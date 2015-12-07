<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
interface Domstor_Filter_FilterFactoryInterface
{
    /**
     *
     * @param string $object
     * @param string $action
     * @param array $params
     * @return Domstor_Filter_Form
     */
    public function create($object, $action, array $params = array());
}
