<?php

/**
 * Description of Balcony
 *
 * @author pahhan
 */
class Domstor_List_Field_Flat_Balcony extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a = $this->getRow();
        $out = $space = '';
        if (!empty($a['balcony_count'])) {
            $out.='Балкон';
            if ($a['balcony_count']>1) {
                $out.=' ('.$a['balcony_count'].')';
            }
            $space=', ';
        }

        if (!empty($a['loggia_count'])) {
            $out.=$space.'Лоджия';
            if ($a['loggia_count']>1) {
                $out.=' ('.$a['loggia_count'].')';
            }
            $space=', ';
        }

        if (!empty($a['balcony_arrangement'])) {
            $out.=$space.$a['balcony_arrangement'];
        }
        return $out;
    }
}
