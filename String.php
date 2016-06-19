<?php

/**
 * Copyright (c) 2010-2016 Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eureka\Component\String;

/**
 * String class to manage string.
 *
 * @author Romain Cottard
 * @version 2.1.0
 */
class String
{

    /**
     * String to manipulate
     * @var string $string
     */
    protected $string = '';

    /**
     * If we use mbstring php extension.
     * @var unknown
     */
    protected static $useMbstring = null;

    /**
     * List of function (standard / mb_string extention)
     * @var array $functions
     */
    protected static $functions = array(

        //~ Without mb_string functions
        0 => array('strlen' => 'strlen','strpos' => 'strpos','substr' => 'substr','truncate' => 'truncate','strtolower' => 'strtolower','strtoupper' => 'strtoupper'),

        //~ With mb_string functions
        1 => array('strlen' => 'mb_strlen','strpos' => 'mb_strpos','substr' => 'mb_substr','truncate' => 'mb_truncate','strtolower' => 'mb_strtolower','strtoupper' => 'mb_strtoupper'));

    /**
     * List of characters mapping.
     * @var array $charMapping
     */
    protected static $charMapping = array('À' => 'a','Á' => 'a','Ä' => 'a','Å' => 'a','Ç' => 'c','È' => 'e','É' => 'e','Ê' => 'e','Ë' => 'e','Ì' => 'i','Í' => 'i','Î' => 'i','Ï' => 'i','Ñ' => 'n','Ò' => 'o','Ó' => 'o','Ô' => 'o','Õ' => 'o','Ö' => 'o','Ø' => 'o','Ù' => 'u','Ú' => 'u','Û' => 'u','Ü' => 'u','Ý' => 'y','à' => 'a','á' => 'a','â' => 'a','ã' => 'a','ä' => 'a','å' => 'a','ç' => 'c','è' => 'e','é' => 'e','ê' => 'e','ë' => 'e','ì' => 'i','í' => 'i','î' => 'i','ï' => 'i','ñ' => 'n','ò' => 'o','ó' => 'o','ô' => 'o','õ' => 'o','ö' => 'o','ø' => 'o','ù' => 'u','ú' => 'u','û' => 'u','ü' => 'u','ý' => 'y','ÿ' => 'y','@' => 'a','Œ' => 'oe','œ' => 'oe','Æ' => 'ae','æ' => 'ae');

    /**
     * List of characters mapping.
     * @var array $charMapping
     */
    protected static $charStrip = array("'" => '-',' ' => '-','.' => '-');

    /**
     * Class constructor.
     * @param string $string
     * @return String Class instance.
     */
    public function __construct($string = '')
    {
        if (static::$useMbstring === null) {
            static::$useMbstring = extension_loaded('mbstring');
        }

        $this->string = $string;

        if (static::$useMbstring) {

            mb_detect_order(array('UTF-8','ASCII'));
            $encoding = mb_detect_encoding($this->string);

            if ($encoding !== false) {
                mb_internal_encoding($encoding);
            }
        }
    }

    /**
     * Return string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }

    /**
     * Strlen function. Use mbstring extension if loaded.
     *
     * @param  string $string String to count chars.
     * @return integer Nb chars.
     * @access public
     */
    public function length()
    {
        $function = static::$functions[(int) static::$useMbstring]['strlen'];

        return $function($this->string);
    }

    /**
     * Strpos function. Use mbstring extension if loaded.
     *
     * @param  string  $haystack The string being checked.
     * @param  string  $needle   The string to find in haystack.
     * @param  integer $offset   The search offset. If it is not specified, 0 is used.
     * @return mixed   False if not found, else position.
     */
    public function strpos($needle, $offset = 0)
    {
        $function = static::$functions[(int) static::$useMbstring]['strpos'];

        return $function($this->string, $needle, $offset);
    }

    /**
     * Substring function. Use mbstring extension if loaded.
     *
     * @param  string  $string String to 'truncate'.
     * @param  integer $start
     * @param  integer $length
     * @return String  Part of string.
     */
    public function substr($start = 0, $length = null)
    {
        $function = static::$functions[(int) static::$useMbstring]['substr'];

        return new String($function($this->string, $start, $length));
    }

    /**
     * Set string to lower case
     *
     * @return String
     */
    public function toLower()
    {
        $function = static::$functions[(int) static::$useMbstring]['strtolower'];

        $this->string = $function($this->string);

        return $this;
    }

    /**
     * Set string to upper case
     *
     * @return String
     */
    public function toUpper()
    {
        $function = static::$functions[(int) static::$useMbstring]['strtoupper'];

        $this->string = $function($this->string);

        return $this;
    }

    /**
     * Trim string
     * @param string $chars List of chars to trim.
     * @return String
     */
    public function trim($chars = " \t\n\r\0\x0B")
    {
        $this->string = trim($this->string, $chars);

        return $this;
    }

    /**
     * Replace '&' by '&amp;'
     *
     * @param    string $string String to encode.
     * @return   string  Encoded string.
     */
    public function amp()
    {
        $this->replace('&amp;', '&')->replace('&', '&amp;');

        return $this;
    }

    /**
     * Get char at index
     * @param integer $index
     * @return string
     */
    public function getChar($index)
    {
        return (isset($this->string[$index]) ? $this->string[$index] : '');
    }

    /**
     * Get char at index
     * @param integer $index
     * @return string
     */
    public function getRandomChar()
    {
        return $this->string[mt_rand(0, $this->length() - 1)];
    }

    /**
     * Check if string is an email.
     *
     * @param    string $string String to encode.
     * @return   string  Encoded string.
     */
    public function isEmail()
    {
        return (bool) preg_match('`^[A-Z0-9]+[A-Z0-9._%+-]*@[A-Z0-9.-]+\.[A-Z]{2,}$`i', $this->string);
    }

    /**
     * Strip string and remove accent and non basic characters.
     *
     * @param    string $string
     * @return   String
     */
    public function strip()
    {
        $this->noAccent()
            ->trim()
            ->toLower()
            ->replace(array_keys(static::$charStrip), array_values(static::$charStrip))
            ->pregReplace(array('#[^a-z0-9-]#S','#-+#S'), array('','-'));

        return $this;
    }

    /**
     * Convert string with accent to same string with no accent.
     *
     * @return String
     */
    public function noAccent()
    {
        $this->string = strtr($this->string, static::$charMapping);

        return $this;
    }

    /**
     * Decode html string into string
     *
     * @param integer $type
     * @param string $encode
     */
    public function htmld($type = ENT_COMPAT, $encode = 'UTF-8')
    {
        $this->string = html_entity_decode($this->string, $type, $encode);

        return $this;
    }

    /**
     * Encode string into html string.
     *
     * @param integer $type
     * @param string $encode
     */
    public function htmle($type = ENT_COMPAT, $encode = 'UTF-8')
    {
        $this->string = htmlentities($this->string, $type, $encode);

        return $this;
    }

    /**
     * Decode html string into string
     *
     * @param integer $type
     */
    public function htmlsd($type = ENT_COMPAT)
    {
        $this->string = htmlspecialchars_decode($this->string, $type);

        return $this;
    }

    /**
     * Encode string into html string.
     *
     * @param integer $type
     * @param string $encode
     */
    public function htmlse($type = ENT_COMPAT, $encode = 'UTF-8')
    {
        $this->string = htmlspecialchars($this->string, $type, $encode);

        return $this;
    }

    /**
     * Concat string with current string.
     * @param string $string String to concat
     * @param boolean $prepend Boolean
     */
    public function concat($string, $prepend = false)
    {
        $this->string = $prepend ? (string) $string . $this->string : $this->string . (string) $string;

        return $this;
    }

    /**
     * Preg replace text in string.
     *
     * @param string $pattern
     * @param string $replace
     * @param integer $limit
     * @param integer $count
     * @return String
     */
    public function pregReplace($pattern, $replace, $limit = -1, &$count = null)
    {
        $this->string = preg_replace($pattern, $replace, $this->string, $limit, $count);

        return $this;
    }

    /**
     * Replace text in string.
     *
     * @param string|array $search
     * @param string|array $replace
     * @param integer $count
     * @return String
     */
    public function replace($search, $replace, &$count = null)
    {
        $this->string = str_replace($search, $replace, $this->string, $count);

        return $this;
    }

    /**
     * Truncate text after x chars (and add suffix, like '...')
     *
     * @param    integer $length
     * @param    string  $suffix
     * @param    boolean $lastSpace
     * @return   String  Truncated string.
     */
    public function truncate($length = 30, $suffix = '', $lastSpace = false)
    {
        $string = clone $this;
        $string->htmld();

        if ($string->length() > $length) {
            $string = $string->substr(0, $length - $string->length());

            if (true === $lastSpace) {
                $string->pregReplace('/\s+?(\S+)?$/', '');
            }

            $string->concat($suffix);
        }

        return $string;
    }

    /**
     * Generate code
     *
     * @param    integer $nbChars Nb characters in code.
     * @return   String
     */
    public function gencode($nbChars = 8)
    {
        $chars = new String('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');

        for ($index = 0; $index < $nbChars; $index ++) {
            $this->string .= $chars->getRandomChar();
        }

        return $this;
    }
}
