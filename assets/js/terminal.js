(function(promptData, data) {
    console.log(data, promptData);
    const command = document.querySelector('#command');
    const terminal = document.querySelector('#console');
    let historyKey = -1;
    let history = JSON.parse(document.querySelector('#history').getAttribute('value'));

    // if (data)
    let consoleContent = document.querySelector('#console-content');
    consoleContent.innerHTML += buildPrompt(promptData);

    command.focus();

    function updateHistory(command) {
        if (command !== '' || history[0] !== command) {
            console.log(history);
            Object.assign({0: command}, history);
            console.log(history);
        }
    }

    command.addEventListener('keydown', function (event) {
        let changeValue = false;
        let value = '';
        if (event.keyCode === 13) {
            event.preventDefault();
            let cmd = command.innerHTML;
            executeCommand(cmd);
            updateHistory(cmd);
            command.innerHTML = '';
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
})(JSON.parse(promptData), data === undefined ? {} : JSON.parse(data));



function executeCommand(command) {
    postData('index.php', {command: command})
        .then(data => setResponse(data))
        .catch(error => console.error(error));
        if (command === 'clear') {
            location.reload();
        }
}

function setResponse(data)
{
    console.log(data);
    document.querySelector('#console-content').innerHTML += buildResponse(data);
}

function buildResponse(data){
    let lines = '';
    data.response.forEach(function (value) {
        lines += `<span class="line">${value}</span>`;
    });
    let result = `<span class="old-command">${data['old-command']}</span><span class="response">${lines}</span>`;

    return result + buildPrompt(data.prompt);
}

function buildPrompt(data){
    let operator = data.isAdmin ? '#' : '$';

    return `<span id=prompt class="inputLine"><span class="user">${data.user}</span><span class="atHost">@</span><span class="host">${data.host}</span><span class="host-seperator">:</span><span class="root">${data.root}</span><span class="operator">${operator}</span>&nbsp;</span>`;
}

function postData (url = '', data = {}) {
    console.log(data, JSON.stringify(data));
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
        .then(response => response.json());
}
