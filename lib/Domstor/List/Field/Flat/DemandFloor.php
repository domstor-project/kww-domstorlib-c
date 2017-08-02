<?php

/**
 * Description of DemandFloor
 *
 * @author pahhan
 */
class Domstor_List_Field_Flat_DemandFloor extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a=$this->getRow();
        $floor=array();
        if ($a['object_floor']) {
            $floor[]='Не выше '.$a['object_floor'].' этажа';
        }
        if ($a['object_floor_limit']) {
            $floor[]=$a['object_floor_limit'];
        }
        $out=implode(', ', $floor);
        return $out;
    }
}
