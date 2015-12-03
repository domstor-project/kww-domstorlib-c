<?php

/**
 * Description of InputText
 *
 * @author pahhan
 */
class SP_Form_Field_InputText extends SP_Form_AbstractField
{
	protected $_is_password = FALSE;
	protected $_is_xhtml = TRUE;

	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$type = ($this->_is_hidden)? 'hidden' : (($this->_is_password)? 'password' : 'text');
		return '<input type="'.$type.'" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$this->_renderClass().' value="'.$value.'" />';
	}
}