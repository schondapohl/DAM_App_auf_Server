var io = require('socket.io').listen(8081);
var votingActive = false;
var timeRemaining = -1;
var clientList = new Array();
var User = function () {
    this.id = "";
    this.uid = "";
    this.toString = function () {
        return "User: ID:" + this.id + " uid: " + this.uid;
    };
};

var removeUser = function (userid) {
    for (var i = 0; i < clientList.length; i++) {
        if (clientList[i].id === userid) {
            clientList.remove(i);
        }
    }
};


function informClient(data) {
    for (var i = 0; i < clientList.length; i++) {
        console.log("sende an " + clientList[i].id);
        if (io.sockets.sockets[clientList[i].id] != null) {
            /*removeUser(clientList[i].id);*/
            io.sockets.sockets[clientList[i].id].json.send(data);
        }
    }
}


/* Socket part */
io.sockets.on('connection', function (socket) {
    console.log("A user is connected with id " + socket.id);
    /* Neuen User erstellen */
    var newUser = new User();
    newUser.id = socket.id;
    clientList.push(newUser);
    console.log("Anzahl der User:" + clientList.length);
    socket.on('disconnect', function () {
        console.log("User is disconnected");

    });

    socket.on('startfrage', function (data) {
        console.log("Frage " + data.frage + " gestartet");
        informClient(data);
    });
});