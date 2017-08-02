<?php

/**
 * Description of RadioSet
 *
 * @author pahhan
 */
class SP_Form_Field_RadioSet extends SP_Form_AbstractField
{
    protected $_options = array();
    protected $_separator = ' ';
    protected $_label_first = true;

    public function setOptions(array $options)
    {
        $this->_options = $options;
        return $this;
    }

    public function setSeparator($separator)
    {
        $this->_separator = (string) $separator;
        return $this;
    }

    public function setLabelFirst($val)
    {
        $this->_label_first = (bool) $val;
        return $this;
    }

    public function render()
    {
        $id = $this->getId();
        $value = ($this->_value===null)? $this->_default : $this->_value;
        $out = '';
        foreach ($this->_options as $key => $option) {
            if ($this->_label_first) {
                $out.= $this->renderRadioLabel($key, $option);
                $out.= $this->renderRadioField($key);
                $out.= $this->_separator.PHP_EOL;
            } else {
                $out.= $this->renderRadioField($key);
                $out.= $this->renderRadioLabel($key, $option);
                $out.= $this->_separator.PHP_EOL;
            }
        }

        return  $out;
    }

    public function renderLabel()
    {
        return $this->_label;
    }

    public function renderRadioField($key)
    {
        $id = $this->getId().'_'.$key;

        $value = ($this->_value===null)? $this->_default : $this->_value;
        $check = ($value === (string)$key)? ' checked' : '';
        return '<input type="radio" name="'.$this->getFullName().'" id="'.$id.'"'.$check.' value="'.$key.'" />';
    }

    public function renderRadioLabel($key, $option)
    {
        $id = $this->getId().'_'.$key;
        return '<label for="'.$id.'">'.$option.'</label>';
    }

    public function displayRadioField($key)
    {
        echo $this->renderRadioField($key);
    }

    public function displayRadioLabel($key, $option)
    {
        echo $this->renderRadioLabel($key, $option);
    }
}
