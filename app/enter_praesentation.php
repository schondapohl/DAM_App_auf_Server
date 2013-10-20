<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vortrag anlegen</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/votrag.js"></script>
</head>
<body>
<a href="index.html"> <img src="s3.png" alt="damlogo" title="damlogo" id="damlogo"/></a>
<img src="ajax-loader.gif" alt="loading" title="loading" class="loading right hidden"/>

<div class="clearer"></div>
<h1>Vortrag anlegen</h1>

<div id="wrapper" class="round redBg ewrapper">
    <div>Frage im Rahmen der Präsentation</div>
    <input class="round einput" id="pfrage" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Vortragender</div>
    <input class="round einput" id="pautor" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Antwort A</div>
    <input class="round einput" id="pa" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Antwort B</div>
    <input class="round einput" id="pb" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Antwort C</div>
    <input class="round einput" id="pc" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Antwort D</div>
    <input class="round einput" id="pd" type="text" onclick="$('#vfeedback').hide();"/>
    <!--<div>Startzeit (im Format 2013-12-24 23:45)</div>
<input class="round einput" id="vstart" type="text" onclick="$('#vfeedback').hide();"/>
<div>Endzeit (im Format 2013-12-24 23:45)</div>
<input class="round einput" id="vend" type="text" onclick="$('#vfeedback').hide();"/>-->
    <div></div>
    <span id="vbtn" class="round" onclick="praesiAnlegen()">Anlegen</span>
    <img src="ajax-loader.gif" alt="loading" title="loading" class="loading hidden"/>
    <span id="vfeedback"></span>
</div>
</body>
</html>
