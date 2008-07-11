<?php

/**
 * Surrounds a variable with backticks (`)
 * @param string $var
 * @return string
 */

function backtick($var)
{
    return '`'.$var.'`';
}

/**
 * Surrounds a variable with double quotes (")
 * @param string $var
 * @return string
 */

function double_quote($var)
{
    return '"'.$var.'"';
}

/**
 * Surrounds a variable with single quotes (')
 * @param string $var
 * @return string
 */

function single_quote($var)
{
    return "'".$var."'";
}

/**
 * Surrounds a variable with square brackets ([])
 * @param string $var
 * @return string
 */

function sq_brackets($var)
{
    return "[".$var."]";
}

/**
 * Outputs a multidimensional array as a human readable table
 *
 * @param array $arr
 * @return html table
 */
function viewArray($arr)
{
   $ret= '<table cellpadding=0 cellspacing=0 border=1>';
   foreach ($arr as $key1 => $elem1) {
       $ret.= '<tr>';
       $ret.= '<td valign=top><p>'.$key1.'&nbsp;</p></td>';
       if (is_array($elem1)) { $ret.=extArray($elem1); }
       else { $ret.='<td valign=top>'.@htmlspecialchars($elem1).'&nbsp;</td>'; }
       //else { $ret.='<td valign=top>'.($elem1).'&nbsp;</td>'; }
       $ret.= '</tr>';
   }
   $ret.='</table>';
   return $ret;
}

function extArray($arr)
{
   $ret= '<td valign=top>';
   $ret.= '<table cellpadding=0 cellspacing=0 border=1>';
   foreach ($arr as $key => $elem) {
      $ret.= '<tr>';
       $ret.= '<td valign=top>'.$key.'&nbsp;</td>';
       if (is_array($elem)) { $ret.=extArray($elem); }
       else { $ret.= '<td valign=top><p>'.@htmlspecialchars($elem).'&nbsp;</p></td>'; }
       //else { $ret.= '<td valign=top><p>'.($elem).'&nbsp;</p></td>'; }
      $ret.= '</tr>';
   }
   $ret.= '</table>';
   $ret.= '</td>';
   return $ret;
}
function addMonth($date)
{
	return date('Y-m-d',strtotime('+1 month',strtotime($date)));
}

/* JF: Generate a poor man's GUID - might be better to use mysql_query("SELECT UUID()");
 * source: mimec - http://uk.php.net/manual/en/function.uniqid.php#69164
 */
function uuid()
{
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
}

function money($amount, $noentities = false, $nullreturns = false)
{
	//setlocale(LC_MONETARY, 'en_GB');
	if ($amount==0 && $nullreturns)
	{
		return '&nbsp;';
	}
	if ($noentities)
	{
		return '£'.number_format($amount,2,'.',',');
	} else {
		return '&pound;'.number_format($amount,2,'.',',');
	}
}

function datef($date)
{
	return date('m D Y',strtotime($date));
}

/**
 * Converts a php array to a javascript one
 *
 * @param unknown_type $string
 */
function jsarray($array, $literal = false)
{
	if ($literal) return '['.implode(',', $array).']';
	return '["'.implode('","', $array).'"]';
}

/**
 * Writes a string out to a file
 *
 * @param string $filename
 * @param string $data
 */
function writeToFile($filename, $data)
{
	if (!$handle = fopen($filename, 'w')) {
		echo "Cannot open file ($filename)";
		exit;
	}
	
	if (fwrite($handle, $data) === FALSE) 
	{
		echo "Cannot write to file ($filename)";
		exit;
	}
	fclose($handle);
}

/**
 * creates a url or amends the current one by changing the querystring variables
 *
 * @param string $url
 * @param string $querystring
 */
function urlLink($url, $querystring = null)
{
	$db = Zend_Registry::get("db");
	$config =& Zend_Registry::get("config");
	if ($url == "this")
	{
		// use existing query string and add changes in $querystring
		$g = $_GET;
		$p = @$_GET['page'];
		unset($_GET['page']);
		
		$add_gets = explode("&", $querystring);
		$add = array();
		foreach($add_gets as $val)
		{
			$add = explode("=", $val);
			$_GET[$add[0]]=@$add[1];
		}
		$q = array();
		foreach($_GET as $key => $val)
		{
			if (is_array($val))
			{
				foreach($val as $ikey => $ival)
				{
					$q[] = $key.'['.$ikey.']='.$ival;
				}
			} else {
				$q[] = $key.'='.$val;
			}
		}
		$url = $p.'?'.implode('&',$q);
	
		$_GET = $g;
		//$flag_ssl = $db->fetchOne("SELECT flag_ssl FROM pages WHERE idAPage='".$p."'");
	} else {
		//$flag_ssl = $db->fetchOne("SELECT flag_ssl FROM pages WHERE idAPage='".$url."'");
	}
	
	if (($flag_ssl == 1) && ($config->property['development']!='on'))
	{
		$scheme = 'https://';
	} else {
		$scheme = 'http://';
	}
	if ($config->property['canonical_urls'] == 'on')
	{
		$url = $scheme.$_SERVER['HTTP_HOST'] .'/'. $url;
	} else {
		$url = $scheme.$_SERVER['HTTP_HOST'] .'/'. $mainscript.'?page='.$ret;
	}
	return $url;
}


/**
 * gets and sets a session variable from request object
 *
 * @param string $sessionvariable
 * @param string $defaultvalue
 */
function getAndSet($sessionvariable, $defaultvalue = null)
{
	if (is_string(@$_GET[$sessionvariable]))
	{
		$_SESSION[$sessionvariable] = $_GET[$sessionvariable];
	}
	if (is_string(@$_POST[$sessionvariable])) 
	{
		$_SESSION[$sessionvariable] = $_POST[$sessionvariable];
	}
	if (!(@$_SESSION[$sessionvariable])) 
	{
		$_SESSION[$sessionvariable] = $defaultvalue;
	}
}

/**
 * Converts a string to lowercase with hyphens instead of spaces for seo link purposes
 *
 * @param unknown_type $string
 * @return unknown
 */
function seoText($string)
{
	return strtolower(str_replace(" ","-",$string));
}

/**
 * Converts a string to lowercase with underscores instead of spaces for database field purposes
 *
 * @param unknown_type $string
 * @return unknown
 */
function dbText($string)
{
	return strtolower(str_replace(" ","_",$string));
}

/**
 * Converts a string from lowecase with hyphens to Word Case with spaces
 *
 * @param unknown_type $string
 * @return unknown
 */
function unSeoText($string)
{
	return ucwords(str_replace("-"," ",$string));
}

/**
 * Splits up a string into an array similar to the explode() function but according to CamelCase.
 * Uppercase characters are treated as the separator but returned as part of the respective array elements.
 * @author Charl van Niekerk <charlvn@charlvn.za.net>
 * @param string $string The original string
 * @param bool $lower Should the uppercase characters be converted to lowercase in the resulting array?
 * @return array The given string split up into an array according to the case of the individual characters.
 */
function explodeCase($string, $lower = true)
{
  // Split up the string into an array according to the uppercase characters
  $array = preg_split('/([A-Z][^A-Z]*)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
  
  // Convert all the array elements to lowercase if desired
  if ($lower) {
    $array = @array_map(strtolower, $array);
  }
  
  // Return the resulting array
  return $array;
}

function unCamelCase($string)
{
	$arr = explodeCase($string);
	return implode(" ", $arr);
}

/**
 * Removes files and directories matching a wildcard
 *
 * @param string $fileglob
 * @return boolean success/failure
 */
function rm($fileglob)
{
   if (is_string($fileglob)) {
       if (is_file($fileglob)) {
			return unlink($fileglob);
       } else if (is_dir($fileglob)) {
           $ok = rm("$fileglob/*");
           if (! $ok) {
               return false;
           }
           return rmdir($fileglob);
       } else {
           $matching = glob($fileglob);
           if ($matching === false) {
               trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
               return false;
           }    
           $rcs = array_map('rm', $matching);
           if (in_array(false, $rcs)) {
               return false;
           }
       }     
   } else if (is_array($fileglob)) {
       $rcs = array_map('rm', $fileglob);
       if (in_array(false, $rcs)) {
           return false;
       }
   } else {
       trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
       return false;
   }

   return true;
}


function op_obj($obj, $flag = false){

	echo "<pre>";
	print_r($obj);
	echo "</pre>";
	echo "<br />";
	if($flag){
    exit;
	}	
}
