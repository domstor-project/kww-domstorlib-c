<?php

/**
 * Description of Garage
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Garage extends Domstor_Detail_Supply
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( $a['Agency']['name'] ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$out = $this->getOfferType2().' ';

		$type = $a['garage_type']? strtolower($a['garage_type']) : 'гараж';
		$out.= $type.' ';

		$out.= $this->getTitleAddress();

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', '.$a['size_x'].' x '.$a['size_y'].' м';
		$address=parent::getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getAddress()
	{
		$out = parent::getAddress();
		$space = $out? ', ' : '';
		if( $this->getVar('cooperative_name') ) $out.= $space.$this->getVar('cooperative_name');
		return $out;
	}

	public function getAllocationBlock()
	{
        $floor = '';
		$floor.=$this->getElementIf('Вид размещения:', $this->getVar('placing_type'));
		$floor.=$this->getElementIf('Этаж расположения объекта:', $this->getVar('object_floor'), ' этаж');
		$floor.=$this->getElementIf('Этажей в строении:', $this->getVar('building_floor'));
		if( $floor )
		{
			$floor='<div class="domstor_object_allocation">
					<h3>Расположение</h3><table>'.
					$floor.
				'</table></div>';
		}
		return $floor;
	}

	public function getSizeBlock()
	{
		$out = '';
        $out.= $this->getElementIf('Ширина:', $this->getVar('size_x'), ' м' );
		$out.= $this->getElementIf('Длина:', $this->getVar('size_y'), ' м' );
		$out.= $this->getElementIf('Высота:', $this->getVar('size_z'), ' м' );
		$out.= $this->getElementIf('Площадь:', $this->getVar('square'), ' кв.м.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'Высота:', $this->getVar('gate_height'), ' м' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'Ширина:', $this->getVar('gate_width'), ' м' );
		if( $gate_size ) $out.= $this->getElement('Размер ворот:','').$gate_size;

		$out.= $this->getElementIf('Количество машиномест:', $this->getVar('car_count'));
		if( $out )
		{
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

		$cellar = $this->getElementIf('Погреб:', $this->getVar('cellar'));
		$repair_pit =$this->getElementIf('Индивидуальная смотровая яма:', $this->getVar('repair_pit'));
		$gate_type = $this->getElementIf('Тип ворот:', $this->getVar('gate_type'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.= $this->getElementIf($this->nbsp(8).'Мощность', $this->getVar('electro_power'), ' кВт');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', 'Резервная автономная подстанция');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', 'Нет электричества');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'Электроснабжение:', '').$electro;

		$communications = '';
		$communications.= $this->getElementIf($this->nbsp(4).'Теплоснабжение:', $this->getVar('heat'));
		$communications.= $this->getElementIf($this->nbsp(4).'Вентиляция:', $this->getVar('ventilation'));
		$communications.= $this->getElementIf($this->nbsp(4).'Охранная сигнализация:', $this->getVar('signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'Видеонаблюдение:', $this->getVar('video_tracking'));
		$communications.= $this->getElementIf($this->nbsp(4).'Противопожарная сигнализация:', $this->getVar('fire_signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'Система пожаротушения:', $this->getVar('fire_prevention'));

		$show = false;
        if( $communications )
		{
			$communications = $this->getElement('Коммуникации:', '').$communications;
			$show = true;
		}

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'Материал наружных стен:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал перекрытий:', $this->getVar('material_ceiling'));


		if( $construction )
		{
			$construction = $this->getElement('Конструкция строения:', '').$construction;
			$show=true;
		}

		//	состояние
        $state = '';
		if( $this->getVar('build_year') ) $state.= $this->getElement($this->nbsp(4).'Год постройки:', $this->getVar('build_year'));
		if( $this->getVar('wearout') ) $state.= $this->getElement($this->nbsp(4).'Процент износа: ', $this->getVar('wearout').'%');
		if( $this->getVar('state') ) $state.= $this->getElement($this->nbsp(4).'Состояние:', $this->getVar('state'));
		if( $state )
		{
			$state = $this->getElement('Состояние объекта:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out = '<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
					<table>'.
						$cellar.
						$repair_pit.
						$gate_type.
                        $electro.
						$communications.
						$construction.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a=&$this->object;
        $out = '';

		$out.= $this->getElementIf('Охрана территории:', $this->getVar('territory_security'));
		$out.= $this->getElementIf('Общая смотровая яма:', $this->getVar('public_repair_pit'));
		$out.= $this->getElementIf('Автосервис в кооперативе:', $this->getVar('auto_service'));
		$out.= $this->getElementIf('Автомойка в кооперативе:', $this->getVar('auto_washing'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие проездов в кооперативе:', $this->getVar('road_covering_inside'));
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие дорог на подъезде к кооперативу:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));

		if( $road )
		{
			$out.=$this->getElement('Дорожные условия:','').$road;
		}


		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}

		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Объект не найден';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
				'</div>';
		$out.=$this->getAllocationBlock();
		$out.=$this->getRealizationBlock();
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