<?php

/**
 * Description of SquareGround
 *
 * @author pahhan
 */
class Domstor_List_Field_SquareGround extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
        $out = '';
		if( isset($a['square_ground']) and $a['square_ground'] )
		{
			$out=$a['square_ground'].' '.strtolower($a['square_ground_unit']);
		}
		elseif( isset($a['square_ground_m2']) and $a['square_ground_m2'] )
		{
			if( $a['square_ground_m2'] )$out=$a['square_ground_m2'].'&nbsp;кв.м.';
		}
		return $out;
	}
}