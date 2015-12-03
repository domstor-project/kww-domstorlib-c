<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_CityAddress implements Domstor_Transformer_Interface
{
    protected $address_transformer;

    public function __construct() {
        $this->address_transformer = new Domstor_Transformer_Supply_Address();
    }

    public function get($data)
    {
        $out = $this->address_transformer->get($data);
        if( !$out ) {
            //$out.= $data['address_note'];
        }

        return $out;
    }
}

