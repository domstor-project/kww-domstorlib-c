<?php

class Domstor_SortClient {

    const METHOD_GET = 100;
    const METHOD_POST = 101;
    const METHOD_REQUEST = 102;
    const TYPE_PREFIX = 200;
    const TYPE_ARRAY = 201;

    protected $_method = 100;
    protected $_type = 200;
    protected $_default = array();
    protected $_prefix = 'sort-';
    protected $_array_name = 's';
    protected $_asc_value = 'a';
    protected $_desc_value = 'd';

    public function setDefault(array $value) {
        $this->_default = $value;
        return $this;
    }

    public function setType($value) {
        $this->_type = $value;
        return $this;
    }

    public function setMethod($value) {
        $this->_method = $value;
        return $this;
    }

    public function setDesc($value) {
        $this->_desc_value = $value;
        return $this;
    }

    public function setAsc($value) {
        $this->_asc_value = $value;
        return $this;
    }

    protected function _getSource() {
        if ($this->_method == self::METHOD_GET) {
            $source = $_GET;
        } elseif ($this->_method == self::METHOD_POST) {
            $source = $_POST;
        } elseif ($this->_method == self::METHOD_REQUEST) {
            $source = $_REQUEST;
        }
        return $source;
    }

    protected function _fromSourcePrefix() {
        $source = $this->_getSource();
        $out = array();
        foreach ($source as $key => $value) {
            if (strpos($key, $this->_prefix) === 0) {
                $new_key = substr($key, strlen($this->_prefix));
                $out[$new_key] = $value;
            }
        }
        return $out;
    }

    protected function _fromSourceArray() {
        $source = $this->_getSource();
        $out = array();
        if (isset($source[$this->_array_name]) and is_array($source[$this->_array_name])) {
            $out = $source[$this->_array_name];
        }
        return $out;
    }

    protected function _fromSource() {
        if ($this->_type == self::TYPE_PREFIX)
            return $this->_fromSourcePrefix();
        if ($this->_type == self::TYPE_ARRAY)
            return $this->_fromSourceArray();
    }

    protected function _replaceValue($value) {
        if ($value == 'd' or $value == $this->_desc_value) {
            $value = $this->_desc_value;
        } else {
            $value = $this->_asc_value;
        }
        return $value;
    }

    protected function _methodPrefix($key, $value) {
        $out = '&' . $this->_prefix . $key . '=' . $this->_replaceValue($value);
        return $out;
    }

    protected function _methodArray($key, $value) {
        $out = '&' . $this->_array_name . '[' . $key . ']=' . $this->_replaceValue($value);
        return $out;
    }

    public function getArray() {
        return $this->_fromSource();
    }

    public function getArraySql() {
        $out = array();

        foreach ($this->getArray() as $key => $value) {
            if ($value == 'd' or $value == $this->_desc_value) {
                $out[$key] = 'DESC';
            } else {
                $out[$key] = 'ASC';
            }
        }

        return $out;
    }

    public function getRequestString(array $input = array()) {
        if (count($input)) {
            $data = $input;
        } else {
            $data = $this->_fromSource();
            if (!count($data))
                $data = $this->_default;
        }

        $out = '';
        foreach ($data as $key => $value) {
            if ($this->_type == self::TYPE_PREFIX)
                $out.= $this->_methodPrefix($key, $value);
            elseif ($this->_type == self::TYPE_ARRAY)
                $out.= $this->_methodArray($key, $value);
        }

        return $out;
    }

    public function replaceString($key, $string) {
        return str_replace($key, $this->getRequestString(), $string);
    }

}
