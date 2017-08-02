<?php

/**
 * Description of SquareGround
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_SquareGround extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a = $this->getRow();
        $a['square_ground_min'] = $a['square_ground_min']? str_replace('.', ',', $a['square_ground_min']) : null;
        $a['square_ground_max'] = $a['square_ground_max']? str_replace('.', ',', $a['square_ground_max']) : null;
        $out = $this->getFromTo($a['square_ground_min'], $a['square_ground_max'], ' '.$a['square_ground_unit'], '', true);
        return $out;
    }
}
