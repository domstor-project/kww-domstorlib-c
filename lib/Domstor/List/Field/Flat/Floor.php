<?php

/**
 * Description of Floor
 *
 * @author pahhan
 */
class Domstor_List_Field_Flat_Floor extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a = $this->getRow();
        $out = '';
        if (isset($a['object_floor']) or isset($a['building_floor'])) {
            $object=$a['object_floor']? $a['object_floor'] : '-';
            $building=$a['building_floor']? $a['building_floor'] : '-';
            $out=$object.'/'.$building;
        }
        return $out;
    }
}
