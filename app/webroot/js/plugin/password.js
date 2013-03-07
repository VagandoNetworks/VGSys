/**
 * Password Strength Meter
 * 
 * @author Ivan Molina Pavana <montemolina@live.com>
 */
$(function (){
    
    // New passowrd
    $('#change-password #password').keyup(function(){
        password.check_password($(this));
    });
    
    // Cuando envie validar de nuevo
    $('#change-password').submit(function(){
        //Ejecuto comprobacion de cada input dentro del paso
        var pass = true;
        var inputs = $('#change-password :input');
        
        inputs.each(function(){
            password.check($(this))
            if(password.data_status[$(this).attr('name')] != 'ok')
            pass = false;
        });
        
        // Enviamos los datos...
        if(pass)
        {
            return true;
        }
        
        return false;
    });
})

var password = {
    score: 0,
    level: 0,
    data: new Array(),
    data_status: new Array(),
    styles: ['invalid', 'weake', 'weak', 'ok', 'perfect'],
    test: function (password)
    {
        this.score = 0; 
        
        //password < 4
        if (password.length < 6 ) { this.level = 0; return}
        
        //password length
        this.score += password.length * 6;
        this.score += ( this.checkRepetition(1,password).length - password.length ) * 1;
        this.score += ( this.checkRepetition(2,password).length - password.length ) * 1;
        this.score += ( this.checkRepetition(3,password).length - password.length ) * 1;
        this.score += ( this.checkRepetition(4,password).length - password.length ) * 1;
        
        //password has 3 numbers
        if (password.match(/(.*[0-9].*[0-9].*[0-9])/)){ this.score += 5;} 
        
        //password has 2 symbols
        if (password.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)){ this.score += 5 ;}
        
        //password has Upper and Lower chars
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)){  this.score += 10;} 
        
        //password has number and chars
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)){  this.score += 15;} 
        //
        //password has number and symbol
        if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([0-9])/)){  this.score += 15;} 
        
        //password has char and symbol
        if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([a-zA-Z])/)){this.score += 15;}
        
        //password is just a numbers or chars
        if (password.match(/^\w+$/) || password.match(/^\d+$/) ){ this.score -= 10;}
        
        //verifying 0 < this.score < 100
        if ( this.score < 0 ){ this.score = 0} 
        if ( this.score > 100 ){ this.score = 100; this.level = 4; return}
        
        if (this.score < 32 ){ this.level = 1; return} 
        if (this.score < 64 ){ this.level = 2; return}
        
        // Buena contraseña
        this.level = 3;
    },
    checkRepetition: function (pLen,str)
    {
     	var res = "";
        for (var i=0; i<str.length ; i++ ) 
        {
            var repeated=true;
            
            for (var j=0;j < pLen && (j+i+pLen) < str.length;j++){
                repeated=repeated && (str.charAt(j+i)==str.charAt(j+i+pLen));
            }
            if (j<pLen){repeated=false;}
            if (repeated) {
                i+=pLen-1;
                repeated=false;
            }
            else {
                res+=str.charAt(i);
            }
        }
        return res;       
    },
    check: function(obj) 
    {
        var field = obj.attr('name');
        var value = obj.val();
        
        switch(field)
        {
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