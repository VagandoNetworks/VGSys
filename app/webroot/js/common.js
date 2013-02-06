/**
 * Obtener parámetros de las variables
 * globales JavaScript.
 */
function getParam(param)
{
    return params[param];
}
function getPhrase(param)
{
    return lang[param];
}

/**
 * Funciones PHP
 */
/* empty (php.js) */
function empty(a,b){var c;if(a===""||!b&&(a===0||a==="0")||a===null||a===false||typeof a==="undefined")return true;if(typeof a=="object"){for(c in a)return false;return true}return false};

/* checkdate (php.js) */
function checkdate(a,c,b){return a>0&&a<13&&b>0&&b<32768&&c>0&&c<=(new Date(b,a,0)).getDate()};

/* ltrim (php.js) */
function ltrim(e,t){t=!t?" \\s ":(t+"").replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g,"$1");var n=new RegExp("^["+t+"]+","g");return(e+"").replace(n,"")};