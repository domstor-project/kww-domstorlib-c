<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_RegionAddress implements Domstor_Transformer_Interface
{
    protected $address_transformer;

    public function __construct() {
        $this->address_transformer = new Domstor_Transformer_Supply_Address();
    }

    public function get($data)
    {
        $address = $this->address_transformer->get($data);

        if( !empty($data['location_id']) and !empty($data['location_name']) ) {
            $address = $data['location_name'].', '.$address;
        }

        return trim($address, ', ');
    }
}

