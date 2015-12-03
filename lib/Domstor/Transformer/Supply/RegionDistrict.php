<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_RegionDistrict implements Domstor_Transformer_Interface
{
    public function get($data)
    {
        return empty($data['city_id'])? (empty($data['subregion'])? '' : $data['subregion']) : $data['location_name'];
    }
}

