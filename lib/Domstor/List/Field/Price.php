<?php

/**
 * Description of Price
 *
 * @author pahhan
 */
class Domstor_List_Field_Price extends Domstor_List_Field_Common
{
    protected $action;

    public function __construct($attr)
    {
        parent::__construct($attr);
        if ($this->action=='rent') {
            $this->title='Арендная ставка';
        } else {
            $this->title='Цена';
        }
    }

    public function getValue()
    {
        $a = $this->table->getRow();
        $out ='';
        if ($this->action=='rent') {
            if ((float) $a['rent_full']) {
                $out=number_format($a['rent_full'], 0, ',', ' ');
                $out.=$this->getIf($a['rent_currency'], ' ');
                $out.=$this->getIf($a['rent_period'], ' ');
            }
        } else {
            if ((float) $a['price_full']) {
                $out=number_format($a['price_full'], 0, ',', ' ');
                $out.=$this->getIf($a['price_currency'], ' ');
            }
        }
        if ($out) {
            $out=str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }
}
