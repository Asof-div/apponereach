const http = require('http');
const chatServer = require('./lib/chat_server');
const port = 3000;

const server = http.createServer(function(req, res) {
	if (req.url == '/') {
		res.end('Welcome to Chat server');
	} else {
		res.statusCode = 404;
		res.end('Not found');
	}
});

chatServer.listen(server);

server.listen(port, () => console.log(`Server listening on ${port}`));