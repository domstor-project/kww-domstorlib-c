<?php

/**
 * Description of Thumb
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_List_Field_Thumb extends Domstor_List_Field_Common {

    protected $object_href;
    protected $id_placeholder;

    public function getValue() {
        $a = $this->getTable()->getRow();
        $out = '';
        if (isset($a['thumb']) && $a['thumb']) {
            $href = str_replace($this->id_placeholder, $a['id'], $this->object_href);
            $out = '<img src="http://' . $this->getTable()->getServerName() . '/' . $a['thumb'] . '" alt="" />';
            $out = '<a href="' . $href . '" title="Перейти на страницу объекта ' . $a['code'] . '" class="domstor_link">' . $out . '</a>';
        }
        return $out;
    }

}
