<?php

/**
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
interface SP_Form_FieldInterface
{
    public function setName($value);

    public function getName();

    public function setLabel($value);

    public function getLabel();

    public function setMethod($method);

    public function getMethod();

    public function getMethodName();

    public function renderLabel();

    public function getFullName();

    public function getId();

    public function render();

    public function display();

    public function setForm(SP_Form_FormInterface $value);

    public function bind(array $value);

    public function getRequestArray();

    public function bindFromRequest();

    public function getValue();

    public function isValuable();

    public function setTransformer($trans);

    public function getTransformedValue();
}
