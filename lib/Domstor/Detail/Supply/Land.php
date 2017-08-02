<?php
/**
 * Description of Land
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Land extends Domstor_Detail_Supply
{
    public function getPageTitle()
    {
        $a = $this->getData();

        $out = $this->getTitle();

        if ($a['Agency']['name']) {
            $out.=' &mdash; '.$a['Agency']['name'];
        }

        return $out;
    }

    public function getTitle()
    {
        $a = $this->getData();

        $out = $this->getOfferType2().' '.$this->getVar('land_type', 'земельный участок');

        $out.= $this->getTitleAddress();

        return $out;
    }

    public function getAnnotation()
    {
        $a=&$this->object;
        $annotation=$this->getOfferType($this->action);
        if ($a['land_type']) {
            $annotation.=', '.$a['land_type'];
        }
        if ($a['square_ground']) {
            $annotation.=', '.$a['square_ground'];
            if ($a['square_ground_unit']) {
                $annotation.=' '.$a['square_ground_unit'];
            }
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

    public function getSizeBlock()
    {
        $out = '';
        $out.= $this->getElementIf('Площадь:', $this->getVar('square_ground'), ' '.$this->getVar('square_ground_unit'));

        if ($this->getVar('size_ground_x') and $this->getVar('size_ground_y')) {
            $out.=$this->getElement('По периметру:', $this->getVar('size_ground_x').' x '.$this->getVar('size_ground_y').' м');
        }

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
        $a=&$this->object;
        $out = '';
        $out.= $this->getElementIf('Тип постройки:', $this->getVar('living_building'));
        $out.= $this->getElementIf('Число жилых комнат:', $this->getVar('room_count'));
        $out.= $this->getElementIf('Количество этажей:', $this->getVar('building_floor'));
        $out.= $this->getElementIf('Площадь:', $this->getVar('square_house'));
        $out.= $this->getElementIf('Отопление:', $this->getVar('heat'));
        $out.= $this->getElementIf('Состояние построек:', $this->getVar('state'));

        $other = '';
        if ($this->getVar('bath_house')) {
            $other.= 'Баня, ';
        }
        if ($this->getVar('swimming_pool')) {
            $other.= 'Бассейн, ';
        }
        if ($this->getVar('garage')) {
            $other.= 'Гараж, ';
        }
        if ($this->getVar('other_building')) {
            $other.= 'Прочие, ';
        }
        if ($other) {
            $other= substr($other, 0, -2);
            $out.= $this->getElement('Хозяйственные постройки:', $other);
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
        $out = '';

        $electro = '';
        $electro.= $this->getElementIf($this->nbsp(8).'Напряжение', $this->getVar('electro_voltage'), ' В');
        $electro.= $this->getElementIf($this->nbsp(8).'Мощность', $this->getVar('electro_power'), ' кВт');
        if ($this->getVar('electro_reserve')) {
            $electro.= $this->getElement('', 'Резервная автономная подстанция');
        }
        if ($this->getVar('electro_not')) {
            $electro.= $this->getElement('', 'Нет электричества');
        }
        if ($electro) {
            $electro = $this->getElement($this->nbsp(4).'Электроснабжение:', '').$electro;
        }

        $show = false;
        $communications = $electro;
        $water = '';
        if ($this->getVar('water_basin')) {
            $water=' (Возможность водозабора из ближайшего водоема)';
        }
        $communications.= $this->getElementIf($this->nbsp(4).'Водоснабжение:', $this->getVar('water').$water);
        $communications.= $this->getElementIf($this->nbsp(4).'Год планируемого устройства коммуникаций:', $this->getVar('communications_year'));
        if ($communications) {
            $communications = $this->getElement('Коммуникации:', '').$communications;
            $show = true;
        }

        //	состояние
        $state = '';
        $state.= $this->getElementIf($this->nbsp(4).'Перепад высот:', $this->getVar('height_difference'), ' м');
        $state.= $this->getElementIf($this->nbsp(4).'Состав грунта:', $this->getVar('coat_structure'));
        $state.= $this->getElementIf($this->nbsp(4).'Высота подземных вод:', $this->getVar('ground_water_height'));
        $state.= $this->getElementIf($this->nbsp(4).'Наличие карстовых пустот:', $this->getVar('karstic_hole'));

        if ($state) {
            $state = $this->getElement('Состояние объекта:', '').$state;
            $show = true;
        }

        if ($show) {
            $out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
					<table>'.
                        $communications.
                        $state.
                    '</table>
				</div>';
        }
        return $out;
    }

    public function getFurnitureBlock()
    {
        $out = '';

        $out.= $this->getElementIf('Ограждение:', $this->getVar('fence'));
        $out.= $this->getElementIf('Удаленность от водоема:', $this->getVar('remote_water'), ' м');
        $out.= $this->getElementIf('Водоохранная зона:', $this->getVar('water_conservation_zone'), ' м');
        $out.= $this->getElementIf('Удаленность от лесного массива:', $this->getVar('remote_forest'), ' м');
        $out.= $this->getElementIf('Наличие лесопосадок на участке:', $this->getVar('forest_cover'));

        $road = '';
        $road.= $this->getElementIf($this->nbsp(4).'Удаленность участка от автотрассы:', $this->getVar('remote_highway'), ' м');
        $road.= $this->getElementIf($this->nbsp(4).'Покрытие подъездной дороги:', $this->getVar('road_covering'));
        $road.= $this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));
        $road.= $this->getElementIf($this->nbsp(4).'Возможность проезда зимой:', $this->getVar('road_winter'));

        if ($road) {
            $out.= $this->getElement('Дорожные условия:', '').$road;
        }
        $out.= $this->getElementIf('Вид территории поселения:', $this->getVar('settlement_type'));

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
                '</div>'.
                $this->getImagesHtml($this->object).
                '<div class="domstor_object_common">'.
                    $this->getLocationBlock().
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
