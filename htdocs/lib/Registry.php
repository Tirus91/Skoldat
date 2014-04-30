<?php
/**
 * Registry.php
 * this file is contains Registry class definition
 *
 * @copyright Copyright (c) 2009 Igor Hlina
 * @license read LICENCE.txt
 *
 */


/**
 * Registry class definition
 * Registry is a special object for global data
 *
 */
class Registry Implements ArrayAccess
{

    /**
     * data holder
     *
     * @var array
     */
    private $vars = array();


    public function set($key, $var)
    {
        if (isset($this->vars[$key]) == true) {
            throw new Exception('Unable to set var `' . $key . '`. Already set.');
        }

        $this->vars[$key] = $var;
        return true;
    }


    public function get($key)
    {
        if (isset($this->vars[$key]) == false) {
            return null;
        }

        return $this->vars[$key];
    }


    public function remove($key)
    {
        unset($this->vars[$key]);
    }

    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->vars[$offset]);
    }
}
