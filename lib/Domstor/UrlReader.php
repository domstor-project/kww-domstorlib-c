<?php

/**
 * Description of Domstor_UrlReader
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_UrlReader implements Domstor_UrlReaderInterface
{
    public function read($url)
    {
        $content = @file_get_contents($url);
        if( $content === false ) {
            throw new RuntimeException('Can\'t read content from ' . $url);
        }
        
        return $content;
    }
}
