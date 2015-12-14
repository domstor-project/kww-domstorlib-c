<?php

/**
 * Description of Sale
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_List_Commerce_Sale extends Domstor_List_Supply
{
    protected $show_square_house = false;
    protected $show_square_ground = false;

    public function checkSquare() {
        if (!is_array($this->data))
            return FALSE;
        foreach ($this->data as $a) {
            if (isset($a['Purposes'][1009]) and $a['Purposes'][1009]) {

                if (count($a['Purposes']) == 1) {
                    $this->show_square_ground = true;
                } else {
                    $this->show_square_ground = true;
                    $this->show_square_house = true;
                    return;
                }
            } else {
                $this->show_square_house = true;
            }
        }
    }

    public function __construct($attr) {
        parent::__construct($attr);
        $this->addCssClass('commerce_'.$this->action);
        $type_field = new Domstor_List_Field_Commerce_Purpose(array(
            'name' => 'type',
            'title' => 'Назначение',
            'css_class' => 'domstor_type',
            'sort_name' => 'sort-purpose',
            'position' => 100,
            ));

        $floor_field = new Domstor_List_Field_Commerce_Floor(array(
            'name' => 'floor',
            'title' => 'Этаж',
            'css_class' => 'domstor_floor',
            'position' => 231,
            ));

        $square_field = new Domstor_List_Field_Commerce_Square(array(
            'name' => 'square_house',
            'title' => 'Площадь',
            'css_class' => 'domstor_square_house',
            'sort_name' => 'sort-square',
            'position' => 232,
            ));

        $square_ground_field = new Domstor_List_Field_Commerce_SquareGround(array(
            'name' => 'square_ground',
            'title' => 'Площадь земельного участка',
            'css_class' => 'domstor_square_ground',
            'sort_name' => 'sort-groundsq',
            'position' => 233,
            ));

        $price_field = new Domstor_List_Field_Commerce_Price(array(
            'name' => 'price',
            'css_class' => 'domstor_price',
            'action' => $this->action,
            'sort_name' => 'sort-price',
            'position' => 260,
            ));

        $this->checkSquare();
        $this->addField($type_field)
            ->addField($floor_field)
            ->addField($price_field)
        ;
        if ($this->show_square_house)
            $this->addField($square_field);
        if ($this->show_square_ground)
            $this->addField($square_ground_field);
        if ($this->action == 'rent')
            $this->getField('price')->setSortName('sort-rent');
    }

}
