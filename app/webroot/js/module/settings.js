
var $Settings = {
    // Sección actual
    section: '',
    // Establecer sección
    setSection: function (name)
    {
        this.section = name;
    },
    // Ocultar
    hideSettings: function ()
    {
        $('.otSettingsList .content').hide();
        $('.otSettingsListLink').show();
    },
    // Mostrar
    showSetting: function ()
    {
        this.hideSettings();
        
        var obj = $('#Settings' + this.section);
        
        obj.find('.content').show();
        obj.find('.otSettingsListLink').hide();
    },
    // Cancelar
    cancel: function ()
    {
        // Ocultamos items activos
        this.hideSettings();
        
        // Reset...
        $('.otSettingsList .content').html('');
    },
    error: function (message)
    {
        var obj = $('#Settings' + this.section).find('.otSettingsEditor').parent();
        obj.prepend('<div class="control-error"><div><span>' + message + '</span></div></div>');   
    },
    // Error input
    errorInput: function (id, message)
    {
        var obj = $('#' + id).parent();
        
        obj.find('.error').remove();
        obj.append('<span class="help-inline error">' + message + '</span>');
        obj.parent().find('.control-label').addClass('otSettingsError');
    },
    // Éxito
    success: function ()
    {
        this.cancel();
        
        $('#Settings' + this.section).find('.otSettingsListLink').effect('highlight', {color: '#FFF9C5'}, 1000);
    },
    // Actualizar valor
    update: function (value)
    {
        $('#Settings' + this.section).find('.otSettingsListItemContent').html(value);  
    },
    // Ocultar errores...
    clean: function ()
    {
        var obj = $('#Settings' + this.section).find('.otSettingsEditor').parent();
        
        obj.find('.control-error, .error').remove();
        obj.find('.otSettingsError').removeClass('otSettingsError');
    }
}