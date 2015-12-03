<?php

class Domstor_LocationsList {

    protected $_data = array();
    protected $_href_tmpl = '';
    protected $_home_id;
    protected $_prefix = 'Недвижимость ';

    public function __construct(array $data, $href_tmpl, $home_id) {
        $this->_data = $data;
        $this->_href_tmpl = $href_tmpl;
        $this->_home_id = $home_id;
    }

    public function getArray() {
        $out = array();
        foreach ($this->_data as $data) {
            $out[$data['id']]['name'] = $data['name'];
            $out[$data['id']]['uri_part'] = '&ref_city=' . $data['id'];
            if ($in_region = ($data['type'] == 2))
                $out[$data['id']]['uri_part'].= '&inreg';
            $out[$data['id']]['is_region'] = $in_region;
        }
        return $out;
    }

    public function getLinks($prefix = NULL) {
        $links = array();

        if (!$prefix) {
            $prefix = $this->_prefix;
        }

        foreach ($this->_data as $data) {
            if ($data['id'] == $this->_home_id) {
                $uri = str_replace('&ref_city=%id', '', $this->_href_tmpl);
            } else {
                $uri = str_replace('%id', $data['id'], $this->_href_tmpl);
            }
            array_push($links, array(
                'url' => $uri,
                'text' => $prefix . ' ' . $data['name'],
            ));
        }

        return $links;
    }

    public function render($prefix = NULL) {
        $links = $this->getLinks($prefix);
        $html = '';

        foreach ($links as $link) {
            $html .= sprintf('<p><a href="%s">%s</a></p>', $link['url'], $link['text']);
        }

        return $html;
    }

    public function display($prefix = NULL) {
        echo $this->render($prefix);
    }

    public function __toString() {
        return $this->render();
    }

}
