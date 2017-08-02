<?php

/**
 * Description of CheckboxList
 *
 * @author pahhan
 */
class SP_Form_Field_CheckboxList extends SP_Form_Field_CheckboxSet
{
    protected $_layout_class;
    protected $_element_class;
    protected $_label_class;
    protected $_layout_tag = 'ul';
    protected $_element_tag = 'li';
    protected $_add_hiding_js = true;
    protected $_is_drop_down = true;

    public function addHidingJs($val)
    {
        $this->_add_hiding_js = (bool) $val;
        return $this;
    }

    public function isDropDown($val)
    {
        $this->_is_drop_down = (bool) $val;
        $this->addHidingJs(false);
        return $this;
    }

    public function setLayoutClass($val)
    {
        $this->_layout_class = $val;
        return $this;
    }

    public function getLayoutClass()
    {
        if ($this->_layout_class) {
            return ' class="' . $this->_layout_class . '"';
        }
    }

    public function setLayoutTag($val)
    {
        $this->_layout_tag = $val;
        return $this;
    }

    public function setLabelClass($val)
    {
        $this->_label_class = $val;
        return $this;
    }

    public function getLabelClass()
    {
        if ($this->_label_class) {
            return ' class="' . $this->_label_class . '"';
        }
    }

    public function setElementClass($val)
    {
        $this->_element_class = $val;
        return $this;
    }

    public function getElementClass()
    {
        if ($this->_element_class) {
            return ' class="' . $this->_element_class . '"';
        }
    }

    public function setElementTag($val)
    {
        $this->_element_tag = $val;
        return $this;
    }

    public function render()
    {
        if (!$this->count()) {
            return '';
        }

        $out = '';

        $out.= '<' . $this->_layout_tag . $this->getLayoutClass() . ' id="' . $this->getId() . '">' . PHP_EOL;
        foreach ($this->_options as $key => $option) {
            $out.= '<' . $this->_element_tag . $this->getElementClass() . '>';
            if ($this->_label_first) {
                $out.= $this->renderCheckboxLabel($key, $option);
                $out.= $this->renderCheckboxField($key, $option);
                $out.= $this->_separator;
            } else {
                $out.= $this->renderCheckboxField($key, $option);
                $out.= $this->renderCheckboxLabel($key, $option);
                $out.= $this->_separator;
            }
            $out.= '</' . $this->_element_tag . '>' . PHP_EOL;
        }
        $out.= '</' . $this->_layout_tag . '>';
        if ($this->_add_hiding_js) {
            $out.= '<script type="text/javascript">el=document.getElementById(\'' . $this->getId() . '\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';</script>' . PHP_EOL;
        }
        return $out;
    }

    public function renderLabel()
    {
        if (!$this->count()) {
            return '';
        }
        if (!$this->_is_drop_down) {
            return $this->_label;
        }
        return '<a' . $this->getLabelClass() . ' href="#" onClick="el=document.getElementById(\'' . $this->getId() . '\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';return false;">' . $this->_label . '</a>' . PHP_EOL;
    }
}
