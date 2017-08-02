<?php

/**
 * Description of Sale
 *
 * @author pahhan
 */
class Domstor_List_Flat_Sale extends Domstor_List_Supply
{
    public function __construct($attr)
    {
        parent::__construct($attr);
        $this->getField('type')->setName('flat_type');
        $room_count_field = new HtmlTableField(array(
            'name'=>'room_count',
            'title'=>'Число комнат',
            'css_class'=>'domstor_room_count',
            'dont_show_if'=>'0',
            'position'=>50,
            'sort_name'=>'sort-rooms',
        ));

        $building_material_field = new HtmlTableField(array(
            'name'=>'building_material',
            'title'=>'Материал строения',
            'css_class'=>'domstor_building_material',
            'position'=>101,
        ));

        $floor_field = new Domstor_List_Field_Flat_Floor(array(
            'name'=>'floor',
            'title'=>'Этаж',
            'css_class'=>'domstor_floor',
            'position'=>232,
            'sort_name'=>'sort-floor',
        ));

        $square_field = new Domstor_List_Field_Flat_Square(array(
            'name'=>'square',
            'title'=>'Площадь, кв.м<br />общ./жил./кух.',
            'css_class'=>'domstor_square',
            'sort_name'=>'sort-square',
            'position'=>234,
        ));

        $phone_field = new HtmlYesNoTableField(array(
            'name'=>'phone',
            'title'=>'Телефон',
            'css_class'=>'domstor_phone',
            'yes'=>'Тел.',
            'position'=>236,
        ));

        $balcony_field = new Domstor_List_Field_Flat_Balcony(array(
            'name'=>'balcony',
            'title'=>'Балкон, лоджия',
            'css_class'=>'domstor_balcony',
            'position'=>238,
        ));

        $this->addField($room_count_field)
             ->addField($building_material_field)
             ->addField($floor_field)
             ->addField($square_field)
             ->addField($phone_field)
             ->addField($balcony_field)
        ;
    }
}
