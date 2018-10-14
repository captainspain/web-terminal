(function() {
    const command = document.querySelector('#command');
    const terminal = document.querySelector('#console');
    const terminalContent = document.querySelector('#console-content');
    let historyKey = -1;
    let history = JSON.parse(document.querySelector('#history').getAttribute('value'));


    command.focus();

    function executeCommand(commandToExecute) {
        postData('index.php', {command: commandToExecute})
            .then(data => setResponse(data))
            .catch(error => console.error(error));

        command.innerHTML = '';
        if (commandToExecute === 'clear') {
            location.reload();
        }
        updateHistory(commandToExecute);
    }

    function updateHistory(commandToAdd) {
        if (commandToAdd === history[0]) return;
        let newHistory = {0: commandToAdd};
        for (var key in history) {
            key = parseInt(key);
            let newKey = key + 1;
            newHistory[newKey] = history[key];
        }
        history = newHistory;
        historyKey = -1;
    }

    function setResponse(response) {
        terminalContent.innerHTML += response;
    }

    command.addEventListener('keydown', function (event) {
        let changeValue = false;
        let value = '';
        if (event.keyCode === 13) {
            event.preventDefault();
            executeCommand(command.innerHTML);
            return;
        } else if (event.keyCode === 38) { // key: UP (arrow)
            event.preventDefault();
            if (setNewHistoryKey(historyKey + 1)) {
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

function postData (url = '', data = {}) {
    return fetch(url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Request-By": "web-terminal",
        },
        redirect: 'follow',
        referrer: 'no-referrer',
        body: JSON.stringify(data)
    })
        .then(response => response.text());
}
