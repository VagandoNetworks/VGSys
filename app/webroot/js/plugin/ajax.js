var cacheAjaxRequest = null;
window.onbeforeunload = function() 
{
	if (cacheAjaxRequest !== null)
	{
		cacheAjaxRequest.abort();
	}	
}
$(document).ajaxStart(function (){
    $('#loading').removeClass('hide');
});
$(document).ajaxStop(function (){
    $('#loading').addClass('hide');
})
/**
 * Crear una petición AJAX
 *
 * @param	string	sFunction	Name of the function we plan to use
 * @param	string	sId	Form ID
 */
$.fn.ajaxCall = function (call, extra, noForm, type)
{
    if (empty(type))
    {
        type = 'POST';
    }
    
    var params = (noForm ? '' : this.getForm());
    if (extra)
    {
        params += '&' + ltrim(extra, '&');
    }
    
    console.log(params);
    
    cacheAjaxRequest = $.ajax({
        type: type,
        url: call,
        dataType: "script",
        data: ltrim(params),
        error: function (request, status, error) 
        {
            alert('Ajax Error: ' + request.responseText);
        }
    });
    
    return cacheAjaxRequest;
}

$.ajaxCall = function(call, extra, type)
{
    return $.fn.ajaxCall(call, extra, true, type);
}

$.fn.getForm = function()
{
	var objForm = this.get(0);	
	var prefix = "";
	var submitDisabledElements = false;
	
	if (arguments.length > 1 && arguments[1] == true)
	{
		submitDisabledElements = true;
	}
	
	if(arguments.length > 2)
	{
		prefix = arguments[2];
	}

	var sXml = '';
	if (objForm && objForm.tagName == 'FORM')
	{
		var formElements = objForm.elements;		
		for(var i=0; i < formElements.length; i++)
		{
			if (!formElements[i].name)
			{
				continue;
			}
			
			if (formElements[i].name.substring(0, prefix.length) != prefix)
			{
				continue;
			}
			
			if (formElements[i].type && (formElements[i].type == 'radio' || formElements[i].type == 'checkbox') && formElements[i].checked == false)
			{
				continue;
			}
			
			if (formElements[i].disabled && formElements[i].disabled == true && submitDisabledElements == false)
			{
				continue;
			}
			
			var name = formElements[i].name;
			if (name)
			{				
				sXml += '&';
				if(formElements[i].type=='select-multiple')
				{
					for (var j = 0; j < formElements[i].length; j++)
					{
						if (formElements[i].options[j].selected == true)
						{
							sXml += name+"="+encodeURIComponent(formElements[i].options[j].value)+"&";
						}
					}
				}
				else
				{
					sXml += name+"="+encodeURIComponent(formElements[i].value);
				}
			}
		}
	}	

	if ( !sXml && objForm)
	{
		sXml += "&" + objForm.name + "="+ encodeURIComponent(objForm.value);
	}	
	
	return sXml;
}