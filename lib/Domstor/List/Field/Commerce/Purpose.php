<?php

/**
 * Description of Purpose
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_Purpose extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a = $this->getRow();
        $out = '';
        if ($purp = $a['Purposes']) {
            if (isset($purp[1001]) and $purp[1001]) {
                unset($purp[1002], $purp[1003]);
            }
            if (isset($purp[1004]) and $purp[1004]) {
                unset($purp[1005], $purp[1006]);
            }
            if (isset($purp[1009]) and $purp[1009]) {
                for ($i=1013; $i<1022; $i++) {
                    unset($purp[$i]);
                }
            }
            $out=implode(', ', $purp);
        }
        return $out;
    }
}
