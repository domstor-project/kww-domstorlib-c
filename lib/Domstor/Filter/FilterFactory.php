<?php

require_once dirname(__FILE__) . '/builders.php';

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Filter_FilterFactory implements Domstor_Filter_FilterFactoryInterface
{

    /**
     *
     * @param string $object
     * @param string $action
     * @param array $params
     * @return Domstor_Filter_Form
     */
    public function create($object, $action, array $params = array())
    {
        if (Domstor_Helper::isCommerceType($object)) {
            $object = 'commerce';
        }

        $builder_class = 'Domstor' . ucfirst($object) . ucfirst($action) . 'FilterBuilder';
        if (!class_exists($builder_class)) {
            return false; //throw new Excepion($builder_class.' not found');
        }

        $builder = new $builder_class;
        $builder->setDomstor($params['domstor'])->setObject($object)->setAction($action);
        $template = dirname(__FILE__) . '/view/' . $object . '_' . $action . '_tmpl.php';
        if (isset($params['filter_dir'])) {
            $dir = $params['filter_dir'];
            if (!is_readable($dir) || !is_dir($dir)) {
                throw new Exception(sprintf('Filter template dir "%s" is not dir or not readable', $dir));
            }
            $fd = rtrim($params['filter_dir'], '/\\') . '/' . $object . '_' . $action . '_tmpl.php';
            if (is_file($fd) and is_readable($fd)) {
                $template = $fd;
            }
        }
        $filter = $builder->buildFilter();
        $filter->setName('f')->setRenderTemplate($template);
        return $filter;
    }
}
