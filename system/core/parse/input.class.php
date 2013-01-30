<?php
/**
 * Polaris Framework
 * 
 * @package     Polaris
 * @author      Ivan Molina Pavana <montemolina@live.com>
 * @copyright   Copyright (c) 2013
 * @since       Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Parse Input
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Parse_Input {
    
	/**
	 * ASCII conversion for URL strings (non-latin character support)
	 *
	 * @var array
	 */
	private $_aAscii = array(
		// Svenska
		'246' => 'o',
		'228' => 'a',
		'229' => 'a',
		'214' => 'O',
		'196' => 'A',
		'197' => 'A',
        // Spanish
        '225' => 'a',
        '233' => 'e',
        '237' => 'i',
        '243' => 'o',
        '250' => 'u'
	);	
	
    
    /**
     * Preparar texto.
     * 
     * Prepara cadenas de texto que puedan contener código HTML, también
     * convierte los emoticones y BBCode.
     * 
     * @access public
     * @param string $txt
     * @return string
     */
    public function prepare($txt)
    {
        // Agregamos los emoticonos
        
        // UTF8 a UNICODE
        $txt = $this->_utf8ToUnicode($txt);
		$txt = str_replace('\\', '&#92;', $txt);
        
        // Limpiamos HTML
        $txt = $this->_htmlspecialchars($txt);
        
        // retornamos
        return $txt;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Limpiar datos
     * 
     * @access public
     * @param string $txt
     * @return string
     */
    public function clean($txt)
    {
        $txt = $this->_htmlspecialchars($txt);
        
        // Unicode
        $txt = $this->_utf8ToUnicode($txt);
		$txt = str_replace('\\', '&#92;', $txt);
        
        //
        return $txt;
    }
    
	/**
	 * Limpia un título para poder ser usado en una URL.
	 *
	 * @param string $title Texto a convertir en URL.
	 * @return string
	 */
	public function cleanTitle($title)
	{
		$title = trim(strip_tags($title));
		$title = $this->_utf8ToUnicode($title, true);		
		$title = preg_replace("/ +/", "-", $title);		
		$title = rawurlencode($title);		
		$title = str_replace(array('"', "'"), '', $title);
		$title = str_replace(' ', '-', $title);
		$title = str_replace(array('-----', '----', '---', '--'), '-', $title);
		$title = rtrim($title, '-');
		$title = ltrim($title, '-');
		
		if (empty($title))
		{
			$title = CORE_TIME;
		}
		
		$title = strtolower($title);

		return $title;
	}
    
    // --------------------------------------------------------------------
    
    /**
     * Limpiar HTML
     * 
     * @access private
     * @param string $txt
     * @return string
     */
	private function _htmlspecialchars($txt)
	{
		$txt = preg_replace('/&(?!(#[0-9]+|[a-z]+);)/si', '&amp;', $txt);
		$txt = str_replace(array(
			'"',
			"'",
			'<',
			'>'
		),
		array(
			'&quot;',
			'&#039;',
			'&lt;',
			'&gt;'
		), $txt);		
		
		return $txt;
	}
    
    // --------------------------------------------------------------------
    
    /**
     * Convertir cadenas de texto UTF8 a UNICODE.
     * 
     * Esto nos permitirá almacenar los datos en un formato UNICODE y así
     * no tener problemas con algunos navegadores ni con la DB.
     * 
     * @access private
     * @param string $str
     * @param bool $forUrl Vamos a transformar para crear un título URL.
     * @return string
     */
    private function _utf8ToUnicode($str, $forUrl = false)
    {
        $unicode = array();
        $values = array();
        $lookingFor = 1;

        for ($i = 0; $i < strlen( $str ); $i++ )
        {
            $thisValue = ord( $str[ $i ] );

            if ( $thisValue < 128 )
            {
            	$unicode[] = $thisValue;
            }
            else
            {
                if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;

                $values[] = $thisValue;

                if ( count( $values ) == $lookingFor ) 
                {
                    $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                    	( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                    $unicode[] = $number;
                    $values = array();
                    $lookingFor = 1;
                }
            }
        }

        return $this->_unicodeToEntitiesPreservingAscii($unicode, $forUrl);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Transforma los caracteres no-latin a UNICODE.
     * 
     * @access private
     * @param string $unicode
     * @param bool $forUrl Vamos a transformar para crear un título URL.
     * @return string
     */
    private function _unicodeToEntitiesPreservingAscii($unicode, $forUrl = false)
    {
        $entities = '';
        foreach( $unicode as $value )
        {
        	if ($forUrl === true)
        	{
        		if ($value == 42 || $value > 127)
        		{
                    $sCacheValue = $this->_parse($value);       
        		
        			$entities .= (preg_match('/[^a-zA-Z]+/', $sCacheValue) ? '-' . $value : $sCacheValue);   			
        		}
        		else 
        		{
        			$entities .= (preg_match('/[^0-9a-zA-Z]+/', chr($value)) ? ' ' : chr($value));
        		}        		
        	}
        	else 
        	{
        		$entities .= ($value == 42 ? '&#' . $value . ';' : ( $value > 127 ) ? '&#' . $value . ';' : chr($value));
        	}
        }
		$entities = str_replace("'", '&#039;', $entities);
        return $entities;
    }
    
    // --------------------------------------------------------------------
    
	/**
	 * Convert ASCII rules.
	 *
	 * @param string $txt Phrase to parse.
	 * @return string Returns the newly parsed string.
	 */
	private function _parse($txt)
	{
		return (isset($this->_aAscii[$txt]) ? $this->_aAscii[$txt] : '&#' . $txt . ';');
	}
}