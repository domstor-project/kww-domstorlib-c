<?php

/**
 * Description of Square
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_Square extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a=$this->getRow();

		$out=$this->getFromTo(str_replace('.', ',', $a['square_house_min']), str_replace('.', ',', $a['square_house_max']), ' кв.м', '', true);
		return $out;
	}
}

