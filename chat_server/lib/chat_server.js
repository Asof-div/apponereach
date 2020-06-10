const socket = require('socket.io');
const pool = require('../config/pgsql');
const moment = require('moment');
let io = null;
let users = [];
let userRooms = {};
let db = pool();

exports.listen = function(server) {
	io = socket.listen(server);

	io.on('connection', function (socket) {
		joinRoom(socket);

		handleIncomingMessages(socket);
	});
}

function joinRoom(socket) {
	socket.on('join', function({user, room}) {

		console.log(room.name);
		socket.join(room.name);

	});
}

function handleIncomingMessages(socket) {
	socket.on('message', function({user, user_type, room, message}) {

		let room_id = room.id;
		let sender = user.id;
		let created_at = moment(Date.now()).format('YYYY-MM-DD HH:mm:ss');
		let sent_time = moment(Date.now()).format('HH:mm');
		let sender_type = getModel(user_type);

		db.query('INSERT INTO chat_conversations  (chat_room_id, message, sender_id, sender_type, created_at) VALUES ($1,$2,$3,$4,$5)', [room_id, message, sender, sender_type, created_at], (err, res) => {
			if (err) throw err;

			socket.broadcast.to(room.name).emit('message', {
				username: `${user_type}: ${user.lastname} ${user.firstname}`,
				text: message,
				sent_time: sent_time
			});
		});
	});
}


function getModel(user){
	let model = "";
	switch(user){
		case 'Admin':
			model = 'App\\Models\\Admin';
		break;
		case 'Operator':
			model = 'App\\Models\\Operator';
		break;
		case 'User':
			model = 'App\\Models\\User';
		break;
		default:
			model = 'App\\Models\\User';
		break;
	}
	return model;
}