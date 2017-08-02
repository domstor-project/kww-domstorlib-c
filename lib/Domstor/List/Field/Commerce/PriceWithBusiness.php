<?php

/**
 * Description of PriceWithBusiness
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_PriceWithBusiness extends Domstor_List_Field_Common
{
    public function __construct($attr = null)
    {
        $this->title = 'Цена';
        $this->position = 260;
        $this->css_class = 'domstor_price';
        $this->sort_name = 'sort-bprice';
        parent::__construct($attr);
    }

    public function getValue()
    {
        $a = $this->table->getRow();
        if (!isset($a['price_with_business'])) {
            return;
        }

        $out = '';
        $price = (float) $a['price_with_business'];
        if ($price) {
            $out = number_format($price, 0, ',', ' ');
            $out.= isset($a['price_currency'])? ' '.$a['price_currency'] : '';
        }

        return $out;
    }
}
