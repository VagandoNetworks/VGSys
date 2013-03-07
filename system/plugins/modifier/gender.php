<?php
/**
 * Convertir el genero de un número a una cadena.
 * 
 * @param int $gender Genero guardado en la DB
 * @return string Genero en cadena (Hombre/Mujer)
 */

function template_modifier_gender($gender)
{    
    return Core::getPhrase('core.' . (($gender == 1) ? 'male' : 'female'));
}