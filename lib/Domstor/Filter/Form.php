<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Filter_Form extends SP_Form_Form {

    // Ссылка на строитель форм
    protected $_builder;
    // Ссылка на загрузчик данных
    protected $_data_loader;

    public function setDataLoader(Domstor_Filter_DataLoader $loader) {
        $this->_data_loader = $loader;
        return $this;
    }

    public function getDataLoader() {
        return $this->_data_loader;
    }

    public function getBuilder() {
        return $this->_builder;
    }

    public function setBuilder(DomstorCommonBuilder $builder) {
        $this->_builder = $builder;
        return $this;
    }

    public function renderHidden() {
        $get_array = array('object', 'action', 'inreg', 'ref_city');
        $out = '';
        // $out = '<input type="hidden" name="filter" value="" />'.PHP_EOL;
        if (is_array($_GET)) {
            foreach ($_GET as $key => $value) {
                if (strpos($key, 'sort-') !== false or in_array($key, $get_array)) {
                    $out.= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . PHP_EOL;
                }
            }
        }
        return $out;
    }

    public function displayHidden() {
        echo $this->renderHidden();
    }

    public function displayFieldLabel($name, $separator = ' ') {
        $this->displayField($name);
        echo $separator;
        $this->displayLabel($name);
    }

    public function displayLabelField($name, $separator = ' ') {
        $this->displayLabel($name);
        echo $separator;
        $this->displayField($name);
    }

}
