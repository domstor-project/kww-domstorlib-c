<?php

/**
 * Description of DetailFactory
 *
 * @author pahhan
 */
class Domstor_Detail_DetailFactory
{
	/**
     *
     * @param string $object
     * @param string $action
     * @param array $params
     * @return boolean|Domstor_Detail_Supply
     */
    public function create($object, $action, array $params)
	{
		if( !Domstor_Helper::checkEstateAction( $object, $action) ) return FALSE;

		$offer = ($action=='purchase' or $action=='rentuse')? 'Demand' : 'Supply';

		$commerce = array(
			'trade',
			'office',
			'product',
			'storehouse',
			'landcom',
			'other',
            'complex',
		);
		if( in_array($object, $commerce) ) $object = 'Commerce';

		$class = sprintf('Domstor_Detail_%s_%s', $offer, ucfirst($object));

		$obj = new $class($params);
		return $obj;
	}
}