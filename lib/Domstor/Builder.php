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
            $oc = $o['cache'];
            $options = isset($oc['options'])? $oc['options'] : array();
            $domstor->setCacheDriver($this->buildCacheDriver($oc['type'], $options));
            $domstor->setCacheTime($oc['time']);
            if (isset($oc['uniq_key'])) {
                $domstor->setCacheUniqKey($oc['uniq_key']);
            }
        }
        
        if (isset($o['filter'])) {
            $of = $o['filter'];
            if (isset($of['template_dir'])) {
                $domstor->setFilterTmplDir($of['template_dir']);
            }
        }
        
        if (isset($o['href_templates'])) {
            foreach ($o['href_templates'] as $name => $value) {
                $domstor->setHrefTemplate($name, $value);
            }
        }
        
        if (isset($o['href_placeholders'])) {
            foreach ($o['href_placeholders'] as $name => $value) {
                $domstor->setHrefPlaceholder($name, $value);
            }
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
    protected function buildCacheDriver($type, array $options = array())
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
