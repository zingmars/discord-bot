const cleverbot = require("./cleverbot-free/index.js");
const process = require('process');

let debug = false;
if (process.env.CLEVERBOT_HISTORY_LOG === 'true') debug = true;
const history = [];

let fs = null;
if (debug) {
    fs = require('fs');
}

// Handle incoming messages
process.stdin.on('data', data => {
    let message = data.toString();
    cleverbot(message, history).then(response => {
        history.push(message, response);
        if (history.length > 60) {
            history.splice(0, 2);
        }

        if (fs) {
            fs.writeFileSync("./Log/debug.log", JSON.stringify(history));
        }
        console.log(response);
    });
});

// Loop to keep the process alive
setInterval(function () {}, 1000);