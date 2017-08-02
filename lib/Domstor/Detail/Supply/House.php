<?php

/**
 * Description of House
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_House extends Domstor_Detail_Supply
{
    public function getPageTitle()
    {
        $a = &$this->object;

        $out = $this->getTitle();

        if ($a['Agency']['name']) {
            $out.=' &mdash; '.$a['Agency']['name'];
        }

        return $out;
    }

    public function getTitle()
    {
        $a = &$this->object;

        $out = $this->getOfferType2().' '.$this->getVar('house_type', 'дом');

        $out.= $this->getTitleAddress();

        return $out;
    }

    public function getAnnotation()
    {
        $a=&$this->object;
        $annotation=$this->getOfferType($this->action);
        if ($a['house_type']) {
            $annotation.=', '.$a['house_type'];
        }
        if ($a['room_count']) {
            $annotation.=', '.$this->getRoomCount($a['room_count']);
        }
        if ($a['square_house']) {
            $annotation.=', '.$a['square_house'].' кв.м.';
        }
        $address=$this->getAddress();
        if ($address) {
            $annotation.=', '.$address;
        }
        $price=$this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
        if ($price) {
            $annotation.=', '.$price;
        }
        if ($a['note_addition']) {
            $annotation.=', '.$a['note_addition'];
        }
        return $annotation;
    }

    public function getRoomsBlock()
    {
        $a = &$this->object;
        $room = '';
        if ($this->getVar('room_count')) {
            $room='<div class="domstor_object_rooms">
					<h3>Число комнат</h3><p>'.
                    $this->getRoomCount($a['room_count']).
                '</p></div>';
        }
        return $room;
    }

    public function getFloorsBlock()
    {
        $a = &$this->object;
        $floor = '';
        if ($this->getVar('building_floor')) {
            $floor = $a['building_floor'].' эт.';
            if ($this->getVar('ground_floor')) {
                $floor.=', цокольный этаж';
            }
            if ($this->getVar('mansard')) {
                $floor.=', мансарда';
            }
            if ($this->getVar('cellar')) {
                $floor.=', подвал';
            }
            $floor='<div class="domstor_object_floor">
					<h3>Этажи</h3><p>'.
                    $floor.
                '</p></div>';
        }
        return $floor;
    }

    public function getSizeBlock()
    {
        $a = &$this->object;
        $out = '';

        $square = '';
        if ($this->getVar('square_house')) {
            $square.=$this->getElement($this->nbsp(4).'Общая:', $a['square_house']);
        }
        if ($this->getVar('square_living')) {
            $square.=$this->getElement($this->nbsp(4).'Жилая:', $a['square_living']);
        }
        if ($this->getVar('square_kitchen')) {
            $square.=$this->getElement($this->nbsp(4).'Кухня:', $a['square_kitchen']);
        }
        if ($this->getVar('square_utility')) {
            $square.=$this->getElement($this->nbsp(4).'Подсобные помещения:', $a['square_utility']);
        }
        if ($square) {
            $sqaure=$this->getElement('Площадь, кв.м.:', '').$square;
        }
        $out.=$sqaure;

        $size = '';
        if ($this->getVar('size_house_x') and $this->getVar('size_house_y')) {
            $size.=$this->getElement($this->nbsp(4).'Периметр:', $a['size_house_x'].' x '.$a['size_house_y'].' м');
        }
        if ($this->getVar('size_house_z')) {
            $size.=$this->getElement($this->nbsp(4).'Высота под крышу:', $a['size_house_z'].' м');
        }
        if ($this->getVar('size_house_z_full')) {
            $size.=$this->getElement($this->nbsp(4).'Высота с крышей:', $a['size_house_z_full'].' м');
        }
        if ($size) {
            $size=$this->getElement('Размеры:', '').$size;
        }
        $out.=$size;

        $square_ground = '';
        $ground = '';
        if ($this->getVar('square_ground')) {
            $square_ground = $a['square_ground'].' '.strtolower($a['square_ground_unit']);
        } else {
            if ($this->getVar('square_ground_m2')) {
                $square_ground = $a['square_ground_m2'].' кв.м.';
            }
        }
        if ($square_ground) {
            $ground.=$this->getElement($this->nbsp(4).'Площадь:', $square_ground);
        }
        if ($this->getVar('size_ground_x') and $this->getVar('size_ground_y')) {
            $ground.=$this->getElement($this->nbsp(4).'Периметр, м.:', $a['size_ground_x'].' x '.$a['size_ground_y'].' м');
        }
        if ($ground) {
            $ground=$this->getElement('Земельный участок:', '').$ground;
        }
        $out.=$ground;

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

    public function getBuildingsBlock()
    {
        $a = &$this->object;
        $out = '';

        if ($this->getVar('bath_house')) {
            $out.=$this->getElement($this->nbsp(4).'Баня:', $a['bath_house']);
        }
        if ($this->getVar('swimming_pool')) {
            $out.=$this->getElement($this->nbsp(4).'Бассейн:', $a['swimming_pool']);
        }
        if ($this->getVar('garage')) {
            $out.=$this->getElement($this->nbsp(4).'Гараж:', $a['garage']);
        }
        if ($this->getVar('car_park_count')) {
            $out.=$this->getElement($this->nbsp(4).'Количество мест под автомобили:', $a['car_park_count']);
        }
        if ($this->getVar('other_building')) {
            $out.=$this->getElement($this->nbsp(4).'Прочие постройки:', $a['other_building']);
        }

        if ($out) {
            $out='<div class="domstor_object_buildings">
					<h3>Постройки</h3>
					<table>'.
                    $out.
                    '</table>
			</div>';
        }
        return $out;
    }

    public function getTechnicBlock()
    {
        $a = &$this->object;
        $out = '';
        $show = false;

        $communications = '';
        if ($this->getVar('phone')) {
            $communications.='телефон, ';
        }
        if ($this->getVar('internet')) {
            $communications.='интернет, ';
        }
        if ($this->getVar('cable_tv')) {
            $communications.='кабельное ТВ, ';
        }
        if ($this->getVar('door_phone')) {
            $communications.='домофон, ';
        }
        if ($this->getVar('gas')) {
            $communications.='газопровод, ';
        }
        if ($this->getVar('satellite_tv')) {
            $communications.='спутниковое ТВ, ';
        }
        if ($this->getVar('signalizing')) {
            $communications.='охранная сигнализация, ';
        }
        if ($this->getVar('fire_prevention')) {
            $communications.='противопожарная сигнализация, ';
        }
        if ($communications) {
            $communications=substr($communications, 0, -2);
            $communications = $this->getElement('Коммуникации:', $communications);
            $show=true;
        }

        $san_tech = '';
        if ($this->getVar('toilet_count')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Количество санузлов в доме:', $a['toilet_count']);
        }

        //электричество
        $electro = '';
        if ($this->getVar('electro_voltage')) {
            $electro=$this->getElement($this->nbsp(4).'Напряжение', $a['electro_voltage'].' В');
        }
        if ($this->getVar('electro_power')) {
            $electro=$this->getElement($this->nbsp(4).'Мощность', $a['electro_power'].' кВт');
        }
        if ($this->getVar('electro_reserve')) {
            $electro=$this->getElement('', 'Резервная автономная подстанция');
        }
        if ($this->getVar('electro_not')) {
            $electro=$this->getElement('', 'Нет электричества');
        }
        if ($electro) {
            $electro = $this->getElement('Электроснабжение:', '').$electro;
            $show=true;
        }

        $heat=$this->getElementIf('Теплоснабжение:', $a['heat']);
        $water=$this->getElementIf('Водоснабжение:', $a['water']);
        $sewerage=$this->getElementIf('Канализация:', $a['sewerage']);

        $construction = '';
        $construction.=$this->getElementIf($this->nbsp(4).'Материал наружных стен:', $this->getVar('material_wall'));
        $construction.=$this->getElementIf($this->nbsp(4).'Материал перекрытий:', $this->getVar('material_ceiling'));
        $construction.=$this->getElementIf($this->nbsp(4).'Материал несущих конструкций:', $this->getVar('material_carying'));
        $construction.=$this->getElementIf($this->nbsp(4).'Материал кровли:', $this->getVar('roof_material'));
        $construction.=$this->getElementIf($this->nbsp(4).'Тип кровли:', $this->getVar('roof_type'));
        $construction.=$this->getElementIf($this->nbsp(4).'Фундамент:', $this->getVar('foundation'));


        if ($construction) {
            $construction = $this->getElement('Конструкция здания:', '').$construction;
            $show=true;
        }

        $windows = '';
        if ($this->getVar('window_material')) {
            $windows.=$this->getElement($this->nbsp(4).'Материал рам:', $a['window_material']);
        }
        if ($this->getVar('window_glasing')) {
            $windows.=$this->getElement($this->nbsp(4).'Тип остекления:', $a['window_glasing']);
        }
        if ($this->getVar('window_opening')) {
            $windows.=$this->getElement($this->nbsp(4).'Тип открывания:', $a['window_opening']);
        }
        if ($windows) {
            $windows = $this->getElement('Окна:', '').$windows;
            $show=true;
        }

        $doors = '';
        if ($this->getVar('door_room')) {
            $doors.=$this->getElement($this->nbsp(4).'Двери межкомнатные:', $a['door_room']);
        }

        //	отделка
        $finish = '';
        if ($this->getVar('finish_ceiling')) {
            $finish.=$this->getElement($this->nbsp(4).'Потолки:', $a['finish_ceiling']);
        }
        if ($this->getVar('finish_paul')) {
            $finish.=$this->getElement($this->nbsp(4).'Полы: ', $a['finish_paul']);
        }
        if ($this->getVar('finish_partition')) {
            $finish.=$this->getElement($this->nbsp(4).'Перегородки: ', $a['finish_partition']);
        }
        if ($this->getVar('facade')) {
            $finish.=$this->getElement($this->nbsp(4).'Фасад: ', $a['facade']);
        }
        if ($finish) {
            $finish = $this->getElement('Отделка:', '').$finish;
            $show=true;
        }

        //	состояние
        $state = '';
        if ($this->getVar('build_year')) {
            $state.=$this->getElement($this->nbsp(4).'Год постройки:', $a['build_year']);
        }
        if ($this->getVar('wearout')) {
            $state.=$this->getElement($this->nbsp(4).'Процент износа: ', $a['wearout'].'%');
        }
        if ($this->getVar('state')) {
            $state.=$this->getElement($this->nbsp(4).'Состояние:', $a['state']);
        }
        if ($state) {
            $state = $this->getElement('Состояние объекта:', '').$state;
            $show=true;
        }

        if ($show) {
            $out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
					<table>'.
                        $communications.
                        $san_tech.
                        $electro.
                        $heat.
                        $water.
                        $sewerage.
                        $construction.
                        $windows.
                        $doors.
                        $finish.
                        $state.
                    '</table>
				</div>';
        }
        return $out;
    }

    public function getFurnitureBlock()
    {
        $a = &$this->object;
        $out = '';

        $obstanovka = '';
        if ($this->getVar('with_furniture')) {
            $obstanovka.='С мебелью, ';
        }
        if ($this->getVar('garden')) {
            $obstanovka.='Посадки, огород на участке, ';
        }
        if ($this->getVar('landscape_design')) {
            $obstanovka.='Ландшафтный дизайн, ';
        }
        if ($this->getVar('improvement_territory')) {
            $obstanovka.='Прилегающая территория благоустроена, ';
        }
        if ($obstanovka) {
            $obstanovka=substr($obstanovka, 0, -2);
            $out.=$this->getElement('Обстановка:', $obstanovka);
        }

        $out.=$this->getElementIf('Ограда:', $this->getVar('fence'));

        if ($this->getVar('garbage_chute')) {
            $out.=$this->getElement('Мусоропровод:', 'имеется');
        }
        if ($this->getVar('security')) {
            $out.=$this->getElement('Охрана:', $a['security']);
        }
        if ($this->getVar('sale_with_parking')) {
            $parking=', Возможна продажа совместно с гаражом или паркоместом';
        }
        if ($this->getVar('parking')) {
            $out.=$this->getElement('Парковка:', $a['parking'].$parking);
        }

        $road = '';
        $road.=$this->getElementIf($this->nbsp(4).'Покрытие дорог:', $this->getVar('road_covering'));
        $road.=$this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));
        if ($road) {
            $out.=$this->getElement('Дорожные условия:', '').$road;
        }


        if ($out) {
            $out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
        }
        return $out;
    }

    public function getHtml()
    {
        if ($this->isEmpty()) {
            return 'Объект не найден';
        }
        $out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
                    $this->getDemandsBlock().
                    /* '<p>'.
                        $this->getAnnotation()
                    .'</p> */
                '</div>'.
                $this->getImagesHtml($this->object).
                '<div class="domstor_object_common">'.
                    $this->getLocationBlock().
                    $this->getRoomsBlock().
                    $this->getFloorsBlock().
                '</div>';
        $out.=$this->getRealizationBlock();
        $out.=$this->getSizeBlock();
        $out.=$this->getBuildingsBlock();
        $out.=$this->getTechnicBlock();
        $out.=$this->getFurnitureBlock();
        $out.=$this->getFinanceBlock();
        $out.=$this->getCommentBlock();
        $out.=$this->getContactBlock();
        $out.=$this->getDateBlock();
        $out.=$this->getNavigationHtml();
        return $out;
    }
}
