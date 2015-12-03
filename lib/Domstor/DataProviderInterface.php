<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
interface Domstor_DataProviderInterface
{
    /**
     * 
     * @param string $url
     * @return array
     */
    public function getData($url);
}
