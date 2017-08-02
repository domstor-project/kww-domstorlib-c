<?php

/**
 * Description of AddressDemand
 *
 * @author pahhan
 */
class Domstor_List_Field_AddressDemand extends Domstor_List_Field_Common
{
    protected $in_region;
    protected $object_href;
    public function getValue()
    {
        $a = $this->getTable()->getRow();
        $out = '';
        if ($this->in_region) {
            $out.=$this->getIf($a['address_note'], '', ', ');
            $out.=$this->getIf($a['city'], '', ', ');
        } else {
            $out.=$this->getIf($a['street'], '', ', ');
            $out.=$this->getIf($a['address_note'], '', ', ');
        }
        $out=substr($out, 0, -2);

        if ($out) {
            $href=str_replace('%id', $a['id'], $this->object_href);
            $out='<a href="'.$href.'" title="Перейти на страницу заявки '.$a['code'].'" class="domstor_link">'.$out.'</a>';
        }
        return $out;
    }
}
