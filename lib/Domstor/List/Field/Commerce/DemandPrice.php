<?php

/**
 * Description of DemandPrice
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_DemandPrice extends Domstor_List_Field_Common
{
    protected $action;

    public function getFormatedPrice()
    {
        $a = $this->table->getRow();
        $out = '';
        if ($a['price_full']) {
            $out=number_format($a['price_full'], 0, ',', ' ');
            $out.=$this->getIf($a['price_currency'], ' ');
            $out=str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getFormatedPriceM2()
    {
        $a=$this->table->getRow();
        if ($a['price_m2']) {
            $out=number_format($a['price_m2'], 0, ',', ' ');
            $out.=$this->getIf($a['price_currency'], ' ');
            $unit = $a['price_m2_unit']=='кв.метров'? 'кв.м':$a['price_m2_unit'];
            $out.=$this->getIf($unit, ' за ');
            $out=str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getFormatedRent()
    {
        $a = $this->table->getRow();//&$this->object;
        $out = '';
        if ($a['rent_full']) {
            $out=number_format($a['rent_full'], 0, ',', ' ');
            $out.=$this->getIf($a['rent_currency'], ' ');
            if ($a['rent_period']) {
                $out.=' '.$a['rent_period'];
            }
            $out=str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getFormatedRentM2()
    {
        $a=$this->table->getRow();
        if ($a['rent_m2']) {
            $out=number_format($a['rent_m2'], 0, ',', ' ');
            $out.=$this->getIf($a['rent_currency'], ' ');
            $unit = $a['rent_m2_unit']=='кв.метров'? 'кв.м':$a['rent_m2_unit'];
            $out.=$this->getIf($unit, ' за ');
            if ($a['rent_period']) {
                $out.=' '.$a['rent_period'];
            }
            $out=str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function __construct($attr)
    {
        parent::__construct($attr);
        if ($this->action=='rent') {
            $this->title='Бюджет';
        } else {
            $this->title='Бюджет';
        }
    }

    public function getValue()
    {
        $a = $this->table->getRow();
        $out = '';
        if ($this->action=='rentuse') {
            $rent=$this->getIf($this->getFormatedRent());
            $rent_m2=$this->getIf($this->getFormatedRentM2());
            if ($rent and $rent_m2) {
                $rent_m2=' ('.$rent_m2.')';
            }
            $out=$rent.$rent_m2;
        } else {
            $price=$this->getIf($this->getFormatedPrice());
            $price_m2=$this->getIf($this->getFormatedPriceM2());
            if ($price and $price_m2) {
                $price_m2=' ('.$price_m2.')';
            }
            $out=$price.$price_m2;
        }

        return $out;
    }
}
