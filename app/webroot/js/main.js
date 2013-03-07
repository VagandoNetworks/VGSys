var $Core = {};

/**
 * Inicializar Núcleo
 */
$Core.init = function ()
{
    // Ajaxify
    $(document).on('click', 'a[ajaxify]' , function (){

        var ajax_url = $(this).attr('ajaxify');
        var fake_url = $(this).attr('href');
        
        $.ajaxCall(ajax_url, '__user=' + user['id'] + '&__a=1');
        
        if (fake_url != '#')
            history.pushState(null, fake_url, fake_url);
        
        return false;
    });
}

/**
 * Enviar formulario mediante AJAX
 */
$Core.form = function (obj)
{
    $(obj).ajaxCall(obj.action, '', false);
        
    return false;
}

/**
 * Mensaje de error
 */
$Core.alert = function (message, title)
{
    var buttons = {
        'ok': { 
            text: 'Aceptar',
            click: function (){
                $(this).dialog('close')
                $(this).html('');
            },
            class: 'btn btn-primary'
        }
    };
    
    $Core.dialog(message, (title) ? title : 'Aviso', buttons);
}

/**
 * Dialog
 */
$Core.dialog = function (message, title, buttons)
{
    $('#dialog').html(message).dialog({
        dialogClass: 'no-close',
        modal: true,
        draggable: false,
        resizable: false,
        bgiframe: true,
        minWidth: 465,
        title: title,
        position: {
            at: 'top+200'
        },
        buttons: buttons,
    });
}

/* Al cargar el documento inicializamos el núcleo */
$(function (){
    // Inicializar
    $Core.init();
})

//function to show an emulated Facebook dialogue
function ShowFacebookDialogue(){
	//setup dialogues
	var dialogue = $("#dialogue").dialog({autoOpen: false, modal: true, draggable:false, resizable:false, bgiframe:true});

	//setup options for this dialogue
	$("#dialogue").dialog( "option", "title", 'Facebook - Adding Entry' );
	$("#dialogue").dialog({ buttons: { "Close": function() { $(this).dialog("close"); } } });
	$("#dialogue").dialog( "open" );
	$("#dialogue").html("<p>An example of a JQuery UI dialogue emulating Facebook's styles!</p>");
	$("#dialogue").bind( "dialogbeforeclose", function(event, ui) {
		alert("You can bind events as you normally would to JQuery UI dialogues.");
	});
}