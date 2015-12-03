<?php

/**
 * Description of Submin
 *
 * @author pahhan
 */
class SP_Form_Field_Submit extends SP_Form_AbstractField
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('submit');
		$this->isValuable(FALSE);
	}

	public function render()
	{
		return '<input type="submit" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$this->_renderClass().' value="'.$this->getLabel().'" />';
	}

}