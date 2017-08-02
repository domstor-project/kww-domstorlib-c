<?php

/**
 * Keep configuration for Domstor_Filter_DataLoader
 *
 * @author pahhan
 */
class Domstor_Filter_DataLoaderConfig
{
    protected $subregions_with_big = false;
    protected $subregions_with_big_options = array();
    protected $subregions_without_empty = false;

    // Возвращает или устанавливает получать ли список с большими городами
    public function subregionsWithBig($val = null, array $options = array())
    {
        if (is_null($val)) {
            return $this->subregions_with_big;
        }

        $this->subregions_with_big = (bool) $val;
        $this->subregions_with_big_options = $options;
        return $this;
    }

    public function getSubregionOption($name)
    {
        return @$this->subregions_with_big_options[$name];
    }

    // Возвращает или устанавливает получать ли список без городов в которых нет недвижимости
    public function subregionsWithoutEmpty($val = null)
    {
        if (is_null($val)) {
            return $this->subregions_without_empty;
        }

        $this->subregions_without_empty = (bool) $val;
        return $this;
    }
}
