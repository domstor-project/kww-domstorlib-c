<?php

/**
 * Description of DataLoader
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Filter_DataLoader {

    protected $_config;
    protected $_filter;

    public function __construct(Domstor_Filter_Form $filter) {
        $this->_filter = $filter;
        $this->_config = new Domstor_Filter_DataLoaderConfig();
        $filter->setDataLoader($this);
    }

    public function getFilter() {
        return $this->_filter;
    }

    public function getBuilder() {
        return $this->getFilter()->getBuilder();
    }

    public function getDomstor() {
        return $this->getBuilder()->getDomstor();
    }

    public function getConfig() {
        return $this->_config;
    }

    public function setConfig($val) {
        $this->_config = $val;
    }

    public function getSubregions() {
        $domstor = $this->getDomstor();

        $url = '/gateway/location/subregion?ref_city=' . $domstor->getRealParam('ref_city');
        //var_dump($this);
        if ($this->getConfig()->subregionsWithBig())
            $url.= '&with_big=1';
        if ($this->getConfig()->subregionsWithoutEmpty()) {
            $url.= '&agency=' . $domstor->getMyId();
            $url.= '&object=' . $this->getBuilder()->getObject();
            $url.= '&action=' . $this->getBuilder()->getAction();
        }
        $records = $domstor->read($url);
        if ($exclude = $this->getConfig()->getSubregionOption('exclude')) {
            $ids = explode(',', $exclude);
            foreach ($ids as $id) {
                if (isset($records[$id]))
                    unset($records[$id]);
            }
        }
        return $records;
    }

    public function getLocations() {
        $domstor = $this->getDomstor();
        $url = '/gateway/location/location?ref_city=' . $domstor->getRealParam('ref_city');
        $records = $domstor->read($url);
        return $records;
    }

    public function getSuburbans() {
        $domstor = $this->getDomstor();
        $url = '/gateway/location/suburban?ref_city=' . $domstor->getRealParam('ref_city');
        return $domstor->read($url);
    }

}
