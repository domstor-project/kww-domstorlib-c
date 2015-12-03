<?php

/**
 * Description of ListFactory
 *
 * @author pahhan
 */
class Domstor_List_ListFactory
{
	/**
     *
     * @param type $object
     * @param type $action
     * @param array $params
     * @return boolean|Domstor_List_Common
     */
    public function create($object, $action, array $params)
	{
		if( !Domstor_Helper::checkEstateAction( $object, $action) )
                throw new Exception('Wrong object/action pair');
        if( Domstor_Helper::isCommerceType($object) ) {
            $object = 'commerce';
        }
		$class = sprintf('Domstor_List_%s_%s', ucfirst($object), ucfirst($action));
		$list = new $class($params);
		return $list;
	}
}