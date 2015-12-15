<?php

/**
 * Description of Domstor_CacheUrlReader
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_CacheUrlReader implements Domstor_UrlReaderInterface
{
    /**
     *
     * @var Domstor_UrlReaderInterface
     */
    private $urlReader;
    
    /**
     *
     * @var Doctrine_Cache_Interface 
     */
    private $cacheDriver;
    
    /**
     *
     * @var integer 
     */
    private $cacheTime;
    
    /**
     *
     * @var string
     */
    private $uniqKey;


    public function __construct(Domstor_UrlReaderInterface $urlReader, Doctrine_Cache_Interface $cacheDriver, $cacheTime, $uniqKey = '')
    {
        $this->urlReader = $urlReader;
        $this->cacheDriver = $cacheDriver;
        $this->cacheTime = $cacheTime;
        $this->uniqKey = $uniqKey;
    }

    public function read($url)
    {
        $id = $this->generateCacheId($url);

        if( $this->cacheDriver->contains($id) ) {
            $content = $this->cacheDriver->fetch($id);
        }
        else {
            $content = $this->urlReader->read($url);
            $this->cacheDriver->save($id, $content, $this->cacheTime);
        }

        return $content;
    }

    private function generateCacheId($url)
    {
        return md5($this->uniqKey . $url);
    }
}
