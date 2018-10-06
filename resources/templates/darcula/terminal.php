<?php

/** @var $this \CaptainSpain\WebTerminal\Terminal */

function makeInputLine($data, $fromData = true)
{
    $command = '';
    $response = '';
    $operator = $data['isSudo'] ? '#' : '$';
    if ($fromData) {
        $command = $data['command'];
        if (!contains('cd ', $command)) {
            $command .= '<br />';
        }
        $command = '<span class="command-executed">' . $command . '</span>';
        $responseData = $data['response'] ?? [];
        $response = '';
        foreach ($responseData as $line) {
            $line = $line === '' ? '&nbsp;' : htmlentities($line);
            $response .= '<span class="new-line" style="display:block">' . $line . '</span>';
        }
        $response = '<span class="response">' . $response . '</span>';
    }

    return <<<HTML
<span class="inputLine"><span class="user">{$data['user']}</span><span class="atHost">@</span><span class="host">{$data['host']}</span>:<span class="root">{$data['root']} </span><span class="operator">{$operator}</span>&nbsp;</span>{$command}{$response}
HTML;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME') ?> - CaptainSpain</title>

    <link rel="stylesheet" href="<?= asset("css", "darcula.css"); ?>">
</head>
<body>
<div id="console">
    <input type="hidden" id="history" value="<?= $this->getHistoryFromData(true); ?>">
    <span class="welcome">
    </span><br />
    <?php foreach ($this->data as $data): ?>
        <?= makeInputLine($data); ?>
    <?php endforeach; ?>

    <?= makeInputLine($inputData, false); ?>
    <span id="command" contenteditable></span>
</div>
<script type="text/javascript" src="<?= asset('js', 'app.js'); ?>"></script>
</body>
</html>
