<?php

/**
 * Description of Flat
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Flat extends Domstor_Detail_Supply
{
    public function getPageTitle()
    {
        $a = &$this->object;

        $out = $this->getTitle();

        if (isset($a['Agency']) and isset($a['Agency']['name'])) {
            $out.=' &mdash; '.$a['Agency']['name'];
        }

        return $out;
    }

    public function getTitle()
    {
        $a = &$this->object;

        $rooms = array(
            1 => 'одно',
            2 => 'двух',
            3 => 'трех',
            4 => 'четырех',
            5 => 'пяти',
            6 => 'шести',
            7 => 'семи',
        );

        $out = $this->getOfferType2().' ';

        if (isset($rooms[$a['room_count']])) {
            $out.= $rooms[$a['room_count']].($this->_action=='exchange'? 'комнатную ' : 'комнатная ');
        }

        $out.= ($this->_action=='exchange'? 'квартиру' : 'квартира');

        $out.= $this->getTitleAddress();

        return $out;
    }

    public function getAnnotation()
    {
        $a=&$this->object;
        $annotation=$this->getOfferType($this->action);
        if ($a['new_building']) {
            $annotation.=', Новостройка';
        }
        if ($a['room_count']) {
            $annotation.=', '.$this->getRoomCount($a['room_count']);
        }
        if ($a['in_communal']) {
            $annotation.=' (в коммуналке)';
        }
        if ($a['flat_type']) {
            $annotation.=', '.$a['flat_type'];
        }
        $squares=$this->getSquares($a['square_house'], $a['square_living'], $a['square_kitchen']);
        if ($squares) {
            $annotation.=', '.$squares;
        }
        $floors=$this->getFloors($a['object_floor'], $a['building_floor']);
        if ($floors) {
            $annotation.=', '.$floors;
        }
        $address=$this->getAddress();
        if ($address) {
            $annotation.=', '.$address;
        }
        $price=$this->getPrice();
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
        $a=&$this->object;
        $room=$this->getRoomCount($a['room_count']);
        if ($room) {
            $room='<p>'.$room.'</p>';
        }
        if ($a['in_communal']) {
            $room.='<p>Комнаты в коммуналке</p>';
        }
        if ($a['in_pocket']) {
            $room.='<p>Есть карман</p>';
        }
        if ($a['Together']['id']) {
            $tgh=$a['Together'];
            $together='<a href="'.$this->getObjectUrl($tgh['id']).'" class="domstro_link">'.$tgh['code'].'</a>';
            if ($tgh['room_count']) {
                $together.=', '.$this->getRoomCount($a['room_count']);
            }
            $squares=$this->getSquares($tgh['square_house'], $tgh['square_living'], $tgh['square_kitchen']);
            if ($squares) {
                $together.=', '.$squares;
            }
            $price=$this->getFormatedPrice($tgh['price_full'], $tgh['price_currency']);
            if ($price) {
                $together.=', '.$price;
            }
            if ($a['Together']) {
                $room.='<p>Совместная продажа с соседней квартирой:<br />'.$this->nbsp(4).$together.'</p>';
            }
        }
        if ($room) {
            $room='<div class="domstor_object_rooms">
					<h3>Число комнат</h3>'.
                    $room.
                '</div>';
        }
        return $room;
    }

    public function getFloorsBlock()
    {
        $a=&$this->object;
        $out=$this->getFloors();
        if ($out) {
            $out='<p>'.$out.'</p>';
        }
        if ($a['ground_floor']) {
            $out.='<p>В здании имеется цокольный этаж</p>';
        }
        if ($a['first_floor_commerce']) {
            $out.='<p>Первые этажи нежилые</p>';
        }
        if ($out) {
            $out='<div class="domstor_object_floor">
					<h3>Этаж</h3>'.
                    $out.
                '</div>';
        }
        return $out;
    }

    public function getTypeBlock()
    {
        $a = &$this->object;
        $type = '';
        if ($this->getVar('flat_type')) {
            $type=$this->getElement('Тип квартиры:', $a['flat_type']);
        }
        if ($this->getVar('planning')) {
            $type.=$this->getElement('Планировка:', $a['planning']);
        }
        if ($this->getVar('building_material')) {
            $type.=$this->getElement('Материал строения:', $a['building_material']);
        }
        if ($type) {
            $type='<div class="domstor_object_type">
					<h3>Тип квартиры (здания)</h3>
					<table>'.$type.'</table>
				</div>';
        }
        return $type;
    }

    public function getSizeBlock()
    {
        $a = &$this->object;
        $square = $out = '';
        if (isset($a['height']) and $a['height']) {
            $out=$this->getElement('Высота потолков:', $a['height'], ' м.');
        }
        if (isset($a['floor_count']) and $a['floor_count'] > 1) {
            $out.=$this->getElement('Количество уровней:', $a['floor_count']);
        }
        if (isset($a['square_house']) and $a['square_house']) {
            $square.=$this->getElement($this->nbsp(4).'Общая:', $a['square_house']);
        }
        if (isset($a['square_living']) and $a['square_living']) {
            $square.=$this->getElement($this->nbsp(4).'Жилая:', $a['square_living']);
        }
        if (isset($a['square_kitchen']) and $a['square_kitchen']) {
            $square.=$this->getElement($this->nbsp(4).'Кухня:', $a['square_kitchen']);
        }
        if (isset($a['square_pocket']) and $a['square_pocket']) {
            $square.=$this->getElement($this->nbsp(4).'Карман:', $a['square_pocket']);
        }
        if ($square) {
            $square=$this->getElement('Площадь, кв.м.:', '').$square;
        }
        $out.= $square;
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

    public function getTechnicBlock()
    {
        $a = &$this->object;
        $out = '';
        $communications='';
        $show = false;

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
        if ($this->getVar('toilet')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Cанузел:', $a['toilet']);
        }
        if ($this->getVar('toilet_count')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Количество санузлов:', $a['toilet_count']);
        }
        if ($this->getVar('santech_year')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Год замены (установки) сантехники:', $a['santech_year']);
        }
        if ($this->getVar('santech_material')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Сантех. трубы:', $a['santech_material']);
        }
        if ($this->getVar('sewerage_material')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Трубы канализации:', $a['sewerage_material']);
        }
        if ($this->getVar('heat_battery')) {
            $san_tech.=$this->getElement($this->nbsp(4).'Батареи отопления:', $a['heat_battery']);
        }
        if ($san_tech) {
            $san_tech = $this->getElement('Санузел, сантехника, арматура:', '').$san_tech;
            $show=true;
        }

        $construction = '';
        if ($this->getVar('material_wall')) {
            $construction.=$this->getElement($this->nbsp(4).'Материал наружных стен:', $a['material_wall']);
        }
        if ($this->getVar('material_ceiling')) {
            $construction.=$this->getElement($this->nbsp(4).'Материал перекрытий:', $a['material_ceiling']);
        }
        if ($this->getVar('material_carying')) {
            $construction.=$this->getElement($this->nbsp(4).'Материал несущих конструкций:', $a['material_carying']);
        }
        if ($construction) {
            $construction = $this->getElement('Конструкция здания:', '').$construction;
            $show=true;
        }

        if ($this->getVar('balcony_count') == 1) {
            $balcony=' балкон';
        } elseif ($this->getVar('balcony_count')<5) {
            $balcony=' балкона';
        } else {
            $balcony=' балконов';
        }

        if ($this->getVar('loggia_count') == 1) {
            $loggia=' лоджия';
        } elseif ($this->getVar('loggia_count') < 5) {
            $loggia=' лоджии';
        } else {
            $loggia=' лоджий';
        }

        $balc_log = '';
        if ($this->getVar('balcony_count')) {
            $balc_log.=$this->getElement($this->nbsp(4).'Количество балконов:', $a['balcony_count']);
        }
        if ($this->getVar('loggia_count')) {
            $balc_log.=$this->getElement($this->nbsp(4).'Количество лоджий:', $a['loggia_count']);
        }
        if ($this->getVar('balcony_arrangement')) {
            $balc_log.=$this->getElement($this->nbsp(4).'Обустройство:', $a['balcony_arrangement']);
        }
        if ($balc_log) {
            $balc_log = $this->getElement('Балкон, лоджия:', '').$balc_log;
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
        $door_front_material = $this->getVar('door_front_material')? ', '.$this->getVar('door_front_material') : '';
        if ($this->getVar('door_front')) {
            $doors.=$this->getElement($this->nbsp(4).'Входная дверь:', $a['door_front'].$door_front_material);
        }
        if ($this->getVar('door_pocket_material')) {
            $doors.=$this->getElement($this->nbsp(4).'Дверь в карман:', $a['door_pocket_material']);
        }
        if ($doors) {
            $doors = $this->getElement('Двери:', '').$doors;
            $show=true;
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
                        $construction.
                        $balc_log.
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
        if ($this->getVar('furniture')) {
            $out.= $this->getElement('Мебель:', $this->getVar('furniture'));
        }
        if ($this->getVar('household_technique')) {
            $out.= $this->getElement('Бытовая техника:', $this->getVar('household_technique'));
        }
        if ($this->getVar('in_corner')) {
            $out.=$this->getElement('Расположение:', 'угловая квартира');
        }

        $window_direction = '';
        if ($this->getVar('window_to_south')) {
            $window_direction='юг, ';
        }
        if ($this->getVar('window_to_north')) {
            $window_direction.='север, ';
        }
        if ($this->getVar('window_to_west')) {
            $window_direction.='запад, ';
        }
        if ($this->getVar('window_to_east')) {
            $window_direction.='восток, ';
        }
        if ($window_direction) {
            $window_direction=substr($window_direction, 0, -2);
            $out.=$this->getElement('Расположение окон:', $window_direction);
        }

        $parking = '';
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

        $lifts = '';
        if ($this->getVar('lift_count') == 1) {
            $lift=' лифт';
        } elseif ($this->getVar('lift_count') < 5) {
            $lift=' лифта';
        } else {
            $lift=' лифтов';
        }
        if ($this->getVar('lift_count')) {
            $lifts=$a['lift_count'].$lift.', ';
        }
        if ($this->getVar('lift_cargo')) {
            $lifts.='есть грузовой, ';
        }
        if ($lifts) {
            $lifts=substr($lifts, 0, -2);
            $lifts = $this->getElement('Лифты', $lifts);
            $out.=$lifts;
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
                '</div>'.
                $this->getImagesHtml($this->object).
                '<div class="domstor_object_common">'.
                    $this->getLocationBlock().
                    $this->getRoomsBlock().
                    $this->getFloorsBlock().
                '</div>';
        $out.=$this->getRealizationBlock();
        $out.=$this->getTypeBlock();
        $out.=$this->getSizeBlock();
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
