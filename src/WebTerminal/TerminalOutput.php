<?php

namespace CaptainSpain\WebTerminal;

class TerminalOutput
{
    /** array */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        $response = $this->data['response'] ?? [];
        $lines = '';
        foreach ($response as $line) {
            $lines .= $this->makeSpan($line, 'line');
        }

        return $this->makeSpan($lines, 'response');
    }

    /**
     * @return string
     */
    public function getOldCommand()
    {
        return $this->makeSpan($this->data['command'], 'command-executed');
    }

    /**
     * @return string
     */
    public function getPrompt()
    {
        $fields = [
            'user' => $this->data['user'],
            'atHost' => '@',
            'host' => $this->data['host'],
            'seperator' => ':',
            'root' => $this->data['root'],
            'operator' => ($this->data['isAdmin'] ?? false) ? '#' : '$',
        ];
        $prompt = '';
        foreach ($fields as $key => $value) {
            $prompt .= $this->makeSpan($value, $key);
        }

        return $this->makeSpan($prompt, 'prompt');
    }

    /**
     * @return string
     */
    private function makeSpan($value, $className)
    {
        return <<<HTML
<span class="{$className}">{$value}</span>
HTML;
    }
}
