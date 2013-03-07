<?php
/**
 * Spanish Lang
 * 
 * @package     VGSys
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */

$lang['join_now'] = 'Únete a {site} hoy';
$lang['login_to'] = 'Iniciar sesión en {site}';
/* SIGNUP */
$lang['checking'] = 'Validando...';

$lang['tip_full_name'] = 'Ingresa tu nombre y apellido.';
$lang['tip_email'] = '¿Cual es tu dirección de correo electrónico?';
$lang['tip_password'] = '¡6 caracteres o más! Sé ingenioso.';
$lang['tip_birthday'] = '¿Cuando es tu cumpleaños?';

$lang['ok_full_name'] = 'El nombre se ve genial.';
$lang['invalid_full_name'] = 'Debes proporcionar tu nombre completo.';

$lang['ok_email'] = 'Te enviaremos una confirmación por correo electrónico.';
$lang['taken_email'] = 'Este correo electrónico ya está registrado.';
$lang['invalid_email'] = 'No parece ser un correo electrónico valido.';

$lang['perfect_password'] = '¡La contraseña es perfecta!';
$lang['ok_password'] = 'La contraseña está bien.';
$lang['weak_password'] = 'La contraseña podría ser mas segura.';
$lang['weake_password'] = 'La contraseña no es suficientemente segura.';
$lang['invalid_password'] = 'La contraseña debe ser de al menos 6 caracteres.';

$lang['invalid_password2'] = 'Las contraseñas no son iguales.';

$lang['ok_birthday'] = '&nbsp;';
$lang['invalid_birthday'] = 'La fecha no es correcta.';
/* SIGIN */
$lang['signin'] = 'Iniciar sesión';
/* VERIFY */
$lang['thanks'] = 'Gracias!';
$lang['thanks_for_signup'] = 'Gracias por registrarte en {site}';
$lang['verify_your_email'] = 'Verifica tu email';
$lang['verify_description'] = 'Antes de poder utilizar nuestros servicios debemos confirmar tu correo electrónico, te hemos enviado un correo a <strong>{email}</strong> con las instrucciones para activar tu cuenta. <br><br>Revisa tu bandeja de correo no deseado, si en 24 horas no recibes el correo puedes <a href="{link}">solicitar uno nuevo</a>.';
$lang['verify_description_resend'] = 'Te hemos enviado un correo a <strong>{email}</strong> con las instrucciones para activar tu cuenta.';
$lang['verify_error_description'] = 'Lo sentimos pero el código de verificación no es válido, puedes solicitar uno nuevo ingresando tu correo en el siguiente formulario.';
$lang['verify_error_description_resend'] = 'Si no has resibido nuestro correo de verificación, puedes solicitar uno nuevo ingresando tu correo en el siguiente formulario.';
$lang['verify_already_verified'] = 'La cuenta asociada a este correo electrónico ya se encuentra verificada.';
$lang['verify_email_no_exists'] = 'El correo electrónico que has ingresado no se encuentra en nuestra base de datos.';
$lang['your_email_has_been_verified'] = 'Tu correo ha sido verificado, ahora puedes iniciar sesión.';

/* LOGIN ERROR */
$lang['invalid_email'] = 'Dirección de correo electrónico no válida. Inténtalo nuevamente.';
$lang['invalid_password'] = 'La contraseña no es válida.';

/* RESET PASSWORD */
$lang['reset_password'] = 'Restablecer contraseña';
$lang['reset_password_description'] = 'Por favor ingresa tu correo electrónico para poder enviarte una nueva contraseña.';
$lang['reset_password_description_sent'] = 'Te hemos enviado un correo a <strong>{email}</strong> con las instrucciones para restablecer tu contraseña.';
$lang['first_verify_account'] = 'Debes <a href="' . Core::getLib('url')->makeUrl('account.verify.resend') . '">verificar tu cuenta</a> antes de poder restablecer tu contraseña.';
$lang['new_password'] = 'Por favor ingresa la que será tu nueva contraseña.';
$lang['change_password'] = 'Cambiar contraseña';
$lang['password_change_success'] = 'Ahora puedes ingresar con tu nueva contraseña.';
$lang['reset_error_description'] = 'Lo sentimos pero el código de verificación no es válido, por favor ingresa tu correo electrónico para poder enviarte una nueva contraseña.';