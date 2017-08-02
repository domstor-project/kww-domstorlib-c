<?php

/**
 * Description of Supply
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_List_Supply extends Domstor_List_Common
{
    public function checkThumb()
    {
        foreach ($this->data as $a) {
            if (isset($a['thumb'])) {
                return true;
            }
        }
        return false;
    }

    public function __construct($attr)
    {
        parent::__construct($attr);

        $thumb_field = new Domstor_List_Field_Thumb(array(
            'name' => 'thumb',
            'title' => 'Фото',
            'css_class' => 'domstor_thumb',
            'position' => 25,
            'object_href' => $this->object_href,
            'id_placeholder' => $this->id_placeholder,
        ));

        $price_field = new Domstor_List_Field_Price(array(
            'name' => 'price',
            'css_class' => 'domstor_price',
            'action' => $this->action,
            'sort_name' => 'sort-price',
            'position' => 260,
        ));

        $district_field = new Domstor_List_Field_Common(array(
            'name' => 'district',
            'title' => $this->in_region ? 'Город/<br>Район региона' : 'Район',
            'css_class' => 'domstor_district',
            'position' => 200,
            'sort_name' => 'sort-district',
            'transformer' => $this->in_region ?
                new Domstor_Transformer_Supply_RegionDistrict() :
                new Domstor_Transformer_Supply_CityDistrict(),
        ));

        $adress_transformer = $this->in_region ? new Domstor_Transformer_Supply_RegionAddress() : new Domstor_Transformer_Supply_CityAddress();

        $address_field = new Domstor_List_Field_Common(array(
            'name' => 'address',
            'title' => 'Адрес',
            'css_class' => 'domstor_address',
            'position' => 230,
            'sort_name' => 'sort-street',
            'transformer' => new Domstor_Transformer_LinkToObject($adress_transformer, $this->object_href, $this->id_placeholder)
        ));

        $this->addField($price_field)
            ->addField($address_field)
            ->addField($district_field)
        ;
        if ($this->checkThumb()) {
            $this->addField($thumb_field);
        }
        if ($this->action == 'rent') {
            $this->getField('price')->setSortName('sort-rent');
        }
    }
}
