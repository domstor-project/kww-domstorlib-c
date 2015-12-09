<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_List_Field_Code extends Domstor_List_Field_Common
{
    protected $object_href;
    protected $id_placeholder;

    public function getValue() {
        $a = $this->getTable()->getRow();
        $href = str_replace($this->id_placeholder, $a['id'], $this->object_href);
        $out = '<a href="' . $href . '" title="Перейти на страницу объекта ' . $a['code'] . '" class="domstor_link">' . $a['code'] . '</a>';
        return $out;
    }

}
