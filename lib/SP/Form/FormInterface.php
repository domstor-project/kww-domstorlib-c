<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
interface SP_Form_FormInterface {

    public function addField(SP_Form_FieldInterface $field);

    public function addFields(array $fields);

    public function hasField($name);

    public function getField($name);

    public function deleteField($name);

    public function renderOpenTag();

    public function renderCloseTag();

    public function setAction($action);

    public function getAction();

    public function setRenderTemplate($path);

    public function getRequestString();

    public function replaceString($key, $string);
}
