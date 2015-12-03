<?php

class DomstorCommonBuilder {

    protected $_form;
    protected $_domstor;
    protected $_object;
    protected $_action;

    public function __construct() {
        $this->_form = new Domstor_Filter_Form;
        $this->_form->setBuilder($this);
        $data_loader = new Domstor_Filter_DataLoader($this->_form);

        // Добавление кнопок отправки формы
        DomstorSubmitConstructor::add($this->_form);
        $code = new SP_Form_Field_InputText;
        $code->setName('code')->setLabel('Код объекта:');
        $this->_form->addField($code);
    }

    public function setDomstor($val) {
        $this->_domstor = $val;
        $this->_form->getDataLoader()->setConfig($this->_domstor->getFilterDataLoaderConfig());
        return $this;
    }

    public function getDomstor() {
        return $this->_domstor;
    }

    public function setObject($val) {
        $this->_object = $val;
        return $this;
    }

    public function getObject() {
        return $this->_object;
    }

    public function setAction($val) {
        $this->_action = $val;
        return $this;
    }

    public function getAction() {
        return $this->_action;
    }

}

class DomstorPriceConstructor {

    public static function add($form) {
        
        $price_form = new DomstorPriceForm;
        $form->addField($price_form);

        return $form;
    }

}

class DomstorRentForm extends Domstor_Filter_Form {

    public function __construct() {
        $this->setName('rent');

        $min = new SP_Form_Field_InputText;
        $min->setName('min')->setLabel('от');

        $max = new SP_Form_Field_InputText;
        $max->setName('max')->setLabel('до');

        $period = new SP_Form_Field_Select;
        $period->setName('period')->setOptions(array('1' => 'в месяц', '12' => 'в год'));

        $this->addFields(array(
            $min,
            $max,
            $period,
        ));
    }

    public function getServerRequestString() {
        $values = $this->getValue();
        $out = '';
        $coef = (float) $values['period'];
        if ($values['min'])
            $out.= '&rent_min=' . $values['min'] / $coef;
        if ($values['max'])
            $out.= '&rent_max=' . $values['max'] / $coef;

        return $out;
    }

}

class DomstorPriceForm extends Domstor_Filter_Form {

    public function __construct() {
        $this->setName('price');

        $min = new SP_Form_Field_InputText;
        $min->setName('min')->setLabel('от');

        $max = new SP_Form_Field_InputText;
        $max->setName('max')->setLabel('до');

        $this->addFields(array(
            $min,
            $max,
        ));
    }

    public function getServerRequestString() {
        $values = $this->getValue();
        $out = '';
        if ($values['min'])
            $out.= '&price_min=' . $values['min'] * 1000;
        if ($values['max'])
            $out.= '&price_max=' . $values['max'] * 1000;

        return $out;
    }

}

class DomstorRentLivingConstructor {

    public static function add($form) {
        $rent_form = new DomstorRentForm;
        $rent_form->getField('period')->addOptions(array('0.033' => 'в сутки'));
        $form->addField($rent_form);
        return $form;
    }

}

class DomstorSubmitConstructor {

    public static function add($form) {
        $submit_field = new SP_Form_Field_Submit;
        $submit_field->setLabel('Найти');

        $submitlink_field = new SP_Form_Field_SubmitLink;
        $submitlink_field->setLabel('Найти');

        $form->addFields(array(
            $submit_field,
            $submitlink_field,
        ));
        return $form;
    }

}

class DomstorSuburbanConstructor {

    public static function add($form, $domstor) {
        $field = new SP_Form_Field_CheckboxList;
        $options = $form->getDataLoader()->getSuburbans();
        $field->setName('suburban')
            ->setLabel('Пригород:')
            ->setOptions($options)
            ->isDropDown(FALSE)
        ;
        $form->addField($field);
    }

}

class DomstorLocationsConstructor {

    public static function add($form, $domstor) {
        if ($domstor->inRegion()) { // Если объект в регионе
            
        }
    }

}

class DomstorDistrictConstructor {

    public static function add($form, $domstor) {
        if ($domstor->inRegion()) { // Если объект в регионе
            // Районы и мелкие города региона
            $district = new SP_Form_Field_CheckboxList;
            $options = $form->getDataLoader()->getSubregions(); //$domstor->read($url);
            $district->setName('subregion')
                ->setLabel('Район / населенный&nbsp;пункт')
                ->setOptions($options)
                ->isDropDown(FALSE)
            ;
        } else {
            // Районы города
            $district = new Domstor_Filter_Field_District;
            $options = $domstor->read('/gateway/location/district?ref_city=' . $domstor->getRealParam('ref_city'));
            $district->setName('district')
                ->setLabel('Район:')
                ->setOptions($options)
                ->isDropDown(FALSE)
            ;
        }

        $form->addField($district, 'district');
        return $form;
    }

}

class DomstorRoomCountConstructor {

    public static function add($form) {
        // Число комнат
        $room_count = new SP_Form_Field_CheckboxSet;
        $room_count->setOptions(array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5 и более'));
        $room_count->setName('room_count')->setLabel('Число комнат: ');
        $form->addField($room_count);
        return $form;
    }

}

class DomstorSquareHouseConstructor {

    public static function add($form) {
        // Площадь помещения
        $square_min_field = new SP_Form_Field_InputText;
        $square_min_field->setName('squareh_min')->setLabel('от');

        $square_max_field = new SP_Form_Field_InputText;
        $square_max_field->setName('squareh_max')->setLabel('до');

        $form->addFields(array(
            $square_min_field,
            $square_max_field,
        ));
        return $form;
    }

}

class DomstorSquareGroundForm extends Domstor_Filter_Form {

    public function __construct() {
        $this->setName('squareg');

        $min = new SP_Form_Field_InputText;
        $min->setName('min')->setLabel('от');

        $max = new SP_Form_Field_InputText;
        $max->setName('max')->setLabel('до');

        $unit = new SP_Form_Field_Select;
        $unit->setName('unit')->setOptions(array('100' => 'сот.', '10000' => 'Га'));

        $this->addFields(array(
            $min,
            $max,
            $unit,
        ));
    }

    public function getServerRequestString() {
        $values = $this->getValue();
        $out = '';
        $coef = (float) $values['unit'];
        if ($values['min'])
            $out.= '&squareg_min=' . $values['min'] * $coef;
        if ($values['max'])
            $out.= '&squareg_max=' . $values['max'] * $coef;

        return $out;
    }

}

class DomstorSquareGroundConstructor {

    public static function add($form) {
        $sq_form = new DomstorSquareGroundForm;
        $form->addField($sq_form);
        return $form;
    }

}

// FLAT
// 		Sale
class DomstorFlatSaleFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // В коммуналке или нет
        $in_communal = new SP_Form_Field_Checkbox;
        $in_communal
            ->setName('in_communal')
            ->setLabel('Комнаты в коммуналке')
        ;

        // Тип этажа
        $floor_type = new SP_Form_Field_Select;
        $floor_type->setOptions(array(
            '' => 'любой',
            'first' => 'только первый',
            'last' => 'только последний',
            'not_first' => 'кроме первого',
            'not_last' => 'кроме последнего',
            'not_first_last' => 'кроме первого и последнего'
        ));
        $floor_type->setName('floor_type')->setLabel('Этаж');

        // Максимальный этаж
        $max_floor = new SP_Form_Field_Select;
        $max_floor->setName('max_floor')->setLabel('Не выше')->setRange(2, 20, array('' => ''));

        // Новостройка или нет
        $new_building = new SP_Form_Field_RadioSet;
        $new_building
            ->setName('new_building')
            ->setLabel('')
            ->setOptions(array('' => 'Все предложения', '0' => 'Вторичное жилье', '1' => 'Новостройки'))
            ->setLabelFirst(0)
            ->setSeparator('<br />')
            ->setDefault('')
        ;

        // Тип квартиры
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип квартиры:')
            ->setOptions($options)
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($in_communal)
            ->addField($floor_type)
            ->addField($max_floor)
            ->addField($new_building)
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		Rent
class DomstorFlatRentFilterBuilder extends DomstorFlatSaleFilterBuilder {

    public function buildFilter() {
        $filter = parent::buildFilter();
        // Удаление полей не нужных для аренды
        $filter->deleteField('price');
        $filter->deleteField('new_building');
        // Добавление полей стоимости аренды объекта
        DomstorRentLivingConstructor::add($filter);

        return $filter;
    }

}

// 		Purchase
class DomstorFlatPurchaseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // В коммуналке или нет
        $in_communal = new SP_Form_Field_Checkbox;
        $in_communal
            ->setName('in_communal')
            ->setLabel('Комнаты в коммуналке')
        ;

        // Тип квартиры
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип квартиры:')
            ->setOptions($options)
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($in_communal)
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		Rentuse
class DomstorFlatRentuseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorRentLivingConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // В коммуналке или нет
        $in_communal = new SP_Form_Field_Checkbox;
        $in_communal
            ->setName('in_communal')
            ->setLabel('Комнаты в коммуналке')
        ;

        // Тип квартиры
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип квартиры')
            ->setOptions($options)
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($in_communal)
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		Exchange
class DomstorFlatExchangeFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // В коммуналке или нет
        $in_communal = new SP_Form_Field_Checkbox;
        $in_communal
            ->setName('in_communal')
            ->setLabel('Комнаты в коммуналке')
        ;

        // Тип этажа
        $floor_type = new SP_Form_Field_Select;
        $floor_type->setOptions(array(
            '' => 'любой',
            'first' => 'только первый',
            'last' => 'только последний',
            'not_first' => 'кроме первого',
            'not_last' => 'кроме последнего',
            'not_first_last' => 'кроме первого и последнего'
        ));
        $floor_type->setName('floor_type')->setLabel('Этаж');

        // Максимальный этаж
        $max_floor = new SP_Form_Field_Select;
        $max_floor->setName('max_floor')->setLabel('Не выше')->setRange(2, 20, array('' => ''));

        // Тип квартиры
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип квартиры')
            ->setOptions($options)
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($in_communal)
            ->addField($floor_type)
            ->addField($max_floor)
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		New
class DomstorFlatNewFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // Тип этажа
        $floor_type = new SP_Form_Field_Select;
        $floor_type->setOptions(array(
            '' => 'любой',
            'first' => 'только первый',
            'last' => 'только последний',
            'not_first' => 'кроме первого',
            'not_last' => 'кроме последнего',
            'not_first_last' => 'кроме первого и последнего'
        ));
        $floor_type->setName('floor_type')->setLabel('Этаж');

        // Максимальный этаж
        $max_floor = new SP_Form_Field_Select;
        $max_floor->setName('max_floor')->setLabel('Не выше')->setRange(2, 20, array('' => ''));

        // Тип квартиры
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип квартиры')
            ->setOptions($options)
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($floor_type)
            ->addField($max_floor)
            ->addField($type)
        ;

        return $this->_form;
    }

}

// HOUSE
// 		Sale
class DomstorHouseSaleFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Площадь помещения
        DomstorSquareHouseConstructor::add($this->_form);

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // Тип дома
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип дома:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		Rent
class DomstorHouseRentFilterBuilder extends DomstorHouseSaleFilterBuilder {

    public function buildFilter() {
        $filter = parent::buildFilter();

        // Удаление полей не нужных для аренды
        $filter->deleteField('price');
        // Добавление полей стоимости аренды объекта
        DomstorRentLivingConstructor::add($filter);

        return $filter;
    }

}

// 		Purchase
class DomstorHousePurchaseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Площадь помещения
        DomstorSquareHouseConstructor::add($this->_form);

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // Тип дома
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип дома:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		Rentuse
class DomstorHouseRentuseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorRentLivingConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Площадь помещения
        DomstorSquareHouseConstructor::add($this->_form);

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // Тип дома
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип дома')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }

}

// 		Exchange
class DomstorHouseExchangeFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Площадь помещения
        DomstorSquareHouseConstructor::add($this->_form);

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Число комнат
        DomstorRoomCountConstructor::add($this->_form);

        // Тип дома
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип дома')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Добавление полей в форму
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }

}

// GARAGE
// 		Sale
class DomstorGarageSaleFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Тип гаража
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип гаража:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Ширина
        $x_min = new SP_Form_Field_InputText;
        $x_min->setName('x_min')->setLabel('от');
        $x_max = new SP_Form_Field_InputText;
        $x_max->setName('x_max')->setLabel('до');

        // Длина
        $y_min = new SP_Form_Field_InputText;
        $y_min->setName('y_min')->setLabel('от');
        $y_max = new SP_Form_Field_InputText;
        $y_max->setName('y_max')->setLabel('до');

        // Высота
        $z_min = new SP_Form_Field_InputText;
        $z_min->setName('z_min')->setLabel('от');
        $z_max = new SP_Form_Field_InputText;
        $z_max->setName('z_max')->setLabel('до');

        // Добавление полей в форму
        $this->_form->addFields(array(
            $type,
            $x_min,
            $x_max,
            $y_min,
            $y_max,
            $z_min,
            $z_max,
        ));

        return $this->_form;
    }

}

// 		Rent
class DomstorGarageRentFilterBuilder extends DomstorGarageSaleFilterBuilder {

    public function buildFilter() {
        $filter = parent::buildFilter();

        // Удаление полей не нужных для аренды
        $filter->deleteField('price');
        // Добавление полей стоимости аренды объекта
        DomstorRentLivingConstructor::add($filter);

        return $filter;
    }

}

// 		Purchase
class DomstorGaragePurchaseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Тип гаража
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип гаража:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Ширина
        $x_min = new SP_Form_Field_InputText;
        $x_min->setName('x_min')->setLabel('от');
        $x_max = new SP_Form_Field_InputText;
        $x_max->setName('x_max')->setLabel('до');

        // Длина
        $y_min = new SP_Form_Field_InputText;
        $y_min->setName('y_min')->setLabel('от');
        $y_max = new SP_Form_Field_InputText;
        $y_max->setName('y_max')->setLabel('до');

        // Высота
        $z_min = new SP_Form_Field_InputText;
        $z_min->setName('z_min')->setLabel('от');
        $z_max = new SP_Form_Field_InputText;
        $z_max->setName('z_max')->setLabel('до');

        // Добавление полей в форму
        $this->_form->addFields(array(
            $type,
            $x_min,
            $x_max,
            $y_min,
            $y_max,
            $z_min,
            $z_max,
        ));

        return $this->_form;
    }

}

// 		Rentuse
class DomstorGarageRentuseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorRentLivingConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Тип гаража
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип гаража')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Ширина
        $x_min = new SP_Form_Field_InputText;
        $x_min->setName('x_min')->setLabel('от');
        $x_max = new SP_Form_Field_InputText;
        $x_max->setName('x_max')->setLabel('до');

        // Длина
        $y_min = new SP_Form_Field_InputText;
        $y_min->setName('y_min')->setLabel('от');
        $y_max = new SP_Form_Field_InputText;
        $y_max->setName('y_max')->setLabel('до');

        // Высота
        $z_min = new SP_Form_Field_InputText;
        $z_min->setName('z_min')->setLabel('от');
        $z_max = new SP_Form_Field_InputText;
        $z_max->setName('z_max')->setLabel('до');

        // Добавление полей в форму
        $this->_form->addFields(array(
            $type,
            $x_min,
            $x_max,
            $y_min,
            $y_max,
            $z_min,
            $z_max,
        ));

        return $this->_form;
    }

}

// LAND
// 		Sale
class DomstorLandSaleFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Тип участка
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип участка:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Добавление полей в форму
        $this->_form->addFields(array(
            $type,
        ));

        return $this->_form;
    }

}

// 		Rent
class DomstorLandRentFilterBuilder extends DomstorLandSaleFilterBuilder {

    public function buildFilter() {
        $filter = parent::buildFilter();

        // Удаление полей не нужных для аренды
        $filter->deleteField('price');
        // Добавление полей стоимости аренды объекта
        DomstorRentLivingConstructor::add($filter);

        return $filter;
    }

}

// 		Purchase
class DomstorLandPurchaseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // Тип участка
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип участка:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Добавление полей в форму
        $this->_form->addFields(array(
            $type,
        ));

        return $this->_form;
    }

}

// 		Rentuse
class DomstorLandRentuseFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorRentLivingConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);


        // Тип участка
        $type = new SP_Form_Field_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object=' . $this->_object . '&ref_city=' . $this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('Тип участка')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Добавление полей в форму
        $this->_form->addFields(array(
            $type,
        ));

        return $this->_form;
    }

}

// COMMERCE
class DomstorCommerceSaleFilterBuilder extends DomstorCommonBuilder {

    public function buildFilter() {
        // Добавление полей цены объекта
        DomstorPriceConstructor::add($this->_form);

        // Добавление поля района (зависит от типа местоположения)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if (!$this->_domstor->inRegion()) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // Назначение
        $purpose = new SP_Form_Field_CheckboxList;
        $options = array(
            '1002' => 'Торговое',
            '1003' => 'Офисное',
            '1005' => 'Производственное',
            '1006' => 'Складское',
            '1009' => 'Земельный участок',
            '1008' => 'Имущественный комплекс',
            '1007' => 'Прочие',
        );
        $purpose->setName('purpose')
            ->setLabel('Назначение:')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
            ->isDropDown(FALSE)
        ;

        // Площадь участка
        DomstorSquareGroundConstructor::add($this->_form);

        // Площадь помещения
        DomstorSquareHouseConstructor::add($this->_form);

        // Добавление полей в форму
        $this->_form->addFields(array(
            $purpose,
        ));

        return $this->_form;
    }

}

class DomstorCommerceRentFilterBuilder extends DomstorCommerceSaleFilterBuilder {

    public function buildFilter() {
        $filter = parent::buildFilter();

        // Удаление полей не нужных для аренды
        $filter->deleteField('price_min');
        $filter->deleteField('price_max');

        // Добавление полей стоимости аренды объекта
        $filter->addField(new DomstorRentForm);

        return $filter;
    }

}

class DomstorCommercePurchaseFilterBuilder extends DomstorCommerceSaleFilterBuilder {
    
}

class DomstorCommerceRentuseFilterBuilder extends DomstorCommerceRentFilterBuilder {
    
}
