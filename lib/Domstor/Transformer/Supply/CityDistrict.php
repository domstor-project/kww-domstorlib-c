<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_CityDistrict implements Domstor_Transformer_Interface
{
    public function get($data)
    {
        return empty($data['district']) ?
            (empty($data['location_name']) || $data['location_name'] == $data['master_city'] ? '' : $data['location_name']) :
            $data['district'];
    }
}
