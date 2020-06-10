
const net = require('net');
const io = require('socket.io').listen(8001);
const ioClients = [];

const server = net.createServer((socket) => {
    socket.on('data', (msg) => {
        msgData = JSON.parse(msg.toString('utf8'));

        ioClients.forEach((ioClient) => {

            //ioClient.emit(msgData.channel, {msg:msgData.message, link:msgData.link} );

        });


        io.sockets.emit(msgData.channel, {msg:msgData.message, link:msgData.link});
        //console.log(msgData.channel);
    });

}).listen(8002);

io.sockets.on('connection', function (socketIo) {
    //ioClients.push(socketIo);
    console.log(socketIo);
});

io.on('connection', function (socket) {

      
    //io.sockets.emit('broadcast', "welcome");
  

    socket.on('broadcast', function(data){
      
      console.log('some action');
      console.log( data);
    });

    socket.on('Smartlife_notify', function(data){
      
      console.log('some action Smartlife_notify');
      //console.log( data);

    });



});
