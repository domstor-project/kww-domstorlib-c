<?php

/**
 * Description of SubmitLink
 *
 * @author pahhan
 */
class SP_Form_Field_SubmitLink extends SP_Form_AbstractField
{
    public function __construct()
    {
        parent::__construct();
        $this->setName('submit_link');
        $this->isValuable(false);
    }

    public function render()
    {
        return '<a href="" onClick="document.getElementById(\''.$this->_form->getId().'\').submit(); return false;">'.$this->getLabel().'</a> ';
    }
}
