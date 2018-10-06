<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= env('APP_NAME') ?> - CaptainSpain</title>

    <link rel="stylesheet" href="<?= asset('/css/default.css') ?>">
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
<script type="text/javascript" src="<?= asset('/js/app.js'); ?>"></script>
</body>
</html>
