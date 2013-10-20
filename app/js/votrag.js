/**
 * Created with JetBrains PhpStorm.
 * User: Markus Zippelt
 * Date: 10.07.13
 * Time: 13:48
 * To change this template use File | Settings | File Templates.
 */


function vortragAnlegen() {
    $('.loading').show();
    if ($('#vname').val() != "") {
        cbpause = 0;
        if( $('#pause').attr('checked')){
            cbpause = 1;
        }
        $.ajax({
            dataType:'jsonp',
            data:{mode:'beschreibungSpeichern', b:$('#vbeschreibung').val()},
            jsonp:'jsonp_callback',
            url:'ajax_vortrag.php',
            success:function (data) {
                $.ajax({
                    dataType:'jsonp',
                    data:{mode:'erstellen', vautor:$('#vautor').val(), vtitel:$('#vname').val(), vbeschreibung:$('#vbeschreibung').val(), vstart:$('#vstart').val(), vend:$('#vend').val(), aid:$('#aidselection').val(), tag:$('#tag').val(), pause:cbpause},
                    jsonp:'jsonp_callback',
                    url:'ajax_vortrag.php',
                    success:function (data) {
                        $('.loading').hide();
                        if (data.erstellt == true) {
                            console.log('Vortrag erstellt');
                            $('#vfeedback').html('Vortrag erstellt');
                            $('.loading').hide();
                            $('#vfeedback').fadeIn('slow');
                            $('input').val('');
                            $('textarea').html('');
                            $('textarea').val('');
                            $("#vfeedback").delay(3000).fadeOut('slow');
                        }
                        else {
                            console.log('Vortrag nicht erstellt');
                            $('#vfeedback').html('Vortrag nicht erstellt');
                            $('.loading').hide();
                            $('#vfeedback').fadeIn('slow');
                            setTimeout(5000);
                            $("#vfeedback").delay(3000).fadeOut('slow');
                        }
                    }
                });
            }
        });


    }
    $('.loading').hide();

}

function leseAbstracts() {
    $.get("ajax_vortrag.php", {
        mode:'leseAbstracts'
    }).done(function (data) {
            $('#abstracts').html(data);
        });
}

function leseAbstractDaten(id) {

    if (id != -1) {
        $.ajax({
            dataType:'jsonp',
            data:{mode:'leseAbstractDaten', aid:id},
            jsonp:'jsonp_callback',
            url:'ajax_vortrag.php',
            success:function (data) {
                $('#vname').val(data.titel);
                $('#vautor').val(data.vorname + " " + data.nachname);
                $('#vbeschreibung').val(data.hintergrund);
            }
        });
    }
    else {
        $('#vname').val("");
        $('#vautor').val("");
        $('#vbeschreibung').val("");
    }

}

function praesiAnlegen() {
    $('.loading').show();
    $.ajax({
        dataType:'jsonp',
        data:{mode:'erstellen', pfrage:$('#pfrage').val(), pautor:$('#pautor').val(), pa:$('#pa').val(), pb:$('#pb').val(), pc:$('#pc').val(), pd:$('#pd').val()},
        jsonp:'jsonp_callback',
        url:'ajax_frage.php',
        success:function (data) {
            $('.loading').hide();
            if (data.erstellt == true) {
                console.log('Vortrag erstellt');
                $('#vfeedback').html('Vortrag erstellt');
                $('.loading').hide();
                $('#vfeedback').fadeIn('slow');
                $('input').val('');
                $("#vfeedback").delay(3000).fadeOut('slow');
            }
            else {
                console.log('Vortrag nicht erstellt');
                $('#vfeedback').html('Vortrag nicht erstellt');
                $('.loading').hide();
                $('#vfeedback').fadeIn('slow');
                setTimeout(5000);
                $("#vfeedback").delay(3000).fadeOut('slow');
            }
        }
    });
}