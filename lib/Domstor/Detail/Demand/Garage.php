<?php

/**
 * Description of Garage
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_Garage extends Domstor_Detail_Demand
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
        $out = $this->getOfferType2().' ';
        $out.= $this->getVar('garage_type', 'гараж');
        $city = $this->getVar('city');
        if ($city) {
            $out.= ' в '.$city;
        }
        return $out;
    }

    public function getAnnotation()
    {
        $a=&$this->object;
        $annotation=$this->getOfferType($this->action);
        if ($a['garage_type']) {
            $annotation.=', '.$a['garage_type'];
        }
        if ($a['size_x'] and $a['size_y']) {
            $annotation.=', не менее '.$a['size_x'].' x '.$a['size_y'].' м';
        }
        $location=$this->getAddress();
        if ($location) {
            $annotation.=', '.$location;
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

    public function getAllocationBlock()
    {
        $a=&$this->object;
        if ($a['placing_type']) {
            $out='<p>'.$a['placing_type'].'</p>';
        }
        if ($out) {
            $out='<div class="domstor_object_rooms">
					<h3>Расположение</h3>'.
                    $out.
                '</div>';
        }
        return $out;
    }

    public function getSizeBlock()
    {
        $out = '';
        $out.= $this->getElementIf('Ширина не менее:', $this->getVar('size_x'), ' м');
        $out.= $this->getElementIf('Длина не менее:', $this->getVar('size_y'), ' м');
        $out.= $this->getElementIf('Высота не менее:', $this->getVar('size_z'), ' м');
        $out.= $this->getElementIf('Площадь не менее:', $this->getVar('square'), ' кв.м.');

        $gate_size = '';
        $gate_size.= $this->getElementIf($this->nbsp(4).'Высота не менее:', $this->getVar('gate_height'), ' м');
        $gate_size.= $this->getElementIf($this->nbsp(4).'Ширина не менее:', $this->getVar('gate_width'), ' м');

        if ($gate_size) {
            $out.= $this->getElement('Размер ворот:', '').$gate_size;
        }

        $out.= $this->getElementIf('Количество машиномест:', $this->getFromTo($this->getVar('car_count_min'), $this->getVar('car_count_max')));
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
        $out = '';

        $out.= $this->getElementIf('Наличие погреба:', $this->getVar('cellar_want'));
        $out.= $this->getElementIf('Наличие индивидуальной смотровой ямы:', $this->getVar('repair_pit_want'));

        $electro = '';
        $electro.= $this->getElementIf($this->nbsp(8).'Напряжение', $this->getVar('electro_voltage'), ' В');
        $electro.= $this->getElementIf($this->nbsp(8).'Мощность не менее', $this->getVar('electro_power'), ' кВт');
        if ($electro) {
            $electro = $this->getElement($this->nbsp(4).'Электроснабжение:', '').$electro;
        }

        $communications = $electro;

        $communications.= $this->getElementIf($this->nbsp(4).'Теплоснабжение:', $this->getVar('heat_want'));
        $communications.= $this->getElementIf($this->nbsp(4).'Вентиляция:', $this->getVar('ventilation_want'));
        $communications.= $this->getElementIf($this->nbsp(4).'Охранная сигнализация:', $this->getVar('signalizing_want'));
        $communications.= $this->getElementIf($this->nbsp(4).'Видеонаблюдение:', $this->getVar('video_tracking_want'));
        $communications.= $this->getElementIf($this->nbsp(4).'Противопожарная сигнализация:', $this->getVar('fire_signalizing_want'));
        $communications.= $this->getElementIf($this->nbsp(4).'Система пожаротушения:', $this->getVar('fire_prevention_want'));

        if ($communications) {
            $communications = $this->getElement('Коммуникации:', '').$communications;
            $out.= $communications;
        }

        if ($this->getVar('state')) {
            $out.=$this->getElement('Состояние объекта не менее чем:', $this->getVar('state'));
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

    public function getFurnitureBlock()
    {
        $out = '';

        $out.= $this->getElementIf('Охрана территории:', $this->getVar('territory_security_want'));
        $out.= $this->getElementIf('Общая смотровая яма:', $this->getVar('public_repair_pit_want'));
        $out.= $this->getElementIf('Автосервис в кооперативе:', $this->getVar('auto_service_want'));
        $out.= $this->getElementIf('Автомойка в кооперативе:', $this->getVar('auto_washing_want'));

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
                    $this->getAllocationBlock().
                    //$this->getFloorsBlock().
                '</div>';
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
