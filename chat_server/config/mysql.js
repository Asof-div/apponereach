const mysql = require('mysql');
const config = require('./config');

module.exports = function() {
	const connection = mysql.createConnection({
		host: config.mysql_host,
		user: config.mysql_user,
		password: config.mysql_password,
		database: config.mysql_database
	});

	connection.connect(function (err) {
		if (err) {
			console.error(`error connecting: ${err.stack}`);
			return;
		}
	});

	return connection;
}
