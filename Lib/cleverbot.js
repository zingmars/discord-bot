const cleverbot = require("./cleverbot-free/index.js");
const process = require('process');

process.stdin.on('data', data => {
    console.log(data.toString());
});

setInterval(function () {}, 1000);

// Without context
//cleverbot("Hello.").then(response => /*...*/);

// With context
// Please note that context should include messages sent to Cleverbot as well as the responses
//cleverbot("Bad.", ["Hi.", "How are you?"]).then(response => /*...*/);
