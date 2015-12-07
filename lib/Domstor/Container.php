<?php

/**
 * Description of Container
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
abstract class Domstor_Container
{
    const TYPE_SHARE = 'share';
    const TYPE_NEW = 'new';
    
    private $params = array();
    private $services = array();
    private $instances = array();

    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        
        return $this;
    }
    
    public function getParam($name, $default = null)
    {
        return array_key_exists($this->params, $name) ? $this->params[$name] : $default;
    }
    
    public function getParamExact($name)
    {
        if (!array_key_exists($this->params, $name)) {
            throw new Exception(sprintf('Undefined parameter "%s"', $name));
        }
        
        return $this->params[$name];
    }
    
    public function defineService($name, array $options)
    {
        if (!isset($options['class']) && !isset($options['factory'])) {
            throw new RuntimeException('"class" or "factory" option must be defined');
        }
        
        if (!isset($options['type'])) {
            $options['type'] = self::TYPE_SHARE;
        }
        
        $this->services[$name] = $options;
    }
    
    public function getService($name)
    {
        if (!isset($this->services[$name])) {
            throw new RuntimeException(sprintf('Undefined service "%s"', $name));
        }
        
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }
        
        $o = $this->services[$name];
        
        if (isset($o['class'])) {
            $inst = $this->buildClass($o);
        }
        else {
            $inst = $this->buildFactory($o);
        }
        
        if (isset($o['calls'])) {
            $this->makeCalls($inst, $o['calls']);
        }
        
        if ($o['type'] === self::TYPE_SHARE) {
            $this->instances[$name] = $inst;
        }
        
        return $inst;
    }
    
    public function get($name)
    {
        if ($this->isParam($name)) {
            return $this->getParamExact($this->clearParamName($name));
        }
        else if($this->isService($name)) {
            return $this->getService($this->clearServiceName($name));
        }
        else {
            return $this->getParam($name);
        }
    }
    
    private function makeCalls($instance, $calls)
    {        
        foreach ($calls as $method => $args) {
            $refMethod = new ReflectionMethod($instance, $method);
            $refMethod->invokeArgs($instance, $this->prepareArgs($args));
        }
    }

    private function buildClass(array $o)
    {
        $refClass = new ReflectionClass($o['class']);
        $args = isset($o['args']) ? $this->prepareArgs($o['args']) : array();
        $instance = $refClass->newInstance($args);
        
        
        
        return $instance;
    }

    private function buildFactory(array $o)
    {
        if ($this->isService($o['factory'])) {
            $factory = $this->getService($o['factory']);
            $refMethod = new ReflectionMethod($factory, $o['method']);
            return $refMethod->invokeArgs($factory, $this->prepareArgs($o['args']));
        }
        
        $refMethod = new ReflectionMethod($o['factory'], $o['method']);
        return $refMethod->invokeArgs(null, $this->prepareArgs($o['args']));
    }

    private function prepareArgs(array $args)
    {
        $out = array();
        
        foreach ($args as $arg) {
            if ($this->isParam($arg)) {
                $out[] = $this->getParamExact($this->clearParamName($arg));
            }
            
            else if ($this->isService($arg)) {
                $out[] = $this->getService($this->clearServiceName($arg));
            }
            
            else {
                $out[] = $arg;
            }
        }
        
        return $out;
    }
    
    private function isService($arg)
    {
        return substr($arg, 0, 1) === '@';
    }
    
    private function isParam($arg)
    {
        return substr($arg, 0, 1) === '%' && substr($arg, -1) === '%';
    }
    
    private function clearServiceName($arg) {
        return substr($arg, 1, -1);
    }
    
    private function clearParamName($arg) {
        return substr($arg, 1, -1);
    }
}
