var countdownrunning = false;
var timer = 30;
var _tag = null;
var _monat = null;
var _jahr = null;
var _stunden = null;
var _minuten = null;
var _sekunden = null;

function umfrageStarten(theid) {

    if (!countdownrunning) {
        countdownrunning = true;
        // Zuerst prüfen ob diese Frage bereits läuft oder abgeschlossen ist
        $.ajax({
            dataType:'jsonp',
            data:{frage:theid, mode:'getStatus'},
            jsonp:'jsonp_callback',
            url:'ajax_fragenprozessor.php',
            success:function (data) {
                if (data.neu) {
                    socket.emit('startfrage', data);
                    // Wenn nicht Countdown starten
                    $('#auswahl_' + theid).fadeIn();
                    $('#frage_' + theid).fadeIn();
                    $('#start_' + theid).fadeOut();
                    $('#result_' + theid).fadeOut();
                    $('#reset_' + theid).fadeOut();
                    $('#vCounter_' + theid).fadeIn();

                    var Datum = new Date();
                    _tag = Datum.getDate();
                    _monat = Datum.getMonth() + 1;
                    _jahr = Datum.getFullYear();
                    _stunden = Datum.getHours();
                    _minuten = Datum.getMinutes();
                    _sekunden = Datum.getSeconds() + timer;

                    if (_sekunden > 60) {
                        _minuten = _minuten + 1;
                        _sekunden = _sekunden - 60;
                    }
                    $('#countdown_dashboard_' + theid).show();
                    console.log("Start mit " + _tag + " " + _monat + " " + _jahr + " " + _stunden + " " + _minuten + " " + _sekunden);

                    $('#countdown_dashboard_' + theid).countDown({
                        targetDate:{
                            'day':_tag,
                            'month':_monat,
                            'year':_jahr,
                            'hour':_stunden,
                            'min':_minuten,
                            'sec':_sekunden
                        },
                        onComplete:function () {
                            beendeUmfrage(theid);
                        }
                    });
                }
                else if (data.gestartet) {
                    alert("Fragen läuft bereits.")
                }
                else if (data.beendet) {
                    alert("bereits beendet.")
                }
            }
        });
    }
    else {
        alert("Es läuft bereits eine Frage. Bitte warten bis diese beendet ist.")
    }
}

function beendeUmfrage(theid) {
    console.log("beendeUmfrage() - " + theid);
    $('#reset_' + theid).fadeIn();
    $.ajax({
        dataType:'jsonp',
        data:{frage:theid, mode:'end'},
        jsonp:'jsonp_callback',
        url:'ajax_fragenprozessor.php',
        success:function (data) {
            console.log("beendeUmfrage() - Antwortdaten:");
            console.log(data);
            if (data.beendet) {
                socket.emit('endFrage', data);
                zeigeUmfrageChart(data, theid);
            }
        }
    });
}

function zeigeUmfrageChart(data, theid) {
    gesCount = 0;
    gesCount = data.a + data.b + data.c + data.d;

    if (gesCount > 0) {
        anteilA = data.a / gesCount * 100;
        anteilB = data.b / gesCount * 100;
        anteilC = data.c / gesCount * 100;
        anteilD = data.d / gesCount * 100;
        var chart1 = new AwesomeChart('chartCanvas_' + theid);
        chart1.title = "Abstimmungsergebnis";
        chart1.data = [anteilA, anteilB, anteilC, anteilD];
        chart1.labels = ['Antwort A', 'Antwort B', 'Antwort C', 'Antwort D'];
        chart1.colors = ['#006CFF', '#FF6600', '#34A038', '#945D59'];
        chart1.randomColors = true;
        chart1.animate = true;
        chart1.animationFrames = 30;
        chart1.draw();
    }
    else {
        $('#rw__' + theid).html('Keine Abstimmung stattgefunden');
    }
    $('#rw__' + theid).fadeIn('slow');
    $('#r_wrapper__' + theid).show();
    $('#padder__' + theid).show();
    $('#ergH__' + theid).fadeIn('slow');
    $('#r__' + theid).fadeIn('slow');
    $('#resulth_' + theid).fadeIn('slow');
}

function init() {
    $.get("ajax_frage.php", {mode:"init", frage:"c4ca4238a0b923820dcc509a6f75849b"}).done(function (thedata) {
        $('#wrapper').html(thedata);
        $('.loading').fadeOut();
        initGuiFeatures();
    });
}

function initVortrag() {
    $.get("ajax_vortrag.php", {mode:"init"}).done(function (thedata) {
        $('#wrapper').html(thedata);
        $('.loading').fadeOut();
        initGuiFeaturesVortrag();
    });
}

function umfrageZuruecksetzen(fragenid) {
    $.ajax({
        dataType:'jsonp',
        data:{mode:'refreshall', fid:fragenid},
        jsonp:'jsonp_callback',
        url:'ajax_fragenprozessor.php',
        success:function (data) {
            countdownrunning = false;
            $('#wrapper').html("");
            init();
        }
    });
}

function umfrageLoeschen(fragenid) {
    $.ajax({
        dataType:'jsonp',
        data:{mode:'delete', fid:fragenid},
        jsonp:'jsonp_callback',
        url:'ajax_fragenprozessor.php',
        success:function (data) {
            countdownrunning = false;
            $('#wrapper').html("");
            init();
        }
    });
}

function vortragAktivieren(fragenid) {
    $.ajax({
        dataType:'jsonp',
        data:{mode:'aktivieren', vid:fragenid},
        jsonp:'jsonp_callback',
        url:'ajax_vortragprozessor.php',
        success:function (data) {
            if (data.aktion == "aktivieren") {
                $('#aktiviereVortrag_' + fragenid).fadeOut('slow');
                $('#deaktiviereVortrag_' + fragenid).fadeIn('slow');
                socket.emit('aktiviereVortrag', data);
            }
        }
    });
}

function vortragDeaktivieren(fragenid) {
    $.ajax({
        dataType:'jsonp',
        data:{mode:'deaktivieren', vid:fragenid},
        jsonp:'jsonp_callback',
        url:'ajax_vortragprozessor.php',
        success:function (data) {
            if (data.aktion == "deaktivieren") {
                $('#deaktiviereVortrag_' + fragenid).fadeOut('slow');
                $('#aktiviereVortrag_' + fragenid).fadeIn('slow');
                socket.emit('deaktiviereVortrag', data);
            }
        }
    });
}

function vortragLoeschen(fragenid) {
    $.ajax({
        dataType:'jsonp',
        data:{mode:'loeschen', fid:fragenid},
        jsonp:'jsonp_callback',
        url:'ajax_vortragprozessor.php',
        success:function (data) {
            if (data.aktion == "loeschen") {
                initVortrag();
            }
        }
    });
}

function leseUmfrageErgebnisse(fragenid) {
    $.ajax({
        dataType:'jsonp',
        data:{mode:'leseErgebnisse', fid:fragenid},
        jsonp:'jsonp_callback',
        url:'ajax_frage.php',
        success:function (data) {
            if (data.beendet) {
                socket.emit('endFrage', data);
                $('#auswahl_' + fragenid).show();
                zeigeUmfrageChart(data, fragenid);
            }
        }
    });
}

function initGuiFeatures() {
    $("#icons li").hover(
        function () {
            $(this).addClass("ui-state-hover");
        },
        function () {
            $(this).removeClass("ui-state-hover");
        }
    );

    $("#icons li").click(
        function () {
            fid = this.id.split('_');
            if (fid[0] == "reset") {
                umfrageZuruecksetzen(fid[1]);
            }
            else if (fid[0] == "start") {
                umfrageStarten(fid[1]);
            }
            else if (fid[0] == "del") {
                umfrageLoeschen(fid[1]);
            }
            else if (fid[0] == "result") {
                $('#result_' + fid[1]).fadeOut('fast');
                leseUmfrageErgebnisse(fid[1]);
            }
            else if (fid[0] == "resulth") {
                $('#resulth_' + fid[1]).hide();
                $('#rw__' + fid[1]).fadeOut('fast');
                $('#auswahl_' + fid[1]).fadeOut('fast');
                $('#result_' + fid[1]).show();
            }
        }
    );
    $.ajax({
        dataType:'jsonp',
        data:{mode:'initGuiElements'},
        jsonp:'jsonp_callback',
        url:'ajax_frage.php',
        success:function (data) {
            for (i = 0; i < data.length; i++) {
                $('#start_' + data[i].fid).fadeOut();
                $('#result_' + data[i].fid).fadeIn();
                $('#reset_' + data[i].fid).fadeIn();
            }
        }
    });

}

function initGuiFeaturesVortrag() {
    $("#icons li").hover(
        function () {
            $(this).addClass("ui-state-hover");
        },
        function () {
            $(this).removeClass("ui-state-hover");
        }
    );

    $("#icons li").click(
        function () {
            fid = this.id.split('_');
            if (fid[0] == "loeschenVortrag") {
                vortragLoeschen(fid[1]);
            }
            else if (fid[0] == "aktiviereVortrag") {
                vortragAktivieren(fid[1]);
            }
            else if (fid[0] == "deaktiviereVortrag") {
                vortragDeaktivieren(fid[1]);
            }
        }
    );
}

