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

    <div>Abstract</div>
    <div id="abstracts"></div>

    <div>Titel des Vortrages</div>
    <input class="round einput" id="vname" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Vortragender</div>
    <input class="round einput" id="vautor" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Beschreibung</div>
    <textarea rows="3" class="round einput" id="vbeschreibung" onclick="$('#vfeedback').hide();"></textarea>

    <div>Tag</div>
    <select id ="tag" class="round">
        <option value="1">Tag 1 (21.11.2013)</option>
        <option value="2">Tag 2 (22.11.2013)</option>
    </select>

    <div>Startzeit (im Format 2013-12-24 23:45)</div>
    <input class="round einput" id="vstart" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Endzeit (im Format 2013-12-24 23:45)</div>
    <input class="round einput" id="vend" type="text" onclick="$('#vfeedback').hide();"/>

    <div>Pause <input class="round" id="pause" type="checkbox" onclick="$('#vfeedback').hide();"/></div>

    <div></div>
    <span id="vbtn" class="round" onclick="vortragAnlegen()">Anlegen</span>
    <img src="ajax-loader.gif" alt="loading" title="loading" class="loading hidden"/>
    <span id="vfeedback"></span>
    <script>leseAbstracts()</script>
</div>
</body>
</html>
