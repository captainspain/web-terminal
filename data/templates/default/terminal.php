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
            $line = $line === '' ? '&nbsp;' : $line;
            $response .= '<span class="new-line" style="display:block">' . $line . '</span>';
        }
        $response = '<span class="response">' . $response . '</span>';
    }

    return <<<HTML
<span class="inputLine"><span class="user">{$data['user']}</span><span class="atHost">@</span><span class="host">{$data['host']}</span>:<span class="root">{$data['root']}</span><span class="operator">{$operator}</span>&nbsp;</span>{$command}{$response}
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
    <style>
        html {
            height: 100%;
            margin: 0;
        }
        body {
            padding: 5px;
        }
        #console {
            /*height: 100%;*/
        }
        #console span, #console input {
            font-family: monospace;
            white-space: pre;
        }
        #console form {
            display: inline;
        }
        .inputLine {
            font-weight: bold;
        }
        .user, .atHost, .host {
            color: green;

        }
        #command {
            outline: none;
            border: none;
        }
    </style>
</head>
<body>
<div id="console">
    <input type="hidden" id="history" value="<?= $this->getHistoryFromData(true); ?>">
    <span class="welcome">
        ██╗    ██╗███████╗██████╗       ████████╗███████╗██████╗ ███╗   ███╗██╗███╗   ██╗ █████╗ ██╗
        ██║    ██║██╔════╝██╔══██╗      ╚══██╔══╝██╔════╝██╔══██╗████╗ ████║██║████╗  ██║██╔══██╗██║
        ██║ █╗ ██║█████╗  ██████╔╝█████╗   ██║   █████╗  ██████╔╝██╔████╔██║██║██╔██╗ ██║███████║██║
        ██║███╗██║██╔══╝  ██╔══██╗╚════╝   ██║   ██╔══╝  ██╔══██╗██║╚██╔╝██║██║██║╚██╗██║██╔══██║██║
        ╚███╔███╔╝███████╗██████╔╝         ██║   ███████╗██║  ██║██║ ╚═╝ ██║██║██║ ╚████║██║  ██║███████╗
         ╚══╝╚══╝ ╚══════╝╚═════╝          ╚═╝   ╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝╚══════╝
    </span><br />
    <?php foreach ($this->data as $data): ?>
        <?= makeInputLine($data); ?>
    <?php endforeach; ?>

    <?= makeInputLine($inputData, false); ?>
    <span id="command" contenteditable></span>
</div>
<script>
    (function() {
        const command = document.querySelector('#command');
        const terminal = document.querySelector('#console');
        let historyKey = -1;
        let history = JSON.parse(document.querySelector('#history').getAttribute('value'));

        command.focus();

        command.addEventListener('keydown', function (event) {
            let changeValue = false;
            let value = '';
            if (event.keyCode === 13) {
                event.preventDefault();
                terminal.innerHTML += `<form id="consoleForm" method="post"><input type="hidden" name="command" value="${command.innerHTML}" /></form>`;
                document.querySelector('#consoleForm').submit();
                return;
            } else if (event.keyCode === 38) { // key: UP (arrow)
                console.log(historyKey);
                event.preventDefault();
                if (setNewHistoryKey(historyKey + 1)) {
                    console.log(`New Key: ${historyKey}`)
                    changeValue = true;
                    value = history[historyKey];
                }
            } else if (event.keyCode === 40) { // key: DOWN (arrow)
                event.preventDefault();
                changeValue = true;
                if (setNewHistoryKey(historyKey - 1)) {
                    if (historyKey === -1) {
                        value = '';
                    } else {
                        value = history[historyKey];
                    }

                }
            }

            if (changeValue && value !== undefined) {
                command.innerHTML = value;
            }
        }, false);

        function setNewHistoryKey(newKey) {
            if (history[newKey] !== undefined || newKey === -1) {
                historyKey = newKey;
                return true;
            }
            return false;
        }

        // Focus input field on document click.
        // Don't focus when selection is not empty.
        document.addEventListener('click', function (e) {
            if (window.getSelection().toString() === '') {
                command.focus();
            }
        }, false);
    })();
</script>
</body>
</html>
