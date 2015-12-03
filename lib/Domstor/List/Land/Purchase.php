<?php

/**
 * Description of Purchase
 *
 * @author pahhan
 */
class Domstor_List_Land_Purchase extends Domstor_List_Demand
{
	public function __construct($attr)
	{
		parent::__construct($attr);
		$this->getField('type')->setTitle('Тип участка');

		$square_field = new Domstor_List_Field_SquareGroundDemand( array(
			'name'=>'square_ground',
			'title'=>'Площадь',
			'css_class'=>'domstor_square_ground',
			'position'=>101,
		) );

		$address_field = new Domstor_List_Field_AddressDemand( array(
			'name'=>'address',
			'title'=>'Местоположение',
			'css_class'=>'domstor_address',
			'position'=>230,
			'object_href'=>$this->object_href,
		) );

		$living_building_field = new HtmlTableField( array(
			'name'=>'living_building',
			'title'=>'Жилые постройки',
			'css_class'=>'living_building_type',
			'position'=>232,
		) );

		$this->addField($square_field)
			 ->addField($address_field)
			 ->addField($living_building_field)
		;

	}
}