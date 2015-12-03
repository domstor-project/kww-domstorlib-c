<?php

/**
 * Description of Floor
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_Floor extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a = $this->getRow();
		$min = $a['object_floor_min'];
		$max = $a['object_floor_max'];
		$min_flag = FALSE;
		$max_flag = FALSE;
		$out = '';

		if( isset($min) and $min != '' ) $min_flag = TRUE;
		if( isset($max) and $max != '' ) $max_flag = TRUE;

		if( $min_flag and $max_flag )
		{
			if( $min == $max )
			{
				$out = ($min == '0')? 'цоколь' : $min;
			}
			else
			{
				$out = 'от&nbsp;'.$min.' до&nbsp;'.$max;
				$out = str_replace('0', 'цоколя', $out);
			}
		}
		elseif( $min_flag or $max_flag )
		{
			if( $min_flag )
			{
				$out = 'от&nbsp;'.$min;
			}
			else
			{
				$out = 'до&nbsp;'.$max;
			}
			$out = str_replace('0', 'цоколя', $out);
		}

		return $out;
	}
}
