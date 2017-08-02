<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Select
 *
 * @author pahhan
 */
class SP_Form_Field_Select extends SP_Form_AbstractField
{
    protected $_options = array();
    protected $_multiple = false;
    protected $_size;

    public function setOptions(array $options)
    {
        $this->_options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function addOptions(array $array)
    {
        return $this->_options = $this->_options + $array;
    }

    public function setRange($from, $to = null, $first = null)
    {
        if (is_null($to)) {
            $to = $from;
            $from = 0;
        }

        $options = array();
        if (is_array($first)) {
            $options[key($first)]=current($first);
        }

        for ($i = $from; $i <= $to; $i++) {
            $options[$i] = $i;
        }

        $this->setOptions($options);

        return $this;
    }

    public function setMultiple($multiple)
    {
        $this->_multiple = (bool) $multiple;
        return $this;
    }

    public function setSize($size)
    {
        $this->_size = (integer) $size;
        return $this;
    }

    public function render()
    {
        $value = ($this->_value===null)? $this->_default : $this->_value;
        $value = (array) $value;
        $name = $this->getFullName();
        $multiple = '';

        if ($this->_multiple) {
            $name.= '[]';
            $multiple = ' multiple';
        }

        $size = $this->_size? ' size="'.$this->_size.'"' : '';

        $out = '<select name="'.$name.'"'.$multiple.$size.' id="'.$this->getId().'">'.PHP_EOL;

        foreach ($this->_options as $key => $option) {
            $selected = in_array($key, $value)? ' selected' : '';
            $out.= '<option value="'.$key.'"'.$selected.'>'.$option.'</option>'.PHP_EOL;
        }
        $out.= '</select>';

        return $out;
    }
}
