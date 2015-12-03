<?php

/**
 * Description of DemandPrice
 *
 * @author pahhan
 */
class Domstor_List_Field_DemandPrice extends Domstor_List_Field_Common
{
	protected $action;

	public function getValue()
	{
		$a = $this->table->getRow();
        $out = '';
		if( $this->action=='rentuse' )
		{
			$out=$this->getPriceFromTo($a['rent_full_min'], $a['rent_full_max'], $a['rent_currency'], $a['rent_period']);
		}
		else
		{
			$out=$this->getPriceFromTo($a['price_full_min'], $a['price_full_max'], $a['price_currency']);
		}
		return $out;
	}

}

