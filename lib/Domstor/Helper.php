<?php

/**
 * Description of Helper
 *
 * @author pahhan
 */
class Domstor_Helper
{
	protected static $estate_actions = array(
		'flat' => array('sale', 'rent', 'purchase', 'rentuse', 'exchange', 'new'),
		'house' => array('sale', 'rent', 'purchase', 'rentuse', 'exchange'),
		'complex' => array('sale', 'rent'),
		'standart' => array('sale', 'rent', 'purchase', 'rentuse'),
	);

	protected static $standart_estate = array(
		'garage',
		'land',
		'commerce',
		'trade',
		'office',
		'product',
		'storehouse',
		'landcom',
		'other',
	);


	protected static $commerce_types = array(
        'trade',
        'office',
        'product',
        'storehouse',
        'other',
        'landcom',
        'complex'
    );

    public static function checkEstateAction($object, $action)
	{
		if( in_array($object, self::$standart_estate) )
		{
			return in_array($action, self::$estate_actions['standart']);
		}
		elseif( array_key_exists($object, self::$estate_actions) )
		{
			return in_array($action, self::$estate_actions[$object]);
		}
		return false;
	}

    public static function isCommerceType($type)
    {
        return in_array($type, self::$commerce_types);
    }
}