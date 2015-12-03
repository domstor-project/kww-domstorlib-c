<?php

class HtmlTable {

    protected $fields = array();
    protected $data = array();
    protected $show_head = true;
    protected $css_class;
    protected $row;
    protected $sort;

    public function __construct($attr = array()) {
        $this->init($attr);
    }

    public function init(array $attr) {
        foreach ($attr as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __toString() {
        return $this->getHtml();
    }

    private function sortFields() {
        foreach ($this->fields as $name => $field) {
            $sort[$name] = $field->getPosition();
        }
        asort($sort);
        foreach ($sort as $key => $value) {
            $new_fields[$key] = $this->fields[$key];
        }
        $this->fields = $new_fields;
    }

    public function addField($field, $title = null, $css_class = null) {
        if (is_object($field)) {
            $field->setTable($this);
            $this->fields[$field->name] = $field;
        } else {
            $name = $field;
            $field = new HtmlTableField(array('name' => $name, 'title' => $title, 'css_class' => $css_class));
            $field->setTable($this);
            $this->fields[$name] = $field;
        }
        return $this;
    }

    public function setFields($fields) {
        $this->fields = $fields;
        return $this;
    }

    public function addFields($fields) {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    public function deleteField($name) {
        unset($this->fields[$name]);
        return $this;
    }

    /**
     *
     * @param string $name
     * @return HtmlTableField
     */
    public function getField($name) {
        return $this->fields[$name];
    }

    public function dumpFields() {
        foreach ($this->fields as $name => $field) {
            printf("%s\t%s\n", $name, $field->getPosition());
        }
    }

    public function getFieldsCount() {
        return count($this->fields);
    }

    public function setFieldTitle($name, $value) {
        $this->getField($name)->setTitle($value);
        return $this;
    }

    public function setFieldPosition($name, $value) {
        $this->getField($name)->setPosition($value);
        return $this;
    }

    public function setFieldCssClass($name, $value) {
        $this->getField($name)->setCssClass($value);
        return $this;
    }

    public function getRow() {
        return $this->row;
    }

    public function getSort() {
        return $this->sort;
    }

    public function setCssClass($value) {
        $this->css_class = $value;
        return $this;
    }

    public function showHead($value) {
        $this->show_head = (bool) $value;
        return $this;
    }

    public function setData($value) {
        if (is_array($value)) {
            $this->data = $value;
            return $this;
        } else {
            throw new Exception('Argument of setData() must be array');
        }
    }

    public function getData() {
        return $this->data;
    }

    protected function getRowHtml($classes = NULL) {
        $out = $classes ? '<tr class="' . $classes . '">' : '<tr>';
        foreach ($this->fields as $name => $field) {
            if (array_key_exists($name, $this->row)) {
                $field->setValue($this->row[$name]);
            }
            $out.=$field->getHtml();
        }
        $out.='</tr>';
        return $out;
    }

    public function getHtml() {
        $this->sortFields();
        $out = '';
        if (is_array($this->data) and ! empty($this->fields)) {
            if (!empty($this->css_class))
                $css = ' class="' . $this->css_class . '"';
            $out.="<table$css>";
            if ($this->show_head) {
                $out.='<thead><tr>';
                foreach ($this->fields as $name => $field) {
                    $out.=$field->getHeadHtml();
                }
                $out.='</tr></thead>';
            }
            $out.='<tbody>';
            foreach ($this->data as $row) {
                $this->row = $row;
                $out.=$this->getRowHtml();
            }
            $out.='</tbody></table>';
        }
        return $out;
    }

}

class HtmlTableField {

    var $name;
    var $title;
    var $css_class;
    protected $value;
    protected $table;
    protected $row;
    protected $link;
    protected $adds;
    protected $from_row_adds;
    protected $position = 0;
    protected $dont_show_if;
    protected $sort_name;
    var $row_span;
    var $col_span;

    public function __construct($attr = null) {
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getData() {
        return $this->getTable()->getData();
    }

    public function dontShowIf($value) {
        $this->dont_show_if = $value;
    }

    public function setSortName($value) {
        $this->sort_name = $value;
    }

    public function removeSortName() {
        $this->sort_name = null;
    }

    public function setTable($value) {
        $this->table = $value;
    }

    public function getTable() {
        return $this->table;
    }

    public function setTitle($value) {
        $this->title = $value;
    }

    public function setCssClass($value) {
        $this->css_class = $value;
    }

    public function setPosition($value) {
        $this->position = $value;
    }

    public function hasPosition() {
        return $this->position !== false;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setRow($value) {
        $this->row = $value;
    }

    public function getRow() {
        return $this->getTable()->getRow();
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        if ($this->dont_show_if !== $this->value) {
            $adds = '';
            if ($this->adds) {
                $adds = $this->adds;
            } elseif ($this->from_row_adds) {
                $row = $this->getRow();
                $adds = $row[$this->from_row_adds];
            }
            return $this->value . $adds;
        }
    }

    public function setName($value) {
        $this->name = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function getLink() {
        if (!empty($this->link['css_class']))
            $css = ' class="' . $this->link['css_class'] . '"';
        if ($this->getValue())
            return '<a' . $css . ' href="' . $this->link['href'] . '">' . $this->getValue() . '</a>';
    }

    public function compileCssClass() {
        $css = (array) $this->css_class;
        return implode(' ', $css);
    }

    public function getHtml() {
        $css = '';
        $span = '';
        $css_class = $this->compileCssClass();
        if (!empty($css_class))
            $css = ' class="' . $css_class . '"';
        if (!empty($this->row_span))
            $span = ' rowspan="' . $this->row_span . '"';
        $out = $this->link ? $this->getLink() : $this->getValue();
        return "<td$css$span>$out</td>\n\r";
    }

    public function getHeadHtml() {
        $css = '';
        if (!empty($this->css_class))
            $css = ' class="' . $this->css_class . '_head"';

        if ($this->sort_name) {

            $sort = $this->getTable()->getSort();

            if (isset($sort['input'][$this->sort_name])) {
                $desc = $sort['input'][$this->sort_name];
                if ($desc == 'd') {
                    $desc = 'a';
                    $title = 'title="Сортировать по возрастанию"';
                } else {
                    $desc = 'd';
                    $title = 'title="Сортировать по убыванию"';
                }
            } else {
                $desc = 'a';
                $title = 'title="Сортировать по возрастанию"';
            }


            $uri_part = str_replace(array('%name%', '%desc%'), array($this->sort_name, $desc), $sort['uri_part']);
            $href = str_replace('%sort', $uri_part, $sort['uri']);
            $td_content = '<a href="' . $href . '" ' . $title . ' >' . $this->title . '</a>';
        } else {
            $td_content = $this->title;
        }
        return '<td' . $css . '>' . $td_content . '</td>';
    }

}

class HtmlMinMaxTableField extends HtmlTableField {

    protected $min;
    protected $max;
    protected $min_value;
    protected $max_value;
    protected $adds;
    protected $from = 'от';
    protected $to = 'до';
    protected $if_equal_show_one = true;
    protected $if_one_without_fromto = false;

    public function getValue() {
        $row = $this->table->getRow();
        $out = '';
        if (isset($this->min))
            $this->min_value = $row[$this->min];
        if (isset($this->max))
            $this->max_value = $row[$this->max];
        if ($this->if_equal_show_one and $this->min_value == $this->max_value) {
            if ($this->dont_show_if !== $this->min_value)
                $out = $this->min_value;
        }
        else {
            if (isset($this->min_value) and $this->dont_show_if !== $this->min_value) {
                $out.=$this->from . ' ' . $this->min_value;
                $space = ' ';
            } elseif ($this->if_one_without_fromto and $this->dont_show_if !== $this->max_value) {
                $out = $this->max_value;
            }

            if (isset($this->max_value) and $this->dont_show_if !== $this->max_value) {
                $out.=$space . $this->to . ' ' . $this->max_value;
            } elseif ($this->if_one_without_fromto and $this->dont_show_if !== $this->min_value) {
                $out = $this->min_value;
            }
        }
        if ($out and $this->adds)
            $out.=$this->adds;
        return $out;
    }

}

class HtmlYesNoTableField extends HtmlTableField {

    protected $yes;
    protected $no;
    protected $row_yes;
    protected $row_no;
    protected $row_condition;
    protected $isset;
    protected $myself;

    public function getValue() {
        $row = $this->table->getRow();
        $value = $this->row_condition ? $row[$this->row_condition] : $this->value;
        $yes = $this->row_yes ? $row[$this->row_yes] : $this->yes;
        $no = $this->row_no ? $row[$this->row_no] : $this->no;
        //var_dump($this);
        if ($this->isset) {
            if (!isset($value))
                return;
        }

        if ($value) {
            return ($this->myself ? $value : $yes);
        } else {
            return $no;
        }
    }

}

class HtmlLinkedTableField extends HtmlTableField {

    protected $params;
    protected $datas;

    public function getLink() {
        if (!is_array($this->params))
            $this->params = array($this->params);
        if (!is_array($this->datas))
            $this->datas = array($this->datas);
        $row = $this->table->getRow();
        foreach ($this->datas as $name) {
            $datas[] = $row[$name];
        }
        $href = str_replace($this->params, $datas, $this->link['href']);
        if (!empty($this->link['css_class']))
            $css = ' class="' . $this->link['css_class'] . '"';
        return '<a' . $css . ' href="' . $href . '">' . $this->getValue() . '</a>';
    }

}

class HtmlDelimitedTableField extends HtmlTableField {

    protected $params;
    protected $delimiter;

    public function getValue() {
        $out = '';
        if (!is_array($this->params))
            $this->params = array($this->params);
        $row = $this->table->getRow();
        foreach ($this->params as $param) {
            if ($row[$param] !== $this->dont_show_if)
                $out.= $row[$param] . $this->delimiter;
        }
        $out = substr($out, 0, -1 * mb_strlen($this->delimiter));
        return $out;
    }

}
