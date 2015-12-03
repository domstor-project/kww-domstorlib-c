<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
interface Domstor_UrlReaderInterface
{
    /**
     * 
     * @param string $url
     * @return string Returns content from url
     */
    public function read($url);
}
