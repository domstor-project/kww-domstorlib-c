<?php

/**
 * Description of Checkbox
 *
 * @author pahhan
 */
class SP_Form_Field_Checkbox extends SP_Form_AbstractField
{

	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$check = $value? ' checked' : '';
		return '<input type="checkbox" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$check.' value="1" />';
	}

}