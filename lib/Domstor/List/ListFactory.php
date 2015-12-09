<?php

/**
 * Description of ListFactory
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_List_ListFactory implements Domstor_List_ListFactoryInterface
{

    /**
     *
     * @param string $object
     * @param string $action
     * @param array $params
     * @return Domstor_List_Common
     * @throws Exception
     */
    public function create($object, $action, array $params) {
        if (!Domstor_Helper::checkEstateAction($object, $action)) {
            throw new Exception('Wrong object/action pair');
        }
            
        if (Domstor_Helper::isCommerceType($object)) {
            $object = 'commerce';
        }
        
        $class = sprintf('Domstor_List_%s_%s', ucfirst($object), ucfirst($action));
        $list = new $class($params);
        
        return $list;
    }

}
