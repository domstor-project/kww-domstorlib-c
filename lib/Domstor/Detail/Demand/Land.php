<?php

/**
 * Description of Land
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_Land extends Domstor_Detail_Demand
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.= ' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getLocation()
	{
        $out = '';

		if( $this->in_region )
		{
			//if( $a['address_note'] ) $out.=$a['address_note'].', ';
			if( $this->getVar('cooperative_name') ) $out.= $this->getVar('cooperative_name').', ';
			if( $this->getVar('city') ) $out.= $this->getVar('city').', ';
			if( $this->getVar('region') ) $out.= $this->getVar('region').', ';
		}
		else
		{
			if( $this->getVar('street') ) $out.= $this->getVar('street').', ';
			if( $this->getVar('cooperative_name') ) $out.= $this->getVar('cooperative_name').', ';
			if( $this->getVar('district') ) $out.= $this->getVar('district').', ';
			if( $this->getVar('city') ) $out.= $this->getVar('city').', ';
		}
		$out = substr($out, 0, -2);
		return $out;
	}

	public function getTitle()
	{
		$out = $this->getOfferType2().' ';
        $out.= $this->getVar('land_type', 'садовый участок');
        $city = $this->getVar('city');
        if( $city ) $out.= ' в '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $this->getVar('land_type') ) $annotation.=', '.$this->getVar('land_type');
		$annotation.= $this->getIf($this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')),', ');
		$location = $this->getLocation();
		if( $location ) $annotation.= ', '.$location;
		$price = $this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getSizeBlock()
	{
		$out = $this->getElementIf('Площадь:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')));
		if( $out )
		{
			$out = '<div class="domstor_object_size">
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
        $out = '';
		$out.=$this->getElementIf('Требуются жилые постройки:', $this->getVar('living_building'));
		$out.=$this->getElementIf('Отопление:', $this->getVar('heat_want'));
		$out.=$this->getElementIf('Состояние построек не менее чем:', $this->getVar('state'));

        $other = '';
		if( $this->getVar('bath_house') ) $other.='Баня, ';
		if( $this->getVar('swimming_pool') ) $other.='Бассейн, ';
		if( $this->getVar('garage') ) $other.='Гараж, ';
		if( $other )
		{
			$other = substr($other, 0, -2);
			$out.= $this->getElement('Хозяйственные постройки:', $other);
		}


		if( $out )
		{
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
		$electro.=$this->getElementIf($this->nbsp(4).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.=$this->getElementIf($this->nbsp(4).'Мощность не менее', $this->getVar('electro_power'), ' кВт');

		if( $electro ) $out = $this->getElement('Электроснабжение:', '').$electro;

		$out.= $this->getElementIf('Водоснабжение:', $this->getVar('water_want'));

		if( $out )
		{
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

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'Удаленность участка от автотрассы не более:', $this->getVar('remote_highway'), ' м');
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие подъездной дороги:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'Возможность проезда зимой:', $this->getVar('road_winter'));
		if( $road )
		{
			$out.= $this->getElement('Дорожные условия:','').$road;
		}
		$out.= $this->getElementIf('Вид территории поселения:', $this->getVar('settlement_type'));

		if( $out )
		{
			$out = '<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Заявка не найдена';
		$out = '	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
					//'<p>'.$this->getAnnotation().'</p>'.
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
				'</div>';
		$out.= $this->getSizeBlock();
		$out.= $this->getBuildingsBlock();
		$out.= $this->getTechnicBlock();
		$out.= $this->getFurnitureBlock();
		$out.= $this->getFinanceBlock();
		$out.= $this->getCommentBlock();
		$out.= $this->getContactBlock();
		$out.= $this->getDateBlock();
		$out.= $this->getNavigationHtml();
		return $out;
	}

}