<?php

/**
 * Description of Radio
 *
 * @author pahhan
 */
class SP_Form_Field_Radio extends SP_Form_AbstractField
{
    public function render()
    {
        $value = ($this->_value===null)? $this->_default : $this->_value;
        $check = $value? ' checked' : '';
        return '<input type="radio" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$check.' value="'.$value.'" />';
    }
}
