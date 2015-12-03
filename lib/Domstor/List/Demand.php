<?php

/**
 * Description of Demand
 *
 * @author pahhan
 */
class Domstor_List_Demand extends Domstor_List_Common
{
	public function __construct($attr)
	{
		parent::__construct($attr);
		$price_field = new Domstor_List_Field_DemandPrice( array(
				'name'=>'price',
				'css_class'=>'domstor_price',
				'title'=>'Бюджет',
				'action'=>$this->action,
				'sort_name'=>'sort-price',
				'position'=>260,
		));
        $this->addField($price_field);

        $district_field = new HtmlTableField( array(
				'name'=>'district',
				'title'=>'Район',
				'css_class'=>'domstor_district',
				'position'=>200,
				'sort_name'=>'sort-district',
		));
		$this->addField($district_field);

		if( $this->action=='rent' )$this->getField('price')->setSortName('sort-rent');
	}
}