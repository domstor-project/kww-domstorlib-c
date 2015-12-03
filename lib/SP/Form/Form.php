<?php

/**
 * Description of Form
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class SP_Form_Form extends SP_Form_AbstractField implements SP_Form_FormInterface, Iterator, Countable {

    protected $_fields = array();
    protected $_action = '';
    protected $_render_template;
    // Begin Iterator interface
    protected $_current_field;

    public function rewind() {

        $this->_current_field = 0;
    }

    public function current() {

        $key = $this->key();
        return $this->_fields[$key];
    }

    public function key() {

        $keys = array_keys($this->_fields);
        $key = $keys[$this->_current_field];
        return $key;
    }

    public function next() {

        $this->_current_field += 1;
    }

    public function valid() {
        $keys = array_keys($this->_fields);
        return isset($keys[$this->_current_field]);
    }

    // End Iterarot interface
    // Begin Countable interface
    public function count() {

        return count($this->_fields);
    }

    // End Countable interface


    public function __construct() {
        $this->_current_field = 0;
        return $this;
    }

    public function setAction($value) {
        $this->_action = $value;
        return $this;
    }

    public function getAction() {
        return $this->_action;
    }

    public function setDefault($values) {
        if (!is_array($values)) {
            throw new InvalidArgumentException(sprintf('$values must be an array, %s given', gettype($values)));
        }
        foreach ($this->_fields as $field) {
            $field->setDefault($values[$field->getName()]);
        }
        return $this;
    }

    public function getDefault() {
        $values = array();
        foreach ($this->_fields as $field) {
            $values[$field->getName()] = $field->getDefault();
        }
        return $values;
    }

    public function getValue() {
        $values = array();
        foreach ($this->_fields as $field) {
            if ($field->isValuable())
                $values[$field->getName()] = $field->getValue();
        }
        return $values;
    }

    public function getRequestString() {
        $out = '';

        foreach ($this->_fields as $field) {
            if ($field->isValuable()) {
                if ($field->count()) {
                    $out.= $field->getRequestString();
                } elseif (!$field->isEmptyValue()) {
                    $out.= '&' . $field->getFullName() . '=' . $field->getValue();
                }
            }
        }
        return $out;
    }

    public function getServerRequestString() {
        $out = '';

        foreach ($this->_fields as $field) {
            if ($field->isValuable()) {
                if ($field->count()) {
                    $out.= $field->getServerRequestString();
                } elseif (!$field->isEmptyValue()) {
                    $out.= '&' . $field->getFullName() . '=' . $field->getValue();
                }
            }
        }
        return $out;
    }

    public function replaceString($key, $string) {
        return str_replace($key, $this->getRequestString(), $string);
    }

    public function setForm(SP_Form_FormInterface $value) {
        $this->_form = $value;
        return $this;
    }

    public function setRenderTemplate($path) {
        $this->_render_template = $path;
        return $this;
    }

    public function renderTemplate() {
        ob_start();
        @include($this->_render_template);
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }

    public function render() {
        if (!isset($this->_render_template)) {
            throw new Exception('Render method must be redefined in extended class');
        }
        
        $out = $this->renderTemplate();
        
        return $out;
    }

    public function renderLabel() {
        $out = '';
        if ($label = $this->getLabel()) {
            $input_id = $this->getId();
            $id = 'label_' . $input_id;
            $out = $this->getLabel();
        }
        return $out;
    }

    public function renderOpenTag() {
        return '<form action="' . $this->getAction() . '" method="' . $this->getMethodName() . '" id="' . $this->getId() . '"' . $this->_renderClass() . '>' . "\r\n";
    }

    public function renderCloseTag() {
        return '</form>';
    }

    public function displayOpenTag() {
        echo $this->renderOpenTag();
        return $this;
    }

    public function displayCloseTag() {
        echo $this->renderCloseTag();
        return $this;
    }

    public function displayLabel($name) {
        echo $this->getField($name)->renderLabel();
    }

    public function displayField($name) {
        echo $this->getField($name)->render();
    }

    public function bind(array $values) {
        foreach ($this->_fields as $field) {
            $field->bind($values);
        }
    }

    public function bindFromRequest() {
        foreach ($this->_fields as $field) {
            $field->bindFromRequest();
        }
    }

    public function getRequestArray() {
        $name = $this->getName();
        if (is_null($this->_form)) {
            $method = $this->getMethod();
            if ($method == self::METHOD_GET) {
                $array = $_GET;
            } elseif ($method == self::METHOD_POST) {
                $array = $_POST;
            }
        } else {
            $array = $this->_form->getRequestArray();
        }
        if (isset($array[$name])) {
            return $array[$name];
        }
    }

    public function addField(SP_Form_FieldInterface $field, $name = NULL) {
        $field->setForm($this);
        if (is_null($name))
            $name = $field->getName();
        $this->_fields[$name] = $field;
        return $this;
    }

    public function addFields(array $fields) {
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
    }

    public function hasField($name) {
        return array_key_exists($name, $this->_fields);
    }

    public function getField($name) {
        if ($this->hasField($name))
            return $this->_fields[$name];
        throw new Exception('Form "' . $this->getName() . '" do not contain "' . $name . '" field');
    }

    public function deleteField($name) {
        unset($this->_fields[$name]);
    }

}
