<?php

/**
 * Description of CheckboxSet
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class SP_Form_Field_CheckboxSet extends SP_Form_AbstractField
{
    protected $_options = array();
    protected $_separator = ' ';
    protected $_label_first = false;
    protected $_option_outer_html = '<div class="domstor_checkbox_group">%s</div>';

    public function setOptions(array $options)
    {
        $this->_options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function setSeparator($separator)
    {
        $this->_separator = (string) $separator;
        return $this;
    }
    
    public function setOptionOuterHtml($param)
    {
    }

    public function setLabelFirst($val)
    {
        $this->_label_first = (bool) $val;
        return $this;
    }

    public function render()
    {
        if (!$this->count()) {
            return '';
        }
        $out = '';

        foreach ($this->_options as $key => $option) {
            if ($this->_label_first) {
                $option = $this->renderCheckboxLabel($key, $option) .
                    $this->renderCheckboxField($key, $option);
            } else {
                $option = $this->renderCheckboxField($key, $option) .
                    $this->renderCheckboxLabel($key, $option);
            }
            
            if ($this->_option_outer_html) {
                $option = sprintf($this->_option_outer_html, $option);
            }
            
            $out .=  $option . $this->_separator . PHP_EOL;
        }

        return $out;
    }

    public function renderLabel()
    {
        if (!$this->count()) {
            return '';
        }
        return $this->_label;
    }

    public function renderCheckboxField($key, $option)
    {
        $id = $this->getId() . '_' . $key;
        $name = $this->getFullName() . '[]';
        $value = ($this->_value === null) ? $this->_default : $this->_value;
        $value = (array) $value;
        $check = in_array($key, $value) ? ' checked' : '';
        return '<input type="checkbox" name="' . $name . '" id="' . $id . '"' . $check . ' value="' . $key . '" />';
    }

    public function renderCheckboxLabel($key, $option)
    {
        $id = $this->getId() . '_' . $key;
        return '<label for="' . $id . '">' . $option . '</label>';
    }

    public function displayCheckboxField($key, $option)
    {
        echo $this->renderCheckboxField($key, $option);
    }

    public function displayCheckboxLabel($key, $option)
    {
        echo $this->renderCheckboxLabel($key, $option);
    }

    public function getRequestString()
    {
        $values = $this->getValue();
        $out = '';
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                if (!empty($value)) {
                    $out.= '&' . $this->getFullName() . '[]=' . $value;
                }
            }
        }
        return $out;
    }
    
    public function count()
    {
        return count($this->_options);
    }
}
