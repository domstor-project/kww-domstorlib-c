<?php

/**
 * Description of Purchase
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_List_Flat_Purchase extends Domstor_List_Demand
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$room_count_field = new Domstor_List_Field_Flat_DemandRooms( array(
				'name'=>'room_count',
				'title'=>'Число комнат',
				'css_class'=>'domstor_room_count',
				'sort_name'=>'sort-rooms',
				'position'=>1,
		));

		$floor_field = new Domstor_List_Field_Flat_DemandFloor( array(
				'name'=>'floor',
				'title'=>'Этаж',
				'css_class'=>'domstor_floor',
				'position'=>201,
		));

		$building_material_field = new HtmlTableField( array(
			'name'=>'building_material',
			'title'=>'Материал строения',
			'css_class'=>'domstor_building_material',
			'position'=>101,
		) );

		$phone_field = new HtmlTableField( array(
			'name'=>'phone_want',
			'title'=>'Телефон',
			'css_class'=>'domstor_phone',
			'position'=>236,
		) );

		$this->addField($room_count_field)
			 ->addField($floor_field)
			 ->addField($building_material_field)
			 ->addField($phone_field)
			 ->getField('type')->removeSortName();
		;
	}
}