<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loader
 *
 * @author pahhan
 */
class SP_Loader
{
    protected $_camels = array();
    protected $_prefixes = array();

    private function _splitCamel($string)
    {
        $array = preg_split('/([A-Z][^A-Z]*)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        return $array;
    }

    public function registerCamels(array $camels)
    {
        $this->_camels = $camels;
    }

    public function registerCamel($camel, $path)
    {
        $this->_camels[$camel] = $path;
    }

    public function registerPrefixes(array $prefixes)
    {
        $this->_prefixes = $prefixes;
    }

    public function registerPrefix($prefix, $path)
    {
        $this->_prefixes[$prefix] = $path;
    }

    private function _loadCamel($string)
    {
        $array = $this->_splitCamel($string);
        if (!isset($array[0])) {
            return false;
        }

        $path = $this->getCamelPath($array[0]);
        if ($path) {
            $require_path = $this->_getRequiredPath($path, $this->_arrayToClassPath($array));
            if ($require_path) {
                require_once($require_path);
                return true;
            }
        }

        return false;
    }

    private function _loadPrefix($string)
    {
        $array = explode('_', $string);
        if (!isset($array[0])) {
            return false;
        }

        $path = $this->getPrefixPath($array[0]);
        if ($path) {
            $require_path = $this->_getRequiredPath($path, $this->_arrayToClassPath($array));
            if ($require_path) {
                require_once($require_path);
                return true;
            }
        }

        return false;
    }

    private function _arrayToClassPath($array)
    {
        return implode('/', $array).'.php';
    }

    private function _getRequiredPath($path, $class_path)
    {
        $full_path = $path.'/'.$class_path;
        if (is_readable($full_path)) {
            return $full_path;
        }

        $full_path = $path.'/'.strtolower($class_path);
        if (is_readable($full_path)) {
            return $full_path;
        }

        return false;
    }

    public function getCamelPath($camel)
    {
        if (isset($this->_camels[$camel])) {
            return $this->_camels[$camel];
        }

        return false;
    }

    public function getPrefixPath($prefix)
    {
        if (isset($this->_prefixes[$prefix])) {
            return $this->_prefixes[$prefix];
        }

        return false;
    }

    public function load($class)
    {
        if ($this->_loadCamel($class)) {
            return;
        }
        if ($this->_loadPrefix($class)) {
            return;
        }
    }

    public function register()
    {
        spl_autoload_register(array($this, 'load'));
    }
}
