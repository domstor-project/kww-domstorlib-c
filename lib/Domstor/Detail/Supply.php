<?php

/**
 * Description of Supply
 *
 * @author pahhan
 */
abstract class Domstor_Detail_Supply extends Domstor_Detail_Common
{
	protected function getImageLink($src, $type)
	{
		//var_dump($src);
		$out='<a href="http://'.$this->getServerName().'/foto/'.$src.'" class="modal" rel="'.$type.'">
			<img src="http://'.$this->getServerName().'/foto/'.$src.'" alt="" />
		</a>
		';
		return $out;
	}

	public function getObjectCode()
	{
		return 'Объект '.$this->object['code'];
	}

	protected function getPhotoHtml($photos)
	{
		if( !empty($photos) )
		{
			$out='<div class="domstor_photo">';
			foreach($photos as $photo)
			{
				$out.=$this->getImageLink($photo, 1);
			}
			$out.='</div>';
		}
		return $out;
	}

	protected function getPlanHtml($photos)
	{
		if( !empty($photos) )
		{
			$out='<div class="domstor_plan">';
			foreach($photos as $photo)
			{
				$out.=$this->getImageLink($photo, 2);
			}
			$out.='</div>';
		}
		return $out;
	}

	protected function getMapHtml($photos)
	{
		if( !empty($photos) )
		{
			$out='<div class="domstor_map">';
			foreach($photos as $photo)
			{
				$out.=$this->getImageLink($photo, 3);
			}
			$out.='</div>';
		}
		return $out;
	}

	protected function getImagesHtml($object)
	{
        $out = '';
        if( isset($object['img_photo']) )
            $out = $this->getPhotoHtml($object['img_photo']);

        if( isset($object['img_plan']) )
            $out.= $this->getPlanHtml($object['img_plan']);

		if( $out ) $out='<div class="domstor_images">'.$out.'</div>';
		return $out;
	}

	public function getOfferType($action=null)
	{
		if( !$action ) $action=$this->_action;
		if( $action=='rent' )
		{
			$out='Аренда';
		}
		elseif( $action=='exchange' )
		{
			$out='Обмен';
		}
		else
		{
			$out='Продажа';
		}
		return $out;
	}

	public function getOfferType2($action = NULL)
	{
		if( !$action ) $action = $this->_action;
		if( $action == 'rent' )
		{
			$out='Сдается';
		}
		elseif( $action == 'exchange' )
		{
			$out='Обменяю';
		}
		else
		{
			$out='Продается';
		}
		return $out;
	}

	public function getDemandsBlock()
	{
		$obj = &$this->object;
        $out = '';
		if( $obj['Demands'] and $this->_action=='exchange')
		{
			$type=array(4=>'Квартира', 6=>'Дом');
			foreach( $obj['Demands'] as $a )
			{

				if( $a['data_class']==4 )
				{
					$href=$this->getExchangeFlatUrl($a['id']);
				}
				elseif( $a['data_class']==6 )
				{
					$href=$this->getExchangeHouseUrl($a['id']);
				}
				$annotation='<a href="'.$href.'" class="domstor_link">'.$a['code'].'</a> '.$type[$a['data_class']];
				$rooms = '';
				if( $a['new_building'] ) $annotation.=', Новостройка';
				for($room=1; $room<6; $room++)
				{
					if( $a['room_count_'.$room] ) $rooms.=$room.', ';
				}
				$rooms=substr($rooms, 0, -2);
				if( $rooms )  $annotation.=', '.$rooms.' комн.';
				if( $a['in_communal'] ) $annotation.=', (в коммуналке)';
				if( $a['object_floor_limit'] )  $annotation.=', '.$a['object_floor_limit'].' эт.';
				if( $a['district'] )  $annotation.=', '.$a['district'];

				$price=Domstor_Detail_Demand::getPriceFromTo($a['price_full_min'], $a['price_full_max'], $a['price_currency']);
				if( $price ) $annotation.=', '.$price;
				if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
				$out.='<p>'.$annotation.'</p>';
			}
		}
		if( $out )
		{
			$out='<div class="domstor_object_demands">
						<h3>Заявки</h3>'.$out.'
					</div>';
		}
		return $out;
	}

	public function getRoomCount($count)
	{
		$out ='';
        if( $count )
		{
			if( $count==1 )
				$room=' комната';
			elseif( $count<5 )
				$room=' комнаты';
			else
				$room=' комнат';
			$out=$count.$room;
		}
		return $out;
	}

	public function getSquares($house, $living, $kitchen)
	{
		if( $house or $living or $kitchen )
		{
			if( !$house ) $house='-';
			if( !$living ) $living='-';
			if( !$kitchen ) $kitchen='-';
			$out=$house.'/'.$living.'/'.$kitchen;
		}
		return $out;
	}

	public function getFloors()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('object_floor') or  $this->getVar('building_floor') )
		{
			$object = $this->getVar('object_floor');
			$building = $this->getVar('building_floor');
			if( !$object ) $object = '-';
			if( !$building ) $building = '-';
			$out = $object.'/'.$building;
		}
		return $out;
	}

	public function getStreetBuilding()
	{
		$a = &$this->object;
		$out = '';

		if(  $this->getVar('street') and  $this->getVar('street_id') )
		{
			$out = $a['street'];
			if(  $this->getVar('building_num') )
			{
				$out.= ', '.$a['building_num'];
				if(  $this->getVar('corpus') )
				{
					if( is_numeric($a['corpus']) )
					{
						$out.= '/'.$a['corpus'];
					}
					else
					{
						$out.= strtoupper($a['corpus']);
					}
				}
			}
		}

		return $out;
	}

    protected function getTitleAddress()
    {
        $out = '';

        if( !$this->in_region ) {
            if( $city = $this->getVar('city') ) {
                $out.= ' в '.$city;
            }
            else {
                $out.= ', '.$this->getVar('master_city');
            }
        }

        if( $this->in_region ) {
            $addr = $this->_getRegionAddress();
        }
        else {
            $addr = $this->_getCityAddress();
        }

        if( $addr ) $out.= ', '.$addr;

		return $out;
    }

	public function getAddress()
	{

        $out = '';
        if( $this->in_region )
        {
            $out = $this->getData('region');
            $addr = $this->_getRegionAddress();
            if( $addr ) $out.= ', '.$addr;
        }
        else
        {
            $out = $this->getData('master_city');
            $addr = $this->_getCityAddress();
            if( $addr ) $out.= ', '.$addr;
        }

        if( $note = $this->getVar('address_note') ) $out.= ', '.$note;
		if( $coop = $this->getVar('cooperative_name') ) $out.= ', '.$coop;

		return $out;
	}

    protected function _getRegionAddress()
    {
        $out = '';
        $district_t = new Domstor_Transformer_Supply_RegionDistrict();
        $address_t = new Domstor_Transformer_Supply_RegionAddress();
        $district = $district_t->get($this->getData());
        $address = $address_t->get($this->getData());

        if( $district ) $out.= $district.', ';
        if( $address ) $out.= $address.', ';
        return trim($out, ', ');
    }

    protected function _getCityAddress()
    {
        $out = '';
        $district_t = new Domstor_Transformer_Supply_CityDistrict();
        $address_t = new Domstor_Transformer_Supply_CityAddress();
        $district = $district_t->get($this->getData());
        $address = $address_t->get($this->getData());

        if( $district ) $out.= $district.', ';
        if( $address ) $out.= $address.', ';

        return trim($out, ', ');
    }

    public function getFormatedPrice($price, $price_currency, $period = NULL)
	{
		if( (float) $price )
		{
			$out = number_format($price, 0, ',', ' ');
			if( $price_currency ) $out.= ' '.$price_currency;
			if( $period ) $out.= ' '.$period;
			$out = str_replace(' ', '&nbsp;', $out);
			return $out;
		}
	}

	public function getPrice()
	{
		$a=&$this->object;
		if( $this->action=='rent' )
		{
			$out=$this->getFormatedPrice($a['rent_full'], $a['rent_currency'], $a['rent_period']);
		}
		else
		{
			$out=$this->getFormatedPrice($a['price_full'], $a['price_currency']);
		}
		return $out;
	}

	public function getLocationBlock()
	{
		$a = $this->getData();
		$location = $this->getAddress();
		if( $location )
		{
			$location='<p>'.$location.'</p>';
			if( isset($a['first_line']) and $a['first_line'] ) $location.='<p>Первая линия</p>';
			if( isset($a['metro']) and $a['metro'] ) $location.='<p>'.$a['metro'].'</p>';
			if( isset($a['available_bus']) and $a['available_bus'] ) $location.='<p>От остановки '.$a['available_bus'].' мин.</p>';
			if( isset($a['available_metro']) and $a['available_metro'] ) $location.='<p>От метро '.$a['available_metro'].' мин.</p>';
			if( isset($a['available_bus_to_metro']) and $a['available_bus_to_metro'] ) $location.='<p>Транспортом до метро '.$a['available_bus_to_metro'].' мин.</p>';
			if( isset($a['map_weblink']) and $a['map_weblink'] ) $location.='<p><a href="'.$a['map_weblink'].'" target="_blank">На карте</a></p>';
			if( isset($a['img_map']) and $map = $this->getMapHtml($a['img_map']) ) $location.= $map;
			if( isset($a['zone']) and $a['zone'] ) $location.='<p>Территориальная зона'.$a['zone'].'</p>';
			$location='
				<div class="domstor_object_place">
					<h3>Местоположение</h3>'.$location.'
				</div>'
			;
		}


		return $location;
	}

	public function getRealizationBlock()
	{
		$a = &$this->object;
        $out = '';

		if( empty($a['realization_way']) or $this->blockIsDisabled('realization')) {
            return $out;
        }

        if( $a['realization_way_id']==1183 or $a['realization_way_id']==1184 or $a['realization_way_id']==1185 )
        {
            $out.=$this->getElement('Способ реализации:', $a['realization_way']);
            $out.=$this->getElementIf('Начальная цена:', $this->getFormatedPrice($a['auction_initial_price'], $a['auction_currency']));
            $out.=$this->getElementIf('Сумма задатка:', $this->getFormatedPrice($a['auction_advance'], $a['auction_currency']));
            $out.=$this->getElementIf('Шаг аукциона:', $this->getFormatedPrice($a['auction_step'], $a['auction_currency']));
            $out.=$this->getElementIf('Тип аукциона:', $a['auction_type']);
            $date=strtotime($a['auction_dttm']);
            if( $date ) $out.=$this->getElement('Дата проведения:', date('d.m.Y', $date));
            if( $date ) $out.=$this->getElement('Время проведения:', date('H:i', $date));
            $out.=$this->getElementIf('Место проведения:', $a['auction_location']);
            $date=strtotime($a['auction_filing_start']);
            if( $date ) $out.=$this->getElement('Дата начал подачи заявок:', date('d.m.Y', $date));
            $date=strtotime($a['auction_filing_finish']);
            if( $date ) $out.=$this->getElement('Дата окончания подачи заявок:', date('d.m.Y', $date));
        }
        else
        {
            $out.=$this->getElement('', $a['realization_way']);
        }

        $out = '<div class="domstor_object_realization">
                <h3>Способ реализации:</h3>
                <table>'.$out.'</table>
            </div>';
		return $out;
	}

	public function getFinanceBlock()
	{
		$a = &$this->object;
        $out = '';

		if( $this->getVar('active_sale') and (float) $this->getVar('price_full') )
            $out.=$this->getElement('Цена:', $this->getFormatedPrice($a['price_full'], $a['price_currency']));

		if( $this->getVar('active_rent') and (float) $this->getVar('rent_full') )
            $out.=$this->getElement('Арендная ставка:', $this->getFormatedPrice($a['rent_full'], $a['rent_currency'], $a['rent_period']));

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

    public function getEntityType()
    {
        return 'Объект';
    }

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>Код объекта: %s</h3>';
        return sprintf($tmpl, $this->getCode());
    }
}