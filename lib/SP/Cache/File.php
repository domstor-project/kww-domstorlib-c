<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of file
 *
 * @author pahhan
 */
class SP_Cache_File extends Doctrine_Cache_Driver
{
    protected $_expire_array;
    protected $_expire_file_name = '_expires.txt';

    /**
     * Configure File cache driver. Specify directory to store cache in
     *
     * @param array $_options      an array of options
     */
    public function __construct($options = array())
    {

        if ( !isset($options['directory']) ) throw new Doctrine_Cache_Exception('Directory not defined');
        $options['directory'] = realpath($options['directory']);
        if ( !is_dir($options['directory']) ) throw new Doctrine_Cache_Exception($options['directory'].' is not a directory');
        if ( !is_writable($options['directory']) ) throw new Doctrine_Cache_Exception($options['directory'].' directory is not writable');

        $this->_options['directory'] = rtrim($options['directory'], '/').'/';
    }

    /**
     * Get the directory of cache files
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->_options['directory'];
    }

    public function getFile($id)
    {
        return $this->getDirectory().$this->_encodeId($id);
    }

    public function getExpireFile()
    {
        return $this->getDirectory().$this->_expire_file_name;
    }

    protected function _parseExpireArray(array $array)
    {
        $out = array();
        foreach($array as $row)
        {
            $row_array = explode('|', $row);
            $out[$row_array[0]] = rtrim($row_array[1], PHP_EOL);
        }
        return $out;
    }

    protected function _writeExpireArray(array $array)
    {
        $handle = fopen($this->getExpireFile(), 'w');
        foreach($array as $key => $value)
        {
            fwrite($handle, $key.'|'.$value.PHP_EOL);
        }
        fclose($handle);
    }

    public function getExpireArray($force = false)
    {
        if( is_null($this->_expire_array) || $force )
        {
            $file = $this->getExpireFile();
            if( !file_exists($file) )
            {
                $handle = fopen($file, 'x+');
                fwrite($handle, '');
                fclose($handle);
            }
            $array = file($this->getDirectory().$this->_expire_file_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if( !is_array($array) ) return false;
            $this->_expire_array = $this->_parseExpireArray($array);
        }

        return $this->_expire_array;
    }

    public function getExpire($id, $force = false)
    {
        $key = $this->_encodeId($id);

        if( !($this->getExpireArray($force) and isset($this->_expire_array[$key])) ) return false;

        return $this->_expire_array[$key];
    }

    public function setExpire($id, $time = false)
    {
        $this->getExpireArray();
        $key = $this->_encodeId($id);

        if( $time )
            $this->_expire_array[$key] = $time;
        else
            $this->_expire_array[$key] = 'null';

        $this->_writeExpireArray($this->_expire_array);
    }

    /**
     * Test if a cache file exists for the passed id
     *
     * @param string $id cache id
     * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    protected function _doContains($id)
    {
        return is_readable($this->getFile($id));
    }

    /**
     * Fetch a cache record from this cache driver instance
     *
     * @param string $id cache id
     * @param boolean $testCacheValidity        if set to false, the cache validity won't be tested
     * @return mixed  Returns either the cached data or false
     */
    protected function _doFetch($id, $testCacheValidity = true)
    {
        if( !$this->_doContains($id) ) return false;

        if ($testCacheValidity)
        {
            $expire = $this->getExpire($id);
            if( $expire and $expire !== 'null' )
            {
                if( $expire < time() ) return false;
            }
        }

        $content = file_get_contents($this->getFile($id));

        return unserialize($this->_hex2bin($content));

    }

    /**
     * Save a cache record directly. This method is implemented by the cache
     * drivers and used in Doctrine_Cache_Driver::save()
     *
     * @param string $id        cache id
     * @param string $data      data to cache
     * @param int $lifeTime     if != false, set a specific lifetime for this cache record (null => infinite lifeTime)
     * @return boolean true if no problem
     */
    protected function _doSave($id, $data, $lifeTime = false, $saveKey = true)
    {
        $file = $this->getFile($id);

        if( is_file($file) )
        {
            if( !is_writable($file) ) throw new Doctrine_Cache_Exception('Can not overwrite cache file '.$file);
        }

        $handle = fopen($this->getFile($id), 'w');
        fwrite($handle, bin2hex(serialize($data)));

        if( $lifeTime )
            $this->setExpire($id, time() + $lifeTime);
        else
            $this->setExpire($id, false);


        true;
    }

    /**
     * Remove a cache record directly. This method is implemented by the cache
     * drivers and used in Doctrine_Cache_Driver::delete()
     *
     * @param string $id cache id
     * @return boolean true if no problem
     */
    protected function _doDelete($id)
    {

        $file = $this->getFile($id);
        if( is_writable($file) ) unlink($file);


        $this->getExpireArray();
        $key = $this->_encodeId($id);
        if( isset($this->_expire_array[$key]) )
        {
            unset($this->_expire_array[$key]);
            $this->_writeExpireArray($this->_expire_array);
        }
    }

    /**
     * Convert hex data to binary data. If passed data is not hex then
     * it is returned as is.
     *
     * @param string $hex
     * @return string $binary
     */
    protected function _hex2bin($hex)
    {
        if ( ! is_string($hex)) {
            return null;
        }

        if ( ! ctype_xdigit($hex)) {
            return $hex;
        }

        return pack("H*", $hex);
    }

    /**
     * Fetch an array of all keys stored in cache
     *
     * @return array Returns the array of cache keys
     */
    protected function _getCacheKeys()
    {
        $keys = array();
        $this->getExpireArray(true);

        foreach( $this->_expire_array as $key => $v)
        {
            $keys[] = $this->_decodeId($key);
        }
        return $keys;
    }

    protected function _encodeId($id)
    {
        return bin2hex(serialize($id));
    }

    protected function _decodeId($id)
    {
        return unserialize($this->_hex2bin($id));
    }
}