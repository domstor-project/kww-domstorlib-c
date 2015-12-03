<?php

/**
 * Description of FlatType
 *
 * @author pahhan
 */
class Domstor_List_Field_FlatType extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a=$this->getTable()->getRow();
		$out=$this->getIf($a['type']);
		$out.=$this->getIf($a['planning'], ' (', ')');
		return $out;
	}
}

