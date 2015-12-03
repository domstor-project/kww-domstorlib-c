<?php

/**
 * Description of Builder
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Builder
{
    /**
     * 
     * @param array $o Build options
     * @return Domstor_Domstor
     */
    public function build(array $o)
    {
        $domstor = new Domstor_Domstor($o['org_id'], $o['location_id']);
        if (isset($o['cache'])) {
            $options = isset($o['cache']['options'])? $o['cache']['options'] : array();
            $domstor->setCacheDriver($this->buildCacheDreiver($o['cache']['type'], $options));
            $domstor->setCacheTime($o['cache']['time']);
        }
        
        return $domstor;
    }
    
    /**
     * 
     * @param string $type
     * @param array $options
     * @return Doctrine_Cache_Driver
     * @throws Exception
     */
    private function buildCacheDreiver($type, array $options = array())
    {
        switch ($type) {
            case 'array':
                return new Doctrine_Cache_Array($options);
            case 'apc':
                return new Doctrine_Cache_Apc($options);
            case 'memcache':
                return new Doctrine_Cache_Memcache($options);
            case 'xcache':
                return new Doctrine_Cache_Xcache($options);
            case 'file':
                return new SP_Cache_File($options);
            default:
                throw new Exception('Unsupported cache type ' . $type);
        }
    }
}
