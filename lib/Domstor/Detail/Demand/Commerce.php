<?php

/**
 * Description of Commerce
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_Commerce extends Domstor_Detail_Demand
{
    public function getPageTitle()
    {
        $a = &$this->object;
        $out = $this->getTitle();
        $out.= $this->getIf($this->getPurpose(), ' (назначение: ', ')');
        $out.= $this->getIf($a['Agency']['name'], ' &mdash; ');
        return $out;
    }

    public function getTitle()
    {
        $out = $this->getOfferType2().' нежилое помещение';

        $city = $this->getVar('city');
        if ($city) {
            $out.= ' в '.$city;
        }
        return $out;
    }

    public function getSquareGround()
    {
        $a=&$this->object;
        return $this->getFromTo($a['square_ground_min'], $a['square_ground_max'], ' '.$a['square_ground_unit'], 'Площадь земельного участка ');
    }

    public function getSquareHouse()
    {
        $a=&$this->object;
        return $this->getFromTo($a['square_house_min'], $a['square_house_max'], ' кв.м', 'Площадь помещений ');
    }

    public function getSquare()
    {
        $a=&$this->object;
        if ($a['Purposes'][1009]) {
            if (count($a['Purposes'])==1) {
                $out.=$this->getSquareGround();
            } else {
                $out=$this->getIf($this->getSquareHouse(), '', ', ');
                $out.=$this->getSquareGround();
            }
        } else {
            $out=$this->getSquareHouse();
        }
        return $out;
    }

    public function getFloor($min, $max)
    {
        if (isset($min)) {
            if (isset($max)) {
                if ($min==$max) {
                    if ($min!='0') {
                        $out=$min;
                    }
                } else {
                    $out=$min.' &ndash; '.$max;
                }
            } else {
                $out=$min;
            }
        } else {
            if (isset($max)) {
                if ($max!='0') {
                    $out=$max;
                }
            }
        }
        return $out;
    }

    public function getFormatedPrice()
    {
        $out = '';
        $price = (float) $this->getVar('price_full');
        if ($price) {
            $out = number_format($price, 0, ',', ' ');
            $out.= $this->getIf($this->getVar('price_currency'), ' ');
            $out = str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getFormatedPriceM2()
    {
        $out = '';
        $price = (float) $this->getVar('price_m2');
        if ($price) {
            $out = number_format($this->getVar('price_m2'), 0, ',', ' ');
            $out.= $this->getIf($this->getVar('price_currency'), ' ');
            $out.= $this->getIf($this->getVar('price_m2_unit'), '/ ');
            $out = str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getFormatedRent()
    {
        $out = '';
        $rent = (float) $this->getVar('rent_full');
        if ($rent) {
            $out = number_format($rent, 0, ',', ' ');
            $out.= $this->getIf($this->getVar('rent_currency'), ' ');
            if ($this->getVar('rent_period')) {
                $out.=' '.$this->getVar('rent_period');
            }
            $out = str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getFormatedRentM2()
    {
        $out = '';
        $rent = (float) $this->getVar('rent_m2');
        if ($rent) {
            $out = number_format($this->getVar('rent_m2'), 0, ',', ' ');
            $out.= $this->getIf($this->getVar('rent_currency'), ' ');
            $out.= $this->getIf($this->getVar('rent_m2_unit'), '/ ');
            if ($this->getVar('rent_period')) {
                $out.=' '.$this->getVar('rent_period');
            }
            $out = str_replace(' ', '&nbsp;', $out);
        }
        return $out;
    }

    public function getPrice()
    {
        if ($this->action=='rent') {
            $rent=$this->getIf($this->getFormatedRent(), 'Не дороже ');
            $rent_m2=$this->getIf($this->getFormatedRentM2());
            if ($rent and $rent_m2) {
                $rent_m2=' ('.$rent_m2.')';
            }
            $out=$rent.$rent_m2;
        } else {
            $price=$this->getIf($this->getFormatedPrice(), 'Не дороже ');
            $price_m2=$this->getIf($this->getFormatedPriceM2());
            if ($price and $price_m2) {
                $price_m2=' ('.$price_m2.')';
            }
            $out=$price.$price_m2;
        }
        return $out;
    }

    public function getAnnotation()
    {
        $a=&$this->object;
        $annotation=$this->getOfferType($this->action);
        $annotation.=$this->getIf($this->getPurpose(), ', ');
        $annotation.=$this->getIf($a['complex'], ', ');
        $annotation.=$this->getIf($this->getSquare(), ', ');
        $annotation.=$this->getIf($this->getAddress(), ', ');
        $annotation.=$this->getIf($this->getPrice(), ', Не дороже ');
        $annotation.=$this->getIf($a['note_addition'], ', ');
        return $annotation;
    }

    public function getDelayBlock()
    {
        $a = &$this->object;
        $out = '';
        if (isset($a['delay_sale_dt'])) {
            $out.='<p>Отсроченная продажа с '.date('d.m.Y', strtotime($a['delay_sale_dt'])).'</p>';
        }
        if (isset($a['delay_rent_dt'])) {
            $out.='<p>Отсроченная аренда с '.date('d.m.Y', strtotime($a['delay_rent_dt'])).'</p>';
        }
        if ($out) {
            $out='<div class="domstor_object_delay">
					<h3>Отсроченное предложение</h3>'.
                    $out.
                '</div>';
        }
        return $out;
    }

    public function getPurposeBlock()
    {
        $out = '';

        $out.= $this->getElementIf('Требуемые назначения:', $this->getPurpose());
        $out.= $this->getElementIf('Предполагаемое использование объекта:', $this->getVar('use_plan'));
        $out.= $this->getElementIf('Разрешенный вид использования земельного участка:', $this->getVar('ground_use_allow'));
        $out.= $this->getElementIf('Класс объекта:', $this->getFromTo($this->getVar('class_min'), $this->getVar('class_max')));

        if ($out) {
            $out='<div class="domstor_object_purpose">
							<h3>Назначение</h3><table>'.
                            $out.
                        '</table></div>';
        }
        return $out;
    }

    public function getSizeBlock()
    {
        $out = '';

        $out.= $this->getElementIf('Площадь помещений:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' кв.м'));
        $out.= $this->getElementIf('Площадь земельного участка:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')));
        $out.= $this->getElementIf('Высота помещений:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' м'));
        $out.= $this->getElementIf('Количество ворот не менее:', $this->getVar('gate_count'));
        $out.= $this->getElementIf('Максимальная высота ворот не менее:', $this->getVar('gate_height'), ' м');
        $out.= $this->getElementIf('Максимальная ширина ворот не менее:', $this->getVar('gate_width'), ' м');

        if ($out) {
            $out='<div class="domstor_object_size">
					<h3>Размеры</h3>
					<table>'.
                    $out.
                    '</table>
			</div>';
        }
        return $out;
    }

    public function getAllocationSubBlock()
    {
        $out = '';

        $placing_features = array();

        if ($this->getVar('placing_separate_building')) {
            $placing_features[]='отдельно-стоящее здание';
        }
        if ($this->getVar('placing_separate_door')) {
            $placing_features[]='с отдельным входом в здании';
        }
        if ($this->getVar('placing_commerce_only')) {
            $placing_features[]='только нежилое здание';
        }
        if ($this->getVar('inside_building')) {
            $inside = 'Помещение внутри здания';
            if ($this->getVar('inside_building')) {
                $inside.=' ('.$this->getVar('inside_building').')';
            }
            $placing_features[]=$inside;
        }
        $out = $this->getElementIf('Особенности размещения:', implode(', ', $placing_features));
        return $out;
    }

    public function getAllocationBlock()
    {
        $out = '';
        $out.= $this->getElementIf('Этаж объекта:', $this->getFromTo($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
        $out.= $this->getElementIf('', $this->getVar('object_floor_limit'));
        $out.= $this->getAllocationSubBlock();
        if ($out) {
            $out='<div class="domstor_object_allocation">
					<h3>Размещение объекта</h3>
					<table>'.
                    $out.
                    '</table>
			</div>';
        }
        return $out;
    }

    public function getTechnicBlock()
    {
        $out = '';

        $out.= $this->getElementIf('Телефонных линий не менее:', $this->getVar('phone_count'));
        $out.= $this->getElementIf('Интернет-провайдеров:', $this->getVar('internet_want'));

        $electro = '';
        $electro.= $this->getElementIf($this->nbsp(4).'Напряжение', $this->getVar('electro_voltage'), ' В');
        $electro.= $this->getElementIf($this->nbsp(4).'Мощность не менее', $this->getVar('electro_power'), ' кВт');
        if ($electro) {
            $electro = $this->getElement('Электроснабжение:', '').$electro;
        }
        $out.= $electro;

        $out.= $this->getElementIf('Теплоснабжение:', $this->getVar('heat_want'));
        if ($this->getVar('heat_control')) {
            $out.= $this->getElementIf('', 'Регулируемый температурный режим');
        }
        $out.= $this->getElementIf('Водоснабжение:', $this->getVar('water_want'));
        $out.= $this->getElementIf('Вид водоснабжения:', $this->getVar('water'));
        if ($this->getVar('water_reserve')) {
            $out.= $this->getElementIf('', 'Резервная скважина');
        }
        $out.= $this->getElementIf('Канализация:', $this->getVar('sewerage_want'));
        $out.= $this->getElementIf('Вид канализации:', $this->getVar('sewerage'));
        $out.= $this->getElementIf('Газоснабжение:', $this->getVar('gas_want'));
        $out.= $this->getElementIf('Вид газоснабжения:', $this->getVar('gas'));

        $construction = '';
        $construction.= $this->getElementIf($this->nbsp(4).'Шаг колонн не менее:', $this->getVar('pillar_step'), ' м');
        $construction.= $this->getElementIf($this->nbsp(4).'Покрытие полов:', $this->getVar('paul_coating'));
        $construction.= $this->getElementIf($this->nbsp(4).'Уклон полов:', $this->getVar('paul_bias'));
        $construction.= $this->getElementIf($this->nbsp(4).'Нагрузка на пол не менее:', $this->getVar('paul_loading'), ' кг/кв.м');
        if ($construction) {
            $out.=$this->getElement('Конструкция строения:', '').$construction;
        }

        //	состояние
        $state = '';
        $state.=$this->getElementIf($this->nbsp(4).'Не менее чем:', $this->getVar('state'));
        if ($state) {
            $out.=$this->getElement('Состояние объекта:', '').$state;
        }


        $lift_pas_w = $lift_car_w = $telpher_w = $crane_beam_w = $crane_tres_w = '';
        if ($this->getVar('lift_passenger_weight')) {
            $lift_pas_w=' до '.$this->getVar('lift_passenger_weight').' кг';
        }
        if ($this->getVar('lift_cargo_weight')) {
            $lift_car_w=' до '.$this->getVar('lift_cargo_weight').' кг';
        }
        if ($this->getVar('telpher_weight')) {
            $telpher_w=' до '.$this->getVar('telpher_weight').' т';
        }
        if ($this->getVar('crane_beam_weight')) {
            $crane_beam_w=' до '.$this->getVar('crane_beam_weight').' т';
        }
        if ($this->getVar('crane_trestle_weight')) {
            $crane_tres_w=' до '.$this->getVar('crane_trestle_weight').' т';
        }

        $lifts = '';
        if ($this->getVar('lift_passenger')) {
            $lifts.= 'пассажирский лифт'.$lift_pas_w.', ';
        }
        if ($this->getVar('lift_cargo')) {
            $lifts.= 'грузовой лифт'.$lift_car_w.', ';
        }
        if ($this->getVar('escalator')) {
            $lifts.= 'эскалатор, ';
        }
        if ($this->getVar('travelator')) {
            $lifts.= 'травалатор, ';
        }
        if ($this->getVar('telpher')) {
            $lifts.= 'тельфер'.$telpher_w.', ';
        }
        if ($this->getVar('crane_beam')) {
            $lifts.= 'кран-балка'.$crane_beam_w.', ';
        }
        if ($this->getVar('crane_trestle')) {
            $lifts.= 'козловой кран'.$crane_tres_w.', ';
        }

        $lifts = substr($lifts, 0, -2);

        $infra = '';
        $infra.= $this->getElementIf($this->nbsp(4).'Необходимые грузоподъемные устройства:', $lifts);
        $infra.= $this->getElementIf($this->nbsp(4).'Санузел:', $this->getVar('toilet_want'));
        if ($infra) {
            $out.= $this->getElement('Инфраструктура:', '').$infra;
        }

        $ice = '';
        $ice.= $this->getElementIf($this->nbsp(4).'Холодильное оборудование:', $this->getVar('refrigerator_want'));
        $ice.= $this->getElementIf($this->nbsp(4).'Температурный режим:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
        $ice.= $this->getElementIf($this->nbsp(4).'Объем камер:', $this->getFromTo($this->getVar('refrigerator_capacity_max'), $this->getVar('refrigerator_capacity_min'), ' куб.м'));
        if ($ice) {
            $out.= $this->getElement('Холодильное оборудование:', '').$ice;
        }

        if ($out) {
            $out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
					<table>'.
                        $out.
                    '</table>
				</div>';
        }
        return $out;
    }

    public function getTransportBlock()
    {
        $out = '';

        $transp = '';
        $transp.= $this->getElementIf($this->nbsp(4).'Ж/д пути, ж/д тупик', $this->getVar('realroad_want'));
        $transp.= $this->getElementIf($this->nbsp(4).'Протяженность путей:', $this->getVar('realroad_length'), ' м');
        $transp.= $this->getElementIf($this->nbsp(4).'Фронт выгрузки:', $this->getVar('realroad_load_length'), ' м');
        $transp.= $this->getElementIf($this->nbsp(4).'Пандус:', $this->getVar('pandus_want'));
        $transp.= $this->getElementIf($this->nbsp(4).'Подъезд, разворот авто:', $this->getVar('road'));
        $transp.= $this->getElementIf($this->nbsp(4).'Парковка:', $this->getVar('parking'));
        if ($transp) {
            $out.=$this->getElement('Выгрузка, погрузка, парковка:', '').$transp;
        }

        $road = '';
        $road.= $this->getElementIf($this->nbsp(4).'Интенсивность транспортного потока:', $this->getVar('transport_stream'));
        $road.= $this->getElementIf($this->nbsp(4).'Интенсивность пешеходного потока:', $this->getVar('people_stream'));
        if ($road) {
            $out.= $this->getElement('Дорожные условия:', '').$road;
        }

        if ($out) {
            $out='<div class="domstor_object_transport">
					<h3>Транспортные условия</h3>
					<table>'.
                        $out.
                    '</table>
				</div>';
        }
        return $out;
    }

    public function getFurnitureBlock()
    {
        $out = '';

        $out.=$this->getElementIf('Удаленность от автотрассы:', $this->getVar('remote_highway'));
        $out.=$this->getElementIf('Удаленность от ж/д узла:', $this->getVar('remote_realroad'));
        $out.=$this->getElementIf('Рельеф:', $this->getVar('relief'));
        $out.=$this->getElementIf('Наличие леса:', $this->getVar('forest'));
        $out.=$this->getElementIf('Объекты на участке:', $this->getVar('objects'));
        $out.=$this->getElementIf('Непосредственное окружение:', $this->getVar('territory'));

        if ($out) {
            $out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
        }
        return $out;
    }

    public function getFinanceBlock()
    {
        $out = '';

        $price = $this->getIf($this->getFormatedPrice());
        $price_m2 = $this->getIf($this->getFormatedPriceM2());
        if ($price and $price_m2) {
            $price_m2=' ('.$price_m2.')';
        }

        if ($this->getVar('active_sale')) {
            $out.=$this->getElementIf('Бюджет:', $price.$price_m2, '', 'не более ');
        }

        if ($this->getVar('active_rent')) {
            $out.=$this->getElementIf('Бюджет:', $this->getFormatedRentM2(), '', 'не более ');
            $out.=$this->getElementIf($this->nbsp(4).'За весь объект:', $this->getFormatedRent(), '', 'не более ');
        }

        if ($out) {
            $out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
					<table>'.$out.'</table>
				</div>';
        }
        return $out;
    }

    public function getHtml()
    {
        if ($this->isEmpty()) {
            return 'Заявка не найдена';
        }
        $out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getIf(strtolower($this->getPurpose()), '<h2>Назначение: ', '</h2>').
                    $this->getSecondHead().
                '</div>
				<div class="domstor_object_common">'.
                    $this->getLocationBlock().
                    $this->getDelayBlock().
                '</div>';
        $out.=$this->getPurposeBlock();
        $out.=$this->getSizeBlock();
        $out.=$this->getAllocationBlock();
        $out.=$this->getTechnicBlock();
        $out.=$this->getTransportBlock();
        $out.=$this->getFurnitureBlock();
        $out.=$this->getFinanceBlock();
        $out.=$this->getCommentBlock();
        $out.=$this->getContactBlock();
        $out.=$this->getDateBlock();
        $out.=$this->getNavigationHtml();
        return $out;
    }
}
