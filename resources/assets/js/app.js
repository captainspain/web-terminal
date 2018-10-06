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