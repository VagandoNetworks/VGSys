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
 * Cachear las plantillas.
 * 
 * Esta clase se encarga de analizar y transformar una plantilla en un
 * archivo PHP funcional. Lo guarda en caché.
 * 
 * @package     Polaris
 * @subpackage  Core
 * @category    Library
 * @author      Ivan Molina Pavana <montemolina@live.com>
 */
class Core_Template_Cache {
    
	/**
	 * Delimitador izquierdo: {
	 *
	 * @var string
	 */
	protected $leftDelim = '{';
	
	/**
	 * Delimitador derecho: {
	 *
	 * @var string
	 */
	protected $rightDelim = '}';
        
	/**
	 * Foreach stack.
	 * 
	 * @var array
	 */
	private $_foreachElseStack = array();
    
	/**
	 * Literal blocks. {literal}{/literal}
	 * 
	 * @var array
	 */
	private $_literals = array();
    
	/**
	 * String regex.
	 * 
	 * @var string
	 */
	private $_sDbQstrRegexp = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';
	
	/**
	 * String regex.
	 * 
	 * @var string
	 */	
	private $_sSiQstrRegexp = '\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'';
	
	/**
	 * Bracket regex.
	 * 
	 * @var string
	 */	
	private $_sVarBracketRegexp = '\[[\$|\#]?\w+\#?\]';
	
	/**
	 * Variable regex.
	 * 
	 * @var string
	 */	
	private $_sSvarRegexp = '\%\w+\.\w+\%';
	
	/**
	 * Function regex.
	 * 
	 * @var string
	 */	
	private $_sFuncRegexp = '[a-zA-Z_]+';
    
    /**
     * Plugins que serán cargados.
     * 
     * @var array
     */
    private $_plugins = array();
    
	/**
	 * Class constructor. Build all the regex we will be using
	 * with this class.
	 */
	public function __construct()
	{
		$this->_sQstrRegexp = '(?:' . $this->_sDbQstrRegexp . '|' . $this->_sSiQstrRegexp . ')';

		$this->_sDvarRegexp = '\$[a-zA-Z0-9_]{1,}(?:' . $this->_sVarBracketRegexp . ')*(?:\.\$?\w+(?:' . $this->_sVarBracketRegexp . ')*)*';

		$this->_sCvarRegexp = '\#[a-zA-Z0-9_]{1,}(?:' . $this->_sVarBracketRegexp . ')*(?:' . $this->_sVarBracketRegexp . ')*\#';

		$this->_sVarRegexp = '(?:(?:' . $this->_sDvarRegexp . '|' . $this->_sCvarRegexp . ')|' . $this->_sQstrRegexp . ')';

		$this->_sModRegexp = '(?:\|@?[0-9a-zA-Z_]+(?::(?>-?\w+|' . $this->_sDvarRegexp . '|' . $this->_sQstrRegexp .'))*)';		
	}
    
    // --------------------------------------------------------------------
    
    /**
     * Compilar plantilla
     * 
     * @access public
     * @param string
     * @param string
     * @return void
     */
    public function compile($name, $data = NULL)
    {
        $data = $this->_parse($data);
        
		$content = '';
		$aLines = explode("\n", $data);

		foreach ($aLines as $line)
		{
			if (preg_match("/<\?php(.*?)\?>/i", $line))
			{
				if (substr(trim($line), 0, 5) == '<?php')
				{
					$content .= trim($line) . "\n";
				}
				else
				{
					$content .= $line . "\n";
				}
			}
			else
			{
				$content .= $line . "\n";
			}
		}

		if ($file = @fopen($name, 'w+'))
		{
			fwrite($file, $content);
			fclose($file);
		}
		else
		{
			return Core_Error::trigger('No se puede cachear la plantilla: ' . $name);
		}
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Parsear plantilla y convertir a PHP
     * 
     * @access private
     * @param string $data Contenido de la plantilla
     * @return string Contenido parseado.
     */
    private function _parse($data)
    {
		$ldq = preg_quote($this->leftDelim);
		$rdq = preg_quote($this->rightDelim);
		$text = array();
		$compiledText = '';
        
        // eliminar comentarios
        $data = preg_replace("/{$ldq}\*(.*?)\*{$rdq}/se", "", $data);
        
		// remove literal blocks
		preg_match_all("!{$ldq}\s*literal\s*{$rdq}(.*?){$ldq}\s*/literal\s*{$rdq}!s", $data, $matches);
		$this->_literals = $matches[1];
		$data = preg_replace("!{$ldq}\s*literal\s*{$rdq}(.*?){$ldq}\s*/literal\s*{$rdq}!s", stripslashes($ldq . "literal" . $rdq), $data);
        
        $text = preg_split("!{$ldq}.*?{$rdq}!s", $data);
        
		preg_match_all("!{$ldq}\s*(.*?)\s*{$rdq}!s", $data, $matches);
		$tags = $matches[1];
        
		$compiledTags = array();
		$totalCompiledTags = count($tags);
		for ($i = 0, $forMax = $totalCompiledTags; $i < $forMax; $i++)
		{
			$compiledTags[] = $this->_compileTag($tags[$i]);
		}
        
        $countCompiledTags = count($compiledTags);
		for ($i = 0, $forMax = $countCompiledTags; $i < $forMax; $i++)
		{
			if ($compiledTags[$i] == '')
			{
				$text[$i+1] = preg_replace('~^(\r\n|\r|\n)~', '', $text[$i+1]);
			}
			$compiledText .= $text[$i].$compiledTags[$i];
		}
		$compiledText .= $text[$i];
        
		$compiledText = preg_replace('!\?>\n?<\?php!', '', $compiledText);

		$compiledHeader = '<?php /* Cached: ' . date("F j, Y, g:i a", time()) . ' */ ?>' . "\n";
        
        // Plugins
        if (count($this->_plugins))
        {
            $compiledHeader .= '<?php $this->loadPlugins(array(';
            foreach ($this->_plugins as $plugin)
            {
                $compiledHeader .= 'array(\''. $plugin[0] . '\', \'' . $plugin[1] . '\'),';
            }
            $compiledHeader .= ")); ?>\n";
        }
        
        $compiledText = $compiledHeader . $compiledText;

		return $compiledText;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Compilar etiquetas personalizadas. (ej: {literal})
     * 
     * @access private
     * @param string $tag Nombre de la etiqueta
     * @return string Código basado en la etiqueta
     */
    private function _compileTag($tag)
    {
		preg_match_all('/(?:(' . $this->_sVarRegexp . '|' . $this->_sSvarRegexp . '|\/?' . $this->_sFuncRegexp . ')(' . $this->_sModRegexp . '*)(?:\s*[,\.]\s*)?)(?:\s+(.*))?/xs', $tag, $matches);

		if ($matches[1][0]{0} == '$' || $matches[1][0]{0} == "'" || $matches[1][0]{0} == '"')
		{
			return "<?php echo " . $this->_parseVariables($matches[1], $matches[2]) . "; ?>";
		}

		$tagCommand = $matches[1][0];
		$tagModifiers = !empty($matches[2][0]) ? $matches[2][0] : null;
		$tagArguments = !empty($matches[3][0]) ? $matches[3][0] : null;

		return $this->_parseFunction($tagCommand, $tagModifiers, $tagArguments);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Parsear todas las etiquetas personalizadas. En las plantillas no 
     * utilizamos PHP convencional ya que la separamos de la plantilla.
     * Las etiquetas que utilizamos son similares a SMARTY.
     * 
     * @access private
     * @param string $function Nombre de la función
     * @param string $modifiers Modificadores
     * @param string $arguments Argumentos
     * @return string Codigo PHP
     */
    private function _parseFunction($function, $modifiers, $arguments)
    {
        switch($function)
        {
            // Operadores
			case 'for':
				$arguments = preg_replace("/\\$([A-Za-z0-9]+)/ise", "'' . \$this->_parseVariable('\$$1') . ''", $arguments);
				return '<?php for (' . $arguments . '): ?>';
				break;
			case '/for':
				return "<?php endfor; ?>";
			case 'if':
				return $this->_compileIf($arguments);
				break;
			case 'else':
				return "<?php else: ?>";
				break;
			case 'elseif':
				return $this->_compileIf($arguments, true);
				break;
			case '/if':
				return "<?php endif; ?>";
				break;
			case 'foreach':
				array_push($this->_foreachElseStack, false);
				$args = $this->_parseArgs($arguments);
				if (!isset($args['from']))
				{
					return '';
				}
				if (!isset($args['value']) && !isset($args['item']))
				{
					return '';
				}
				if (isset($args['value']))
				{
					$args['value'] = $this->_removeQuote($args['value']);
				}
				elseif (isset($args['item']))
				{
					$args['value'] = $this->_removeQuote($args['item']);
				}

				(isset($args['key']) ? $args['key'] = "\$this->_vars['".$this->_removeQuote($args['key'])."'] => " : $args['key'] = '');

				$result = '<?php if (count((array)' . $args['from'] . ')): ?>' . "\n";

				$result .= '<?php foreach ((array) ' . $args['from'] . ' as ' . $args['key'] . '$this->_vars[\'' . $args['value'] . '\']): ?>';
				return $result;
				break;
			case 'foreachelse':
				$this->_foreachElseStack[count($this->_foreachElseStack)-1] = true;
				return "<?php endforeach; else: ?>";
				break;
			case '/foreach':
				if (array_pop($this->_foreachElseStack))
				{
					return "<?php endif; ?>";
				}
				else
				{
					return "<?php endforeach; endif; ?>";
				}
				break;
            // Funciones de la template
			case 'assign':
				$args = $this->_parseArgs($arguments);
				if (!isset($args['var']))
				{
					return '';
				}
				if (!isset($args['value']))
				{
					return '';
				}
				return '<?php $this->assign(\'' . $this->_removeQuote($args['var']) . '\', ' . $args['value'] . '); ?>';
				break;
			case 'literal':
				list (,$literal) = each($this->_literals);
				return "<?php echo '" . str_replace("'", "\'", $literal) . "'; ?>\n";
				break;
            case 'lang':
                $args = $this->_parseArgs($arguments);
                if ( ! $args['var'])
                {
                    return '';
                }
                $var = $args['var'];
                unset($args['var']);
                $array = '';
                if (count($args))
                {
                    $array = ', array(';
                    foreach ($args as $key => $value)
                    {
                        $array .= '\'' . $key . '\' => ' . $value . ',';
                    }
                    $array = rtrim($array, ',') . ')';
                }
                return '<?php echo Core::getPhrase(' . $var . $array .'); ?>';
                break;
            case 'param':
                $args = $this->_parseArgs($arguments);
                return '<?php echo Core::getParam(\'' . $this->_removeQuote($args['var']) . '\'); ?>';
                break;
            case 'img':
                $args = $this->_parseArgs($arguments);
                $src = $args['src'];
                unset($args['src']);
                $attr = '';
                if (count($args))
                {
                    foreach($args as $tag => $value)
                    {
                        $attr .= $tag . '="' . $this->_removeQuote($value) . '" ';
                    }
                }
                return "<?php echo '<img src=\"" . Core::getParam('core.url_static_img') . $this->_removeQuote($src) . "\" " . $attr . "/>'; ?>";
                break;
            case 'url':
                $args = $this->_parseArgs($arguments);
                if ( ! $args['link'])
                {
                    return '';
                }
                $link = $args['link'];
                unset($args['link']);
                $array = '';
                if (count($args))
                {
                    $array = ', array(';
                    foreach ($args as $key => $value)
                    {
                        $array .= '\'' . $key . '\' => ' . $value . ',';
                    }
                    $array = rtrim($array, ',') . ')';
                }
                return '<?php echo Core::getLib(\'url\')->makeUrl(' . $link . $array .'); ?>';
                break;
            case 'ajax':
                $args = $this->_parseArgs($arguments);
                if ( ! $args['link'])
                {
                    return '';
                }
                return '<?php echo Core::getLib(\'url\')->makeAjax(' . $args['link'] . '); ?>';
            break;
            /** Funciones del layout **/
			case 'title':
				return '<?php echo $this->getTitle(); ?>';
				break;
            case 'meta':
				return '<?php echo $this->getMeta(); ?>';
				break;
			case 'style':
				return '<?php echo $this->getStyles(); ?>';
				break;
            case 'script':
				return '<?php echo $this->getScripts(); ?>';
				break;
            case 'script_vars':
                return '<?php echo $this->getVars();?>';
                break;
            /** Etiquetas Módulos */
            case 'block':
                $args = $this->_parseArgs($arguments);
                return '<?php Core::getBlock(' . $args['name'] . ');?>';
                break;
            /** Funciones Form **/
            case 'html_select_date':
                $args = $this->_parseArgs($arguments);
                if (count($args))
                {
                    $array = ', array(';
                    foreach ($args as $key => $value)
                    {
                        $array .= '\'' . $key . '\' => ' . $value . ',';
                    }
                    $array = rtrim($array, ',') . ')';
                }
                return '<?php echo Core::getLib(\'form.helper\')->selectDate(' . $array . ');?>';
            break;
            // TODO: Checar lo de abajo
            case 'module':
                return Core::getModuleName('string');
            break;
            case 'header':
                return '<?php Core::getBlock(\'core.template-header\');?>';
                break;
			case 'content':
                $content = '';
				$content .= '<?php Core::getLib(\'module\')->getControllerTemplate();?>';
				return $content;
				break;
            case 'footer':
				return '<?php Core::getBlock(\'core.template-footer\'); ?>';
				break;
            case 'debug':
                return '<?php echo Core::getDebug(); ?>';
                break;
                
            /** Cargamos la función como un plugin */
            default:
                $args = $this->_parseArgs($arguments);
                if (count($args))
                {
                    $array = 'array(';
                    foreach ($args as $key => $value)
                    {
                        $array .= '\'' . $key . '\' => ' . $value . ',';
                    }
                    $array = rtrim($array, ',') . ')';
                }
                $this->_plugins[] = array('function', $function);
                return '<?php echo template_function_' . $function . '(' . $array . ');?>';
        }
    }
    
    // --------------------------------------------------------------------
    
   	/**
	 * Parsear argumentos. (ej. {for bar1=sample1 bar2=sample2}
	 *
	 * @param string $arguments Arguments to parse.
	 * @return array ARRAY of all the arguments.
	 */
	private function _parseArgs($arguments)
	{
		$result	= array();
		preg_match_all('/(?:' . $this->_sQstrRegexp . ' | (?>[^"\'=\s]+))+|[=]/x', $arguments, $matches);

		$state= 0;
		foreach($matches[0] as $value)
		{
			switch($state)
			{
				case 0:
					if (is_string($value))
					{
						$name = $value;
						$state= 1;
					}
					else
					{
						Core_Error::trigger("Nombre de atributo no válido");
					}
					break;
				case 1:
					if ($value == '=')
					{
						$state= 2;
					}
					else
					{
						 Core_Error::trigger("Esperando '=' después de '{$lastValue}'");
					}
					break;
				case 2:
					if ($value != '=')
					{
						if(!preg_match_all('/(?:(' . $this->_sVarRegexp . '|' . $this->_sSvarRegexp . ')(' . $this->_sModRegexp . '*))(?:\s+(.*))?/xs', $value, $variables))
						{
							$result[$name] = $value;
						}
						else
						{
							$result[$name] = $this->_parseVariables($variables[1], $variables[2]);
						}
						$state= 0;
					}
					else
					{
						Core_Error::trigger("'=' no puede ser un valor de atributo");
					}
					break;
			}
			$lastValue = $value;
		}

		if($state!= 0)
		{
			if($state== 1)
			{
				Core_Error::trigger("esperando '=' después del nombre de atributo '{$lastValue}'");
			}
			else
			{
				Core_Error::trigger("falta valor del atributo");
			}
		}

		return $result;
	}
    
    // --------------------------------------------------------------------
    
	/**
	 * Compilar declaraciones IF
	 *
	 * @param string $arguments If statment arguments.
	 * @param bool $elseIf TRUE if this is an ELSEIF.
	 * @param bool $while TRUE of this is a WHILE loop.
	 * @return string Returns the converted PHP if statment code.
	 */
	private function _compileIf($arguments, $elseIf = false, $while = false)
	{
		$result = "";
		$args = array();
		$argStack	= array();

		preg_match_all('/(?>(' . $this->_sVarRegexp . '|\/?' . $this->_sSvarRegexp . '|\/?' . $this->_sFuncRegexp . ')(?:' . $this->_sModRegexp . '*)?|\-?0[xX][0-9a-fA-F]+|\-?\d+(?:\.\d+)?|\.\d+|!==|===|==|!=|<>|<<|>>|<=|>=|\&\&|\|\||\(|\)|,|\!|\^|=|\&|\~|<|>|\%|\+|\-|\/|\*|\@|\b\w+\b|\S+)/x', $arguments, $matches);
		$args = $matches[0];

		$countArgs = count($args);
		for ($i = 0, $forMax = $countArgs; $i < $forMax; $i++)
		{
			$arg = &$args[$i];
			switch (strtolower($arg))
			{
				case '!':
				case '%':
				case '!==':
				case '==':
				case '===':
				case '>':
				case '<':
				case '!=':
				case '<>':
				case '<<':
				case '>>':
				case '<=':
				case '>=':
				case '&&':
				case '||':
				case '^':
				case '&':
				case '~':
				case ')':
				case ',':
				case '+':
				case '-':
				case '*':
				case '/':
				case '@':
					break;
				case 'eq':
					$arg = '==';
					break;
				case 'ne':
				case 'neq':
					$arg = '!=';
					break;
				case 'lt':
					$arg = '<';
					break;
				case 'le':
				case 'lte':
					$arg = '<=';
					break;
				case 'gt':
					$arg = '>';
					break;
				case 'ge':
				case 'gte':
					$arg = '>=';
					break;
				case 'and':
					$arg = '&&';
					break;
				case 'or':
					$arg = '||';
					break;
				case 'not':
					$arg = '!';
					break;
				case 'mod':
					$arg = '%';
					break;
				case '(':
					array_push($argStack, $i);
					break;
				case 'is':
					$isArgCount = count($args);
					$isArg = implode(' ', array_slice($args, 0, $i - 0));
					$argTokens = $this->_compileParseIsExpr($isArg, array_slice($args, $i+1));
					array_splice($args, 0, count($args), $argTokens);
					$i = $isArgCount - count($args);
					break;
				default:
					preg_match('/(?:(' . $this->_sVarRegexp . '|' . $this->_sSvarRegexp . '|' . $this->_sFuncRegexp . ')(' . $this->_sModRegexp . '*)(?:\s*[,\.]\s*)?)(?:\s+(.*))?/xs', $arg, $matches);

					if (isset($matches[0]{0}) && ($matches[0]{0} == '$' || $matches[0]{0} == "'" || $matches[0]{0} == '"'))
					{
						$arg = $this->_parseVariables(array($matches[1]), array($matches[2]));
					}

					break;
			}
		}

		if($while)
		{
			return implode(' ', $args);
		}
		else
		{
			if ($elseIf)
			{
				return '<?php elseif ('.implode(' ', $args).'): ?>';
			}
			else
			{
				return '<?php if ('.implode(' ', $args).'): ?>';
			}
		}

		return $result;
	}
    
    // --------------------------------------------------------------------
    
	/**
	 * Parsear variables.
	 *
	 * @param array $variables ARRAY de variables.
	 * @param array $modifiers ARRAY de modificadires.
	 * @return string Converted variable.
	 */
	private function _parseVariables($variables, $modifiers)
	{
		$result = "";
		foreach($variables as $key => $value)
		{
			if (empty($modifiers[$key]))
			{
				$result .= $this->_parseVariable(trim($variables[$key])).'.';
			}
			else
			{
				$result .= $this->_parseModifier($this->_parseVariable(trim($variables[$key])), $modifiers[$key]).'.';
			}
		}
		return substr($result, 0, -1);
	}
    
    // --------------------------------------------------------------------
    
	/**
	 * Parsear una variable específica
	 *
	 * @param string $variable Nombre de la variable que se está analizando.
	 * @return string Variable modificada
	 */
	private function _parseVariable($variable)
	{
		if ($variable{0} == "\$")
		{
			return $this->_compileVariable($variable);
		}
		else
		{
			return $variable;
		}
	}
    
    // --------------------------------------------------------------------
    
    /**
     * Compilar todas las variables
     * 
     * @access private
     * @param string $variable Nombre de variable
     * @return string Variable modificada
     */
    private function _compileVariable($variable)
    {
		$result = '';
		$variable = substr($variable, 1);

		preg_match_all('!(?:^\w+)|(?:' . $this->_sVarBracketRegexp . ')|\.\$?\w+|\S+!', $variable, $matches);
		$variables = $matches[0];
		$varName = array_shift($variables);
        
        // TODO: << variables reservadas
        
        $result = "\$this->_vars['$varName']";
        
		foreach ($variables as $sVar)
		{
			if ($sVar{0} == '[')
			{
				$var = substr($var, 1, -1);
				if (is_numeric($var))
				{
					$result .= "[$var]";
				}
				elseif ($sVar{0} == '$')
				{
					$result .= "[" . $this->_compileVariable($sVar) . "]";
				}
				else
				{
					$parts = explode('.', $var);
					$section = $parts[0];
					$section_prop = isset($parts[1]) ? $parts[1] : 'index';
					$result .= "[\$this->_aSections['$section']['$section_prop']]";
				}
			}
			elseif ($var{0} == '.')
			{
   				$result .= "['" . substr($var, 1) . "']";
			}
			elseif (substr($var,0,2) == '->')
			{
				Core_Error::trigger('Llamar a miembros de objetos no está permitido');
			}
			else
			{
				Core_Error::trigger('$' . $varName.implode('', $variables) . ' es una referencia no válida');
			}
		}
        
		return $result;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Parsear modificadores
     * 
     * @access private
     * @param string $variable
     * @param string $modifiers
     * @return string
     */
    private function _parseModifier($variable, $modifiers)
    {
		$mods = array();
		$args = array();

		$mods = explode('|', $modifiers);
		unset($mods[0]);
		foreach ($mods as $mod)
		{
			$args = array();
			if (strpos($mod, ':'))
			{
				$parts = explode(':', $mod);
				$cnt = 0;

				foreach ($parts as $key => $part)
				{
					if ($key == 0)
					{
						continue;
					}

					if ($key > 1)
					{
						$cnt++;
					}

					$args[$cnt] = $this->_parseVariable($part);
				}

				$mod = $parts[0];
			}

			if ($mod{0} == '@')
			{
				$mod = substr($mod, 1);
				$mapArray = false;
			}
			else
			{
				$mapArray = true;
			}

			$arg = ((count($args) > 0) ? ', '.implode(', ', $args) : '');
            
            // Cargar modificador
			if (function_exists($mod))
			{
				$variable = '' . $mod . '(' . $variable . $arg . ')';
			}
			else
			{
                // Agregamos el plugin a la lista
                $this->_plugins[] = array('modifier', $mod);
                
				$variable = 'template_modifier_' . $mod . '(' . $variable . $arg . ')';
			}
        }
        
        return $variable;
    }
    
    // --------------------------------------------------------------------
    
	/**
	 * Remover quotes de las variables PHP.
	 *
	 * @param string $string PHP variable to work with.
	 * @return string Converted PHP variable.
	 */
	private function _removeQuote($string)
	{
		if (($string{0} == "'" || $string{0} == '"') && $string{strlen($string)-1} == $string{0})
		{
			return substr($string, 1, -1);
		}
		else
		{
			return $string;
		}
	}
}