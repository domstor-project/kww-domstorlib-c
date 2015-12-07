<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
interface Domstor_List_ListFactoryInterface
{
    /**
     *
     * @param string $object
     * @param string $action
     * @param array $params
     * @return Domstor_List_Common
     */
    public function create($object, $action, array $params);
}
