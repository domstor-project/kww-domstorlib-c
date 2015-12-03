<?php

/**
 * Description of Price
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_Price extends Domstor_List_Field_Common
{
	protected $action;

	public function __construct($attr)
	{
		parent::__construct($attr);
		if( $this->action=='rent' )
		{
			$this->title='Арендная ставка';
		}
		else
		{
			$this->title='Цена';
		}
	}

	public function getValue()
	{
		$a = $this->table->getRow();
        $out = '';
		$price_ground_unit = (isset($a['price_m2_unit']) and $a['price_m2_unit'])? $a['price_m2_unit'] : 'кв.м';

		$a['rent_m2_min'] = (float) $a['rent_m2_min'];
		$a['rent_m2_max'] = (float) $a['rent_m2_max'];
		$a['rent_full'] = (float) $a['rent_full'];

		$a['price_m2_min'] = (float) $a['price_m2_min'];
		$a['price_m2_max'] = (float) $a['price_m2_max'];
		$a['price_full'] = (float) $a['price_full'];

		if( $this->action == 'rent' )
		{
			if( $a['offer_parts'] and ($a['rent_m2_min'] or $a['rent_m2_max']) )
			{
				$out=$this->getIf($this->getPriceFromTo($a['rent_m2_min'], $a['rent_m2_max'], $a['rent_currency']), '', '/ '.$price_ground_unit.' '.$a['rent_period'] );
			}
			elseif( $a['rent_full'] )
			{
				$out=number_format($a['rent_full'], 0, ',', ' ');
				$out.=$this->getIf($a['rent_currency'], ' ');
				$out.=$this->getIf($a['rent_period'], ' ');
				$out=str_replace(' ', '&nbsp;', $out);
			}
		}
		else
		{
			if( $a['offer_parts'] and ($a['price_m2_min'] or $a['price_m2_max']) )
			{
				$out=$this->getIf($this->getPriceFromTo($a['price_m2_min'], $a['price_m2_max'], $a['price_currency']), '', '/ '.$price_ground_unit );
			}
			elseif( $a['price_full'] )
			{
				$out=number_format($a['price_full'], 0, ',', ' ');
				$out.=$this->getIf($a['price_currency'], ' ');
				$out=str_replace(' ', '&nbsp;', $out);
			}
		}

		return $out;
	}
}