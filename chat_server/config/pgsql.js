const {Pool, Client } = require('pg');
const config = require('./config');

module.exports = function() {
	const connection = new Pool({
		connectionString: config.connectionString
	});

	return connection;
}
