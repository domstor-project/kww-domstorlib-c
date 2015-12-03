<?php

/**
 * Description of Comment
 *
 * @author pahhan
 */
class Domstor_List_Field_Comment extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = str_replace(',', ', ', $a['note_web']);
		return $out;
	}
}



