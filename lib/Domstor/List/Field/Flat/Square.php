<?php

/**
 * Description of Square
 *
 * @author pahhan
 */
class Domstor_List_Field_Flat_Square extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a = $this->getRow();
        $out = '';
		if( $a['square_house'] or $a['square_living'] or $a['square_kitchen'] )
		{
			$house = $a['square_house']? $a['square_house'] : '-';
			$living = $a['square_living']? $a['square_living'] : '-';
			$kitchen = $a['square_kitchen']? $a['square_kitchen'] : '-';
			$out = $house.'/'.$living.'/'.$kitchen;
			$out = str_replace('.', ',', $out);
		}
		return $out;
	}
}

