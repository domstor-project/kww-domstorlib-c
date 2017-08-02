<?php

/**
 * Description of Sale
 *
 * @author pahhan
 */
class Domstor_List_Land_Sale extends Domstor_List_Supply
{
    public function __construct($attr)
    {
        parent::__construct($attr);

        $this->getField('type')->setTitle('Тип участка');

        $square_field = new Domstor_List_Field_SquareGround(array(
            'name'=>'square_ground',
            'title'=>'Площадь',
            'css_class'=>'domstor_square_ground',
            'position'=>101,
            'sort_name'=>'sort-square',
        ));

        $living_building_field = new HtmlTableField(array(
            'name'=>'living_building',
            'title'=>'Жилые постройки',
            'css_class'=>'living_building_type',
            'position'=>232,
        ));

        $square_house_field = new HtmlTableField(array(
            'name'=>'square_house',
            'title'=>'Площадь жилой постройки',
            'dont_show_if'=>'0',
            'adds'=>' кв.м',
            'css_class'=>'square_house_type',
            'position'=>234,
        ));

        $other_building_field = new HtmlYesNoTableField(array(
            'name'=>'other_building',
            'title'=>'Прочие постройки',
            'yes'=>'Есть',
            'css_class'=>'other_building_type',
            'position'=>236,
        ));

        $this->addField($square_field)
             ->addField($living_building_field)
             ->addField($square_house_field)
             ->addField($other_building_field)
        ;
    }
}
