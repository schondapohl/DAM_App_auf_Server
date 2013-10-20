<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Voting verwaltung</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.knob.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <link href="css/blitzer/jquery-ui-1.10.3.custom.css" rel="stylesheet">
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
</head>
<body>

<a href="index.html"> <img src="s3.png" alt="damlogo" title="damlogo" id="damlogo"/></a>
<img src="ajax-loader.gif" alt="loading" title="loading" class="loading right"/>
<span class="right" id="clientCount"></span>
<div class="clearer"></div>
<div id="wrapper"></div>
<!--<script src="http://localhost:8081/socket.io/socket.io.js"></script>-->
<script src="http://app.emzed.de:8081/socket.io/socket.io.js"></script>
<script>
    var socket = io.connect('http://app.emzed.de:8081');
    socket.on('connecting', function () {
        console.log("Socket is connecting");
    });
    socket.on('connect', function () {
        console.log("Socket is connected");
        socket.emit('defineAdmin', null);
    });
    socket.on('connect_failed', function () {
        console.log("Connection is failed");
    });
    socket.on('message', function (message, callback) {
        console.log(message);
        if(message.typ == 99)
        {
            console.log("Verbunden Clients " + message.count);
            $('#clientCount').html("Verbunden Clients " + message.count);
            $('#clientCount').fadeIn();
        }
    });
    socket.on('reconnecting', function () {
        console.log("Reconnecting to Socket");
    });
    socket.on('reconnect', function () {
        console.log("Reconnection is completed");
    });
    socket.on('reconnect_failed', function () {
        console.log("Reconnection is failed");
    });
    socket.on('disconnect', function () {
        console.log("Socket is disconnected");
    });
</script>
<script type="text/javascript" src="js/admin.js"></script>
<script>initVortrag()</script>
</body>
</html>