/**
 * Registro de usuarios
 * 
 * Este archivo se encarga de validar y enviar los datos
 * necesarios para el registro de nuevos usuarios.
 * 
 * @category    JavaScript
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
$(function(){
    // Mostrar información del campo
    $('#signup-form :input').focus(function(){
        signup.show_status($(this), 'tip');
    })
    // Cuando se retire el focus...
    $('#signup-form :input').blur(function(){
        signup.check($(this));
    })
    // Cuando envie validar de nuevo
    $('#signup-form').submit(function(){
        //Ejecuto comprobacion de cada input dentro del paso
        var pass = true;
        var inputs = $('#signup-form :input');
        $('#btnSend').attr('disabled', 'disabled');
        inputs.each(function(){
            signup.check($(this))
            if(signup.data_status[$(this).attr('name')] != 'ok')
            pass = false;
        });
        
        // Enviamos los datos...
        if(pass)
        {
            $(this).ajaxCall('account/create', '', false);
        }
        else
        {
            $('#btnSend').removeAttr('disabled');
        }
        
        return false;
    });
    
    // password
    $('#signup-form #password').keyup(function(){
        signup.check_password($(this));
    });
})

var signup = {
    // Los datos
    data: new Array(),
    data_status: new Array(),
    styles: ['invalid', 'weake', 'weak', 'ok', 'perfect'],
    // Checar campos
    check: function(obj) 
    {
        var field = obj.attr('name');
        var value = obj.val();
        
        switch(field)
        {
            /* full name */
            case 'first_name':
            case 'last_name':
                // Datos
                this.data['first_name'] = $('#first_name').val();
                this.data['last_name'] = $('#last_name').val();
                // Vacío
                if(empty(this.data['first_name']) && empty(this.data['last_name']))
                    return this.show_status(obj, 'tip');
                // Tamaño
                if(this.data['first_name'].length<2 || this.data['last_name'].length<2)
                    return this.show_status(obj, 'invalid');
                // Bien
                return this.show_status(obj, 'ok')
            break;
            /* email */
            case 'email':
                value = value.toLowerCase();
                // Si ya paso por aquí y no hubo cambios...
                if(this.data[field] === value && this.data_status[field] == 'ok')
                    return true;
                // Almaceno dato
                this.data[field] = value;
                // Vacío
                if(empty(value))
                    return this.show_status(obj, 'tip');
                // Tamaño
                if(value.length>35)
                    return this.show_status(obj, 'size')
                // Es un email válido?
                if(!/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/.exec(value))
                    return this.show_status(obj, 'invalid')
                // Validamos
                this.show_status(obj, 'checking');
                //
                $.ajaxCall('account/validate', 'type=email&value=' + value + '&obj=' + obj.attr('id'));
            break;
            /* password */
            case 'password':
                // Si ya paso por aquí y no hubo cambios...
                if(this.data[field] === value && this.data_status[field] == 'ok')
                    return true;
                // Almaceno dato
                this.data[field] = value;
                // Vacío
                if(empty(value))
                    return this.show_status(obj, 'tip');
                    
                // Verificamos...
                return this.show_status(obj, this.data_status[field]);
            break;
            case 'password2':
                // Si ya paso por aquí y no hubo cambios...
                if(this.data[field] === value && this.data_status[field] == 'ok')
                    return true;
                // Almaceno dato
                this.data[field] = value;
                // Vacío
                if(empty(value))
                    return false;
                // Comparamos...
                if (this.data['password'] != this.data['password2'])
                    return this.show_status(obj, 'invalid');
                // Bien
                return this.show_status(obj, 'ok');
            break;
            /* fecha de nacimiento */
            case 'day':
            case 'month':
            case 'year':
                this.data['day'] = $('#day').val();
                this.data['month'] = $('#month').val();
                this.data['year'] = $('#year').val();
                // Vacío
                if(empty(value))
                    return this.show_status(obj, 'tip');
                    
                // Comprobamos fecha correcta
                if(!empty(this.data['day']) && !empty(this.data['month']) && !empty(this.data['year']))
                {
                    if(!checkdate(this.data['month'], this.data['day'], this.data['year']))
                        return this.show_status(obj, 'invalid')
                    // Todo bien
                    return this.show_status(obj, 'ok');
                } else {
                    return this.show_status(obj, 'tip');
                }
            break;
            /* genero */
            case 'gender':
                // Valor
                value = $("input:checked").val();
                // Vacío
                if(empty(value))
                    return this.show_status(obj, 'tip')
                // Ok
                return this.show_status(obj, 'ok');
            break;
            // Para los inputs no requeridos...
            default:
                this.data_status[field] = 'ok';
                return true;
            break;
        }
    },
    // ** Mostramos el estado del campo
    show_status: function(obj, status)
    {
        var tipObj = obj.parent().find('.sidetip');

        // Mostramos    
        tipObj.find('p').removeClass('active');
        tipObj.find('.' + status).addClass('active');
        // Almacenamos el estado
        var field = obj.attr('name');
        this.data_status[field] = (status == 'ok' || status == 'perfect' || status == 'weak') ? 'ok' : status;
        //
        if(status == 'ok')
            return true;
        else
            return false;
    },
    // Validar contraseña
    check_password: function (obj)
    {
        // Checar contraseña
        password.test(obj.val());
        
        $('.score').show();
        $('.fill').animate({
            width: password.score + '%'
        });
        
        this.show_status(obj, this.styles[password.level]);
    }
}
