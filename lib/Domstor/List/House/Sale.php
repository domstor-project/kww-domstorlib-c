<?php

/**
 * Description of Sale
 *
 * @author pahhan
 */
class Domstor_List_House_Sale extends Domstor_List_Supply
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$room_count_field = new HtmlTableField( array(
			'name'=>'room_count',
			'title'=>'Число комнат',
			'css_class'=>'domstor_room_count',
			'dont_show_if'=>'0',
			'position'=>101,
			'sort_name'=>'sort-rooms',
		) );

		$floor_field = new HtmlTableField( array(
			'name'=>'building_floor',
			'title'=>'Этажей',
			'css_class'=>'domstor_floor',
			'dont_show_if'=>'0',
			'position'=>102,
			'sort_name'=>'sort-floor',
		) );

		$square_field = new Domstor_List_Field_Flat_Square( array(
			'name'=>'square',
			'title'=>'Площадь, кв.м<br />общ./жил./кух.',
			'css_class'=>'domstor_square',
			'sort_name'=>'sort-square',
			'position'=>234,
		) );

		$square_ground_field = new Domstor_List_Field_SquareGround( array(
			'name'=>'square_round',
			'title'=>'Площадь участка',
			'css_class'=>'domstor_square_round',
			'sort_name'=>'sort-ground',
			'position'=>236,
		) );

		$other_building_field = new HtmlTableField( array(
			'name'=>'other_building',
			'title'=>'Постройки',
			'css_class'=>'domstor_other_building',
			'position'=>238,
		) );

		$this->addField($room_count_field)
			 ->addField($floor_field)
			 ->addField($square_field)
			 ->addField($square_ground_field)
			 ->addField($other_building_field)
		;

	}
}