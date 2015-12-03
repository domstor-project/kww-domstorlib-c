<?php

/**
 * Description of SiteMapGenerator
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_SiteMapGenerator
{
    /**
     * XMLWriter
     * @var XMLWriter
     */
    protected $_xml_writer;

    /**
     * Cache time in seconds
     * @var integer
     */
    protected $_cache_time = 86400;

    /**
     * Cache driver for xml caching
     * @var SP_Cache_Interface
     */
    protected $_cache_driver;

    /**
     * Site hostname
     * @var string
     */
    protected $_host = '';

    /**
     * Data for map generation
     * @var array
     */
    protected $_data;

    /**
     * Link priority in sitemap
     * @var float
     */
    protected $_priority = 0.8;

    /**
     * Sitemap update period
     * @var string
     */
    protected $_period = 'weekly';

    /**
     * For generating cache key
     * @var string
     */
    protected $_request_url;
    protected $_object_href;

    function __construct($_object_href) {
        $this->_object_href = $_object_href;
    }

    /**
     *
     * @param array $data
     */
    public function setData(array $data) {
        $this->_data = $data;
        return $this;
    }

    public function setHost($host) {
        $this->_host = $host;
        return $this;
    }

    public function setXmlWriter($xml_writer) {
        $this->_xml_writer = $xml_writer;
        return $this;
    }

    public function getXmlWriter() {
        if (is_null($this->_xml_writer))
            $this->_xml_writer = new XMLWriter;
        return $this->_xml_writer;
    }

    public function setCacheTime($cache_time) {
        $this->_cache_time = $cache_time;
        return $this;
    }

    public function setCacheDriver($cache_driver) {
        $this->_cache_driver = $cache_driver;
    }

    public function setPriority($priority) {
        $this->_priority = $priority;
        return $this;
    }

    public function setPeriod($period) {
        $this->_period = $period;
        return $this;
    }

    public function setRequestUrl($request_url) {
        $this->_request_url = $request_url;
        return $this;
    }

    /**
     * Returns hashed _request_url for cache key
     * @return string
     */
    protected function _getCacheKey() {
        return md5($this->_request_url);
    }

    /**
     * Creates cache driver
     * @param srting $type
     * @param array $params
     * @return Doctrine_Cache_Interface
     * @throws InvalidArgumentException
     */
    public function createCacheDriver($type, array $options) {
        if ($type === 'file')
            $this->_cache_driver = new SP_Cache_File($options);
        else
            throw new InvalidArgumentException('Unavailable cache driver type "' . $type . '"');

        return $this->_cache_driver;
    }

    public function generate() {
        $xml_content = $this->_cache_driver->fetch($this->_getCacheKey());

        if (!$xml_content) {
            $url = $this->_object_href;
            $xml = $this->getXmlWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElementNs(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');

            foreach ($this->_data as $row) {
                $full_url = $this->_host . str_replace('%id', $row->id, $url);

                $this->_genereteElement($xml, $row, $full_url);
            }


            $xml->endElement();
            $xml->endDocument();

            $xml_content = $xml->outputMemory();
            $this->_cache_driver->save($this->_getCacheKey(), $xml_content, $this->_cache_time);
        }

        header("Content-type: text/xml");
        echo $xml_content;
    }

    protected function _genereteElement(XMLWriter $xml, &$row, &$url) {
        $xml->startElement('url');
        $xml->writeElement('loc', $url);
        $lastmod = isset($row->edit_dt) ? date('Y-m-d', strtotime($row->edit_dt)) : date('Y-m-d');
        $xml->writeElement('lastmod', $lastmod);
        $xml->writeElement('changefreq', $this->_period);
        $xml->writeElement('priority', $this->_priority);
        $xml->endElement();
    }

}
