<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_Address implements Domstor_Transformer_Interface
{
    public function get($data)
    {
        $street = empty($data['street'])?
            (empty($data['street_name'])? '' : $data['street_name'] ) :
            $data['street'];
        $out = '';
        if( $street ) {
            $out = $street;
            if( isset($data['building_num']) and $data['building_num'] ) {
                $out.= ', '.$data['building_num'];
                if( $data['corpus'] ) {
                    if( is_numeric($data['corpus']) ) {
                        $out.= '/';
                    }
                    $out.= $data['corpus'];
                }
            }
        }
        return $out;
    }
}

