<?php

namespace CaptainSpain\WebTerminal;

class Terminal
{
    /** @var Request */
    private $request;
    /** @var Logger */
    private $logger;
    /** @var array */
    private $data = [];
    /** @var string */
    private $templatePath;
    /** @var array */
    private $history = [];

    /**
     * Terminal constructor.
     *
     * @param Request $request
     * @param Logger $logger
     * @param string $theme
     */
    public function __construct(Request $request, Logger $logger, string $theme = 'default')
    {
        $this->request = $request;
        $this->logger = $logger;
        $this->templatePath = templates_dir("/$theme/terminal.php");
        $this->data = $logger->getData();
        $this->history = $this->getHistoryFromData();
        $this->setCurrentWorkingDir();
    }

    /**
     * Set the current working directory, if command `cd ...` is used.
     */
    private function setCurrentWorkingDir()
    {
        $cwd = $this->request->session->get(
            'root',
            getcwd()
        );

        if ($cwd !== getcwd()) {
            chdir($cwd);
        }
    }

    /**
     * @param bool $json
     * @return array|mixed|string
     */
    private function getHistoryFromData($json = false)
    {
        if (empty($this->history)) {
            $commands = $this->getCommands();
            $commands = array_reverse($commands);
        } else {
            $commands = $this->history;
        }

        return $json === false
            ? $commands
            : htmlspecialchars(json_encode($commands, JSON_FORCE_OBJECT));
    }

    /**
     * @return array
     */
    private function getCommands()
    {
        $commands = [];
        foreach ($this->data as $data) {
            if ($data['command'] && !in_array($data['command'], $commands, true)) {
                $commands[] = $data['command'];
            }
        }
        return $commands;
    }

    /**
     * @return array
     */
    private function getInputData()
    {
        return [
            'user' => ($user = get_current_user()),
            'root' => $this->normalizeRoot(getcwd(), $user),
            'host' => gethostname(),
            'datetime' => new \DateTime(),
            'isSudo' => $user === 'sudo',
        ];
    }

    /**
     * @param string $command
     *
     * TODO: The checks needs to be refactored. Something more readable..
     */
    private function execute(string $command)
    {
        $command = $this->normalizeCommand($command);
        $data = ['command' => $command];
        if (contains('clear', $command)) {
            $this->data = [];
            $this->request->session->flush();
            return;
        }

        if (contains('cd ', $command)) {
            $this->execCdCommand($command, $data);
        } elseif (contains('sudo ', $command)) {
            $this->execSudoCommand($command, $data);
        } else {
            $response = [];
            exec("$command 2>&1", $response);
            $data['response'] = $response;
        }

        $data = array_merge($this->getInputData(), $data);
        $this->data[] = $data;

        return $data;
    }

    /**
     * @param string $command
     * @param array $data
     */
    private function execCdCommand($command, &$data)
    {
        $path = str_replace('cd ', '', $command);
        $data['root'] = $this->normalizeRoot(getcwd());
        $data['response'] = [''];
        if (chdir($path)) {
            $this->request->session->set('root', getcwd());
            $data['root'] = $this->normalizeRoot(getcwd());
        } else {
            $data['response'] = ['sh: 1: cd: test: No such file or directory'];
        }
    }

    /**
     * @param string $command
     * @param array $data
     */
    private function execSudoCommand($command, &$data)
    {
        if (env('ENABLE_SUDO', 'false') === 'false') {
            $data['response'] = ['Error, `sudo` is disabled.'];
        } else { // USE AT OWN RISK!!
            $sudoPass = env('SUDO_PASSWORD');
            $data['response'] = [];
            $command = str_replace('sudo ', '', $command);
            exec("echo $sudoPass | sudo -S $command 2>&1", $data['response']);
        }
    }

    /**
     * @param string $root
     * @param string $user
     * @return string
     */
    private function normalizeRoot(string $root, string $user = null): string
    {
        $user = $user ?? get_current_user();
        return str_replace("/home/{$user}", '~', $root);
    }

    /**
     * Init, Do's some checks etc...
     * @return void
     */
    public function init(): void
    {
        $request = $this->request;
        if ($request->isPost()) {
            $this->handlePost();
        }
    }

    /**
     * @param string $command
     * @return string
     */
    private function normalizeCommand(string $command)
    {
        $command = escapeshellcmd($command);

        return $command;
    }

    /**
     * Handle's POST, executes the command and redirects to GET.
     */
    private function handlePost()
    {
        $command = $this->request->getPostData('command');

        $result = $this->execute($command);

        if ($this->request->isFetchRequest() && $result !== null) {
            $outputter = new TerminalOutput($result);
            echo ' ' . $outputter->getOldCommand()
                . $outputter->getResponse()
                . $outputter->getPrompt();
            exit();
        }

        header("Location: index.php");
        exit();
    }

    /**
     * Displays the template.
     */
    public function output()
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $inputData = $this->getInputData();
        /** @noinspection PhpIncludeInspection */
        include_once $this->templatePath;
    }

    /**
     * Destructor, Saves all data like session and logs.
     */
    public function __destruct()
    {
        $this->logger->setData($this->data)->save();
        $this->request->session->save();
    }
}
