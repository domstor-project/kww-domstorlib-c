<?php

/**
 * Description of Purchase
 *
 * @author pahhan
 */
class Domstor_List_House_Purchase extends Domstor_List_Demand
{
    public function __construct($attr)
    {
        parent::__construct($attr);

        $room_count_field = new HtmlMinMaxTableField(array(
            'name'=>'room_count',
            'title'=>'Число комнат',
            'min'=>'room_count_min',
            'max'=>'room_count_max',
            'dont_show_if'=>'0',
            'position'=>101,
        ));

        $square_field = new HtmlMinMaxTableField(array(
            'name'=>'square_house',
            'title'=>'Площадь дома',
            'min'=>'square_house_min',
            'max'=>'square_house_max',
            'dont_show_if'=>'0',
            'adds'=>' кв.м.',
            'position'=>234,
        ));

        $square_ground_field = new Domstor_List_Field_SquareGroundDemand(array(
            'name'=>'square_round',
            'title'=>'Площадь участка',
            'css_class'=>'domstor_square_round',
            'position'=>236,
        ));

        $other_building_field = new HtmlTableField(array(
            'name'=>'other_building',
            'title'=>'Постройки',
            'css_class'=>'domstor_other_building',
            'position'=>238,
        ));

        $this->addField($room_count_field)
             //->addField($floor_field)
             ->addField($square_field)
             ->addField($square_ground_field)
             //->addField($other_building_field)
        ;
    }
}
