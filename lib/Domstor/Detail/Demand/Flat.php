<?php
/**
 * Description of Flat
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_Flat extends Domstor_Detail_Demand
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

        $out = $this->getOfferType2().' ';

        $out.= str_replace('комн.', 'комнатную', $this->getRooms()).' ';
        $out.= 'квартиру в '.$a['city'];
        return $out;
    }

    public function getRooms()
    {
        $rooms = '';
        for ($room=1; $room<6; $room++) {
            if ($this->getVar('room_count_'.$room)) {
                $rooms.=$room.', ';
            }
        }
        $rooms=substr($rooms, 0, -2);
        if ($rooms) {
            $rooms.=' комн.';
        }
        return $rooms;
    }

    public function getAnnotation()
    {
        $a=&$this->object;
        $annotation=$this->getOfferType($this->action);
        if ($this->getVar('new_building')) {
            $annotation.=', Новостройка';
        }
        $rooms=$this->getRooms();
        if ($rooms) {
            $annotation.=', '.$rooms;
        }
        if ($this->getVar('in_communal')) {
            $annotation.=' (в коммуналке)';
        }
        if ($this->getVar('object_floor')) {
            $annotation.=', '.$a['object_floor'].' эт.';
        }
        $location=$this->getAddress();
        if ($location) {
            $annotation.=', '.$location;
        }
        $price=$this->getPrice($this->getVar('price_full'), $this->getVar('price_currency'), $this->getVar('rent_full'), $this->getVar('rent_period'), $this->getVar('rent_currency'));
        if ($price) {
            $annotation.=', '.$price;
        }
        if ($this->getVar('note_addition')) {
            $annotation.=', '.$a['note_addition'];
        }
        return $annotation;
    }

    public function getRoomsBlock()
    {
        $a = &$this->object;

        $rooms = '';
        for ($room=1; $room<6; $room++) {
            if ($this->getVar('room_count_'.$room)) {
                $rooms.=$room.' комн., ';
            }
        }
        $rooms=substr($rooms, 0, -2);

        if ($rooms) {
            $rooms='<p>'.$rooms.'</p>';
        }
        if ($this->getVar('in_communal')) {
            $rooms.='<p>Комнаты в коммуналке</p>';
        }
        if ($rooms) {
            $rooms='<div class="domstor_object_rooms">
					<h3>Число комнат</h3>'.
                    $rooms.
                '</div>';
        }
        return $rooms;
    }

    public function getFloorsBlock()
    {
        $a = &$this->object;
        $floor = '';
        if ($this->getVar('object_floor')) {
            $floor='<p>'.$a['object_floor'].' эт.</p>';
        }
        if ($this->getVar('object_floor_limit')) {
            $floor.='<p>'.$a['object_floor_limit'].'</p>';
        }
        if ($floor) {
            $floor='<div class="domstor_object_floor">
					<h3>Этаж</h3>'.
                    $floor.
                '</div>';
        }
        return $floor;
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
        $out = $this->getElementIf('Высота потолков:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' м'));

        $square = '';
        $square.=$this->getElementIf($this->nbsp(4).'Общая:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')));
        $square.=$this->getElementIf($this->nbsp(4).'Жилая:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')));
        $square.=$this->getElementIF($this->nbsp(4).'Кухня:', $this->getFromTo($this->getVar('square_kitchen_min'), $this->getVar('square_kitchen_max')));
        if ($square) {
            $sqaure=$this->getElement('Площадь, кв.м.:', '').$square;
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
        $a=&$this->object;
        $out = '';
        $communications = '';
        $communications.=$this->getElementIf($this->nbsp(4).'Телефон:', $this->getVar('phone_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Интернет:', $this->getVar('internet_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Кабельное ТВ:', $this->getVar('cable_tv_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Домофон:', $this->getVar('door_phone_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Газопровод:', $this->getVar('gas_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Спутниковое ТВ:', $this->getVar('satellite_tv_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Охранная сигнализация:', $this->getVar('signalizing_want'));
        $communications.=$this->getElementIf($this->nbsp(4).'Противопожарная сигнализация:', $this->getVar('fire_prevention_want'));

        if ($communications) {
            $out = $this->getElement('Коммуникации:', '').$communications;
        }

        $out.=$this->getElementIf($this->nbsp(4).'Состояние объекта не менее чем:', $this->getVar('state'));


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

    public function getFurnitureBlock()
    {
        $a=&$this->object;
        $out = '';
        $out.=$this->getElementIf('Наличие мебели:', $this->getVar('with_furniture_want'));
        $out.=$this->getElementIf('Грузовой лифт:', $this->getVar('lift_cargo_want'));
        $out.=$this->getElementIf('Парковка:', $this->getVar('parking'));
        $out.=$this->getElementIf('Квартира совместно с гаражом или паркоместом:', $this->getVar('sale_with_parking'));

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
            return 'Заявка не найдена';
        }
        $out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
                    //'<p>'.$this->getAnnotation().'</p>'.
                '</div>
				<div class="domstor_object_common">'.
                    $this->getLocationBlock().
                    $this->getRoomsBlock().
                    $this->getFloorsBlock().
                '</div>';
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
