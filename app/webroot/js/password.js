/**
 * Password Strength Meter
 * 
 * @author Ivan Molina Pavana <montemolina@live.com>
 * @param objPwd ID del campo password.
 */
var password = {
    score: 0,
    level: 0,
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
    }
}