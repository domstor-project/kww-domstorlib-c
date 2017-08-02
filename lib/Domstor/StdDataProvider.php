<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StdDataProvider
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_StdDataProvider implements Domstor_DataProviderInterface
{
    /**
     *
     * @var Domstor_UrlReaderInterface
     */
    private $urlReader;

    public function __construct(Domstor_UrlReaderInterface $urlReader)
    {
        $this->urlReader = $urlReader;
    }

    public function getData($url)
    {
        $content = $this->urlReader->read($url);
        $data = base64_decode($content);
        if ($data !== false) {
            $data = (array) unserialize($data);
            array_walk_recursive($data, array($this, 'convert'));
        }
        
        return $data;
    }

    public function convert(&$val, $key)
    {
        $val = iconv('windows-1251', 'utf-8', $val);
    }
}
