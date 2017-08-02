<?php

/**
 * Description of DataProvider
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_JsonDataProvider implements Domstor_DataProviderInterface
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
        return $this->contentToData($content);
    }

    protected function contentToData($content)
    {
        $data = json_decode($content);
        if ($data === null && $error = json_last_error()) {
            throw new Domstor_JsonException($error);
        }
        
        return $data;
    }
}
