<?php

/**
 * Description of Floor
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_Floor extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a = $this->getRow();
        $min = $a['object_floor_min'];
        $max = $a['object_floor_max'];
        $min_flag = false;
        $max_flag = false;
        $out = '';

        if (isset($min) and $min != '') {
            $min_flag = true;
        }
        if (isset($max) and $max != '') {
            $max_flag = true;
        }

        if ($min_flag and $max_flag) {
            if ($min == $max) {
                $out = ($min == '0')? 'цоколь' : $min;
            } else {
                $out = 'от&nbsp;'.$min.' до&nbsp;'.$max;
                $out = str_replace('0', 'цоколя', $out);
            }
        } elseif ($min_flag or $max_flag) {
            if ($min_flag) {
                $out = 'от&nbsp;'.$min;
            } else {
                $out = 'до&nbsp;'.$max;
            }
            $out = str_replace('0', 'цоколя', $out);
        }

        return $out;
    }
}
