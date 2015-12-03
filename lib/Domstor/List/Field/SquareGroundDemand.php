<?php

/**
 * Description of SquareGroundDemand
 *
 * @author pahhan
 */
class Domstor_List_Field_SquareGroundDemand extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = $this->getFromTo($a['square_ground_min'], $a['square_ground_max'], '&nbsp;'.$a['square_ground_unit']);
		return $out;
	}
}