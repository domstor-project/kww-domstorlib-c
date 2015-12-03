<?php

/**
 * Description of AbstractField
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
abstract class SP_Form_AbstractField implements SP_Form_FieldInterface {

    const METHOD_GET = 100;
    const METHOD_POST = 101;

    protected $_method = 100;
    protected $_name;
    protected $_label;
    protected $_form;
    protected $_value;
    protected $_is_valuable = true;
    protected $_default;
    protected $_classes = array();
    protected $_transformer;
    protected $_empty_value = '';
    protected $_is_hidden = FALSE;

    public function __construct() {
        return $this;
    }

    public function isHidden($val = NULL) {
        if (is_null($val))
            return $this->_is_hidden;
        $this->_is_hidden = (bool) $val;
        return $this;
    }

    public function setIsHidden($val) {
        return $this->isHidden($val);
    }

    public function count() {
        return 0;
    }

    public function setEmptyValue($value) {
        $this->_empty_value = $value;
        return $this;
    }

    public function isEmptyValue() {
        $empty_vals = (array) $this->_empty_value;

        foreach ($empty_vals as $empty) {
            if ($this->_value === $empty or is_null($this->_value))
                return TRUE;
        }

        return FALSE;
    }

    public function setName($value) {
        $this->_name = $value;
        return $this;
    }

    public function getName() {
        return $this->_name;
    }

    public function setMethod($value) {
        $this->_method = $value;
        return $this;
    }

    public function getMethod() {
        $method = is_null($this->_form) ? $this->_method : $this->_form_->getMethod();
        return $method;
    }

    public function getMethodName() {
        $method = $this->getMethod();
        if ($method == self::METHOD_GET) {
            return 'GET';
        } elseif ($method == self::METHOD_POST) {
            return 'POST';
        }
        throw new Exception('Unknown method');
    }

    public function setLabel($value) {
        $this->_label = $value;
        return $this;
    }

    public function getLabel() {
        return $this->_label;
    }

    public function getFullName() {
        if (isset($this->_form)) {
            $name = $this->_form->getFullName() . '[' . $this->getName() . ']';
        } else {
            $name = $this->getName();
        }

        return $name;
    }

    public function getId() {
        $form_name = isset($this->_form) ? $this->_form->getId() . '_' : '';
        return $form_name . $this->getName();
    }

    public function setDefault($value) {
        $this->_default = $value;
        return $this;
    }

    public function getDefault() {
        return $this->_default;
    }

    public function isValuable($value = NULL) {
        if (is_null($value)) {
            return $this->_is_valuable;
        } else {
            $this->_is_valuable = (bool) $value;
            return $this;
        }
    }

    public function getThisOrTrans() {
        
    }

    public function getValue() {
        return $this->_value;
    }

    public function getRequestString() {
        return '&' . $this->getFullName() . '=' . $this->getValue();
    }

    public function getServerRequestString() {
        return $this->getRequestString();
    }

    public function setTransformer($trans) {
        $trans->setField($this);
        $this->_transformer = $trans;
        return $this;
    }

    public function getTransformedValue() {
        $value = $this->getValue();

        if (!is_null($this->_transformer)) {
            //var_dump($value);
            $value = $this->_transformer->transform($value);
        }
        return $value;
    }

    public function setForm(SP_Form_FormInterface $value) {
        $this->_form = $value;
        return $this;
    }

    public function renderLabel() {
        $out = '';
        if ($label = $this->getLabel()) {
            $input_id = $this->getId();
            $id = 'label_' . $input_id;
            $out = '<label for="' . $input_id . '" id="' . $id . '">' . $this->getLabel() . '</label>';
        }
        return $out;
    }

    /*public function displayLabel() {
        echo $this->renderLabel();
    }*/

    public function display() {
        echo $this->render();
        return $this;
    }

    public function __toString() {
        try {
            return $this->render();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function bind(array $values) {
        $name = $this->getName();
        if (isset($values[$name]))
            $this->_value = $values[$name];
        return $this;
    }

    public function getRequestArray() {
        if (is_null($this->_form)) {
            $method = $this->getMethod();
            if ($method == self::METHOD_GET) {
                $array = $_GET;
            }
            if ($method == self::METHOD_POST) {
                $array = $_POST;
            }
        } else {
            $array = $this->_form->getRequestArray();
            //var_dump($array);
        }
        if (is_null($array))
            $array = array();
        return $array;
    }

    public function bindFromRequest() {
        $array = $this->getRequestArray();
        if (!is_array($array))
            $array = array();
        $this->bind($array);
        return $this;
    }

    protected function _renderClass() {
        if (count($this->_classes) > 0) {
            $classes = implode(' ', $this->_classes);
            $out = ' class="' . $classes . '"';
        } else {
            $out = '';
        }
        return $out;
    }

}
