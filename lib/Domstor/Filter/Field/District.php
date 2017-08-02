<?php

/**
 * Description of District
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Filter_Field_District extends SP_Form_Field_CheckboxList
{
    protected $_sublayout_class;
    protected $_subelement_class;

    public function __construct()
    {
    }

    public function setSublayoutClass($val)
    {
        $this->_sublayout_class = $val;
        return $this;
    }

    public function getSublayoutClass()
    {
        if ($this->_sublayout_class) {
            return ' class="' . $this->_sublayout_class . '"';
        }
    }

    public function setSubelementClass($val)
    {
        $this->_subelement_class = $val;
        return $this;
    }

    public function getSubelementClass()
    {
        if ($this->_subelement_class) {
            return ' class="' . $this->_subelement_class . '"';
        }
    }

    public function render()
    {
        if (!$this->count()) {
            return '';
        }

        $out = '<' . $this->_layout_tag . $this->getLayoutClass() . ' id="' . $this->getId() . '" >' . PHP_EOL;
        foreach ($this->_options as $key => $option) {
            $out.= '<' . $this->_element_tag . $this->getElementClass() . '>';

            $out.= $this->renderCheckboxField($key, $option);

            $out.= '</' . $this->_element_tag . '>' . PHP_EOL;
        }
        $out.= '</' . $this->_layout_tag . '>';
        if ($this->_add_hiding_js) {
            $out.= '<script type="text/javascript">el=document.getElementById(\'' . $this->getId() . '\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';</script>' . PHP_EOL;
        }
        return $out;
    }

    public function renderCheckboxField($key, $option)
    {
        $out = parent::renderCheckboxField($key, $option);
        $district = $this->_options[$key];
        if (count($district['Subdistricts'])) {
            $out.= $this->renderCheckboxLinkedLabel($key, $option);
            $out.= '<' . $this->_layout_tag . $this->getLayoutClass() . ' id="' . $this->getId() . '_' . $key . '_fields">' . PHP_EOL;
            foreach ($district['Subdistricts'] as $subdistrict) {
                $out.= '<' . $this->_element_tag . $this->getElementClass() . '>';
                if ($this->_label_first) {
                    $out.= $this->renderCheckboxSubLabel($subdistrict);
                    $out.= $this->renderCheckboxSubField($subdistrict);
                    $out.= $this->_separator;
                } else {
                    $out.= $this->renderCheckboxSubField($subdistrict);
                    $out.= $this->renderCheckboxSubLabel($subdistrict);
                    $out.= $this->_separator;
                }
                $out.= '</' . $this->_element_tag . '>' . PHP_EOL;
            }
            $out.= '</' . $this->_layout_tag . '>' . PHP_EOL;
            if ($this->_add_hiding_js) {
                $out.= '<script type="text/javascript">el=document.getElementById(\'' . $this->getId() . '_' . $key . '_fields\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';</script>' . PHP_EOL;
            }
        } else {
            $out.= $this->renderCheckboxLabel($key, $option);
        }
        return $out;
    }

    public function renderCheckboxSubField($subdistrict)
    {
        return parent::renderCheckboxField($subdistrict['id'], null);
    }

    public function renderCheckboxSubLabel($subdistrict)
    {
        return parent::renderCheckboxLabel($subdistrict['id'], $subdistrict['name']);
    }

    public function renderCheckboxLabel($key, $option)
    {
        $option = $option['name'];
        return parent::renderCheckboxLabel($key, $option);
    }

    public function renderCheckboxLinkedLabel($key, $option)
    {
        $option = $option['name'];
        $id = $this->getId() . '_' . $key . '_fields';
        if (!$this->_is_drop_down) {
            return '<label for="' . $this->getId() . '_' . $key . '">' . $option . '</label>';
        }
        return '<a title="Показать подрайоны" href="#" onClick="el=document.getElementById(\'' . $id . '\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';return false;">' . $option . '</a>' . PHP_EOL;
    }
}
