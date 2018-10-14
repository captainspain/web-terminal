<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME') ?> - CaptainSpain</title>

    <link rel="stylesheet" href="<?= asset("/css/app.css"); ?>">
    <link rel="stylesheet" href="<?= asset("/css/darcula.css"); ?>">
</head>
<body>
<div id="console">
    <input type="hidden" id="history" value="<?= $this->getHistoryFromData(true); ?>">
    <span class="welcome">
 ██╗    ██╗███████╗██████╗        ████████╗███████╗██████╗ ███╗   ███╗██╗███╗   ██╗ █████╗ ██╗
 ██║    ██║██╔════╝██╔══██╗       ╚══██╔══╝██╔════╝██╔══██╗████╗ ████║██║████╗  ██║██╔══██╗██║
 ██║ █╗ ██║█████╗  ██████╔╝ █████╗   ██║   █████╗  ██████╔╝██╔████╔██║██║██╔██╗ ██║███████║██║
 ██║███╗██║██╔══╝  ██╔══██╗ ╚════╝   ██║   ██╔══╝  ██╔══██╗██║╚██╔╝██║██║██║╚██╗██║██╔══██║██║
 ╚███╔███╔╝███████╗██████╔╝          ██║   ███████╗██║  ██║██║ ╚═╝ ██║██║██║ ╚████║██║  ██║███████╗
  ╚══╝╚══╝ ╚══════╝╚═════╝           ╚═╝   ╚══════╝╚═╝  ╚═╝╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝  ╚═╝╚══════╝
    </span><br />

    <span id="console-content">
        <?php foreach ($this->data as $data): ?>
            <?php $output = new CaptainSpain\WebTerminal\TerminalOutput($data) ?>
            <?= $output->getPrompt(); ?>
            <?= $output->getOldCommand(); ?>
            <?= $output->getResponse(); ?>
        <?php endforeach; ?>

        <?= (new CaptainSpain\WebTerminal\TerminalOutput($inputData))->getPrompt() ?>
    </span>
    <span id="command" contenteditable></span>
</div>
<script type="text/javascript" src="<?= asset('/js/app.js'); ?>"></script>
</body>
</html>
