<?php

require_once __DIR__ . '/bootstrap.php';

use CaptainSpain\WebTerminal\Logger;
use CaptainSpain\WebTerminal\Request;
use CaptainSpain\WebTerminal\Session;
use CaptainSpain\WebTerminal\Terminal;

$post = $_POST ?? [];
$get = $_GET ?? [];
$server = $_SERVER ?? [];
$session = new Session($_SESSION);
$request = new Request($post, $get, $server, $session);

$filePath = __DIR__ . '/data/' . env('TERMINAL_FILE', 'terminal.json');
$logger = new Logger($filePath);

$terminal = new Terminal($request, $logger, env('APP_THEME', 'default'));


$terminal->init();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME') ?> - CaptainSpain</title>
    <link rel="stylesheet" type="text/css" href="assets/css/terminal.css">
</head>
<body>
<div id="console">
    <input type="hidden" id="history" value="<?= $terminal->getHistoryFromData(true) ?>">
    <span class="welcome">
        ██╗    ██╗███████╗██████╗       ████████╗███████╗██████╗ ███╗   ███╗██╗███╗   ██╗ █████╗ ██╗
        ██║    ██║██╔════╝██╔══██╗      ╚══██╔══╝██╔════╝██╔══██╗████╗ ████║██║████╗  ██║██╔══██╗██║
        ██║ █╗ ██║█████╗  ██████╔╝█████╗   ██║   █████╗  ██████╔╝██╔████╔██║██║██╔██╗ ██║███████║██║
        ██║███╗██║██╔══╝  ██╔══██╗╚════╝   ██║   ██╔══╝  ██╔══██╗██║╚██╔╝██║██║██║╚██╗██║██╔══██║██║
        ╚███╔███╔╝███████╗██████╔╝         ██║   ███████╗██║  ██║██║ ╚═╝ ██║██║██║ ╚████║██║  ██║███████╗
         ╚══╝╚══╝ ╚══════╝╚═════╝          ╚═╝   ╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝╚══════╝
    </span><br />

    <span id="console-content"></span>
    <span id="command" contenteditable></span>
</div>
<script>
    let data = '<?= json_encode($terminal->getData()) ?>';
    let promptData = '<?= json_encode($terminal->getPromtData()) ?>';
</script>
<script src="/assets/js/terminal.js"></script>
</body>
</html>
