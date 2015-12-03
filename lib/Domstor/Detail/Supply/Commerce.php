<?php
/**
 * Description of Commerce
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Commerce extends Domstor_Detail_Supply
{
	public function getPageTitle()
	{
		$a = &$this->object;
		$out = $this->getTitle();
		$out.= $this->getIf(strtolower($this->getPurpose()), ' (назначение: ', ')');

		$out.= $this->getIf( $a['Agency']['name'], ' &mdash; ');
		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;
		$out = $this->getOfferType2().' нежилое помещение ';

		if( $a['city'] ) $out.= 'в '.$this->getVar('city');

		$addr = $this->getStreetBuilding();
		$district = ($this->getVar('district_parent') == 'Пригород' or $this->getVar('district') == 'Пригород' )? ', '.$this->getVar('district') : '';
		$out.= $district.($addr? ', '.$addr : ($this->getVar('address_note')? ', '.$this->getVar('address_note') : '' ));

		return $out;
	}

	public function getSquareGround()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit'), 'Площадь земельного участка ');
	}

	public function getSquareHouse()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' кв.м',  'Площадь помещений ');
	}

	public function getSquare()
	{
		$a=&$this->object;
		if( $a['Purposes'][1009] )
		{
			if( count($a['Purposes'])==1 )
			{
				$out.=$this->getSquareGround();
			}
			else
			{
				$out=$this->getIf($this->getSquareHouse(), '', ', ');
				$out.=$this->getSquareGround();
			}
		}
		else
		{
			$out=$this->getSquareHouse();
		}
		return $out;
	}

	public function getFloor($min, $max)
	{
		if( isset($min) )
		{
			if( isset($max) )
			{
				if($min==$max)
				{
					if( $min!='0' )
					{
						$out=$min;
					}
				}
				else
				{
					$out=$min.' &ndash; '.$max;
				}
			}
			else
			{
				$out=$min;
			}
		}
		else
		{
			if( isset($max) )
			{
				if( $max!='0' )
				{
					$out=$max;
				}
			}
		}
		return $out;
	}

	public function getObjectFloor($min, $max)
	{
		$min_flag = FALSE;
		$max_flag = FALSE;
		$out = '';

		if( isset($min) and $min != '' ) $min_flag = TRUE;
		if( isset($max) and $max != '' ) $max_flag = TRUE;

		if( $min_flag and $max_flag )
		{
			if( $min == $max )
			{
				$out = ($min == '0')? 'цоколь' : $min;
			}
			else
			{
				$out = 'от&nbsp;'.$min.' до&nbsp;'.$max;
				$out = str_replace('0', 'цоколя', $out);
			}
		}
		elseif( $min_flag or $max_flag )
		{
			if( $min_flag )
			{
				$out = 'от&nbsp;'.$min;
			}
			else
			{
				$out = 'до&nbsp;'.$max;
			}
			$out = str_replace('0', 'цоколя', $out);
		}

		return $out;
	}

	public function getAnnotation()
	{
		$a = $this->object;
		$annotation=$this->getOfferType($this->action);
		$annotation.=$this->getIf( $this->getPurpose(), ', ');
		if( $this->getVar('complex_id') )$annotation.=', В составе имущественного комплекса';
		$annotation.=$this->getIf( $this->getSquare(), ', ');
		$annotation.=$this->getIf( $this->getAddress(), ', ');
		$annotation.=$this->getIf( $this->getPrice(), ', ');
		$annotation.=$this->getIf( $this->getVar('note_addition'), ', ');
		return $annotation;
	}

	public function getDelayBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('delay_sale_dt') ) $out.='<p>Отсроченная продажа с '.date('d.m.Y', strtotime($this->getVar('delay_sale_dt'))).'</p>';
		if( $this->getVar('delay_rent_dt') ) $out.='<p>Отсроченная аренда с '.date('d.m.Y', strtotime($this->getVar('delay_rent_dt'))).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_delay">
					<h3>Отсроченное предложение</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getComplexBlock()
	{
		$a = $this->object;
        $out ='';
		if( isset($a['complex']) and $a['complex'] )
		{
			$this->object = $a['complex'];
			$out = 'Объект <a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">'.$this->object['code'].'</a>, '.$this->getAnnotation();
			$this->object = $a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complex">
					<h3>В составе имущественного комплекса</h3>'.
					$out.
				'</div>';
		}

		return $out;
	}

	public function getComplexObjectsBlock()
	{
		$a = $this->object;
        $out = '';
		if( isset($a['ComplexObjects']) and is_array($a['ComplexObjects']) )
		{
			foreach( $a['ComplexObjects'] as $object )
			{
				$this->object=$object;
				$out.='<p><a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">Объект '.$object['code'].'</a>, '.$this->getAnnotation().'</p>';
			}
			$this->object=$a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complexobj">
					<h3>Объекты имущественного комплекса</h3>'.
					$out.
				'</div>';
		}

		return $out;
	}

	public function getPurposeBlock()
	{
		$a = &$this->object;
        $out = '';

		if( isset($a['Purposes'][1009]) or isset($a['class']) )
		{
			$purp=array();
			for($i=1013; $i<1022; $i++)
			{
				if( !empty($a['Purposes'][$i]) ) $purp[]=$a['Purposes'][$i];
			}
			$out=implode(', ', $purp);
			$out=$this->getElementIf('Земельный участок:', $out);
			$out.=$this->getElementIf('Класс объекта:', $this->getVar('class'));
			if( $out )
			{
				$out='<div class="domstor_object_purpose">
						<h3>Назначение</h3><table>'.
						$out.
					'</table></div>';
			}
		}
		return $out;
	}

	public function getSizeBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('Площадь помещений:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' кв.м') );
		$out.=$this->getElementIf('Площадь земельного участка:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')) );
		$out.=$this->getElementIf('Высота помещений:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' м') );
		$out.=$this->getElementIf('Количество ворот:', $this->getVar('gate_count'));
		$out.=$this->getElementIf('Максимальная высота ворот:', $this->getVar('gate_height'), ' м');
		$out.=$this->getElementIf('Максимальная ширина ворот:', $this->getVar('gate_width'), ' м');
		$out.=$this->getElementIf('Количество входов:', $this->getVar('door_count'));
		$out.=$this->getElementIf('Количество загрузочных окон:', $this->getVar('load_window_count'));

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

	public function getAllocationSubBlock()
	{
		$a=&$this->object;
		if( $this->getVar('placing_features_id')==-1001 ) return;
		if( $this->getVar('placing_features_id')==1116 )
		{
			$name='С отдельным входом';
		}
		else
		{
			$name=$this->getVar('placing_features');
		}
		$pt=array();
        $inside = $out = '';
		if( $this->getVar('placing_type') ) $pt[]=$this->getVar('placing_type');
		if( $this->getVar('placing_type2') ) $pt[]=$this->getVar('placing_type2');
		$pt=implode(', ', $pt);
		if( $pt ) $pt=' ('.$pt.')';
		if( $this->getVar('inside_building') ) $inside=', Помещение внутри здания ('.$this->getVar('inside_building').')';
		$out=$this->getElementIf('Особенности размещения:', $name.$pt.$inside);
		return $out;
	}

	public function getAllocationBlock()
	{
        $out = '';
		$out.=$this->getElementIf('Этаж объекта:',$this->getObjectFloor($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
		$out.=$this->getElementIf('Этажность здания:', $this->getFromTo($this->getVar('building_floor_min'), $this->getVar('building_floor_max')) );
		if( $this->getVar('ground_floor') ) $out.=$this->getElement('', 'Имеется цокольный этаж');
		$out.=$this->getAllocationSubBlock();
		if( $out )
		{
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
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('Телефонных линий:', $this->getVar('phone_count'));
		$out.=$this->getElementIf('Интернет-провайдеров:', $this->getVar('internet_count'));

        $electro = '';
		$electro.=$this->getElementIf($this->nbsp(4).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.=$this->getElementIf($this->nbsp(4).'Мощность', $this->getVar('electro_power'), ' кВт');
		$electro.=$this->getElementIf($this->nbsp(4).'Возможность увеличения мощности до', $this->getVar('electro_power_up'), ' кВт');
		if( $this->getVar('electro_reserve') ) $electro.=$this->getElement('', 'Есть резервная автономная подстанция');
		if( $this->getVar('electro_not') ) $electro.=$this->getElement('', 'Нет электричества');
		if( $this->getVar('electro_allow') ) $electro.=$this->getElement('', 'Получена документация для подключения');
		if( $electro ) $electro = $this->getElement('Электроснабжение:', '').$electro;
		$out.=$electro;


		$out.=$this->getElementIf('Теплоснабжение:', $this->getVar('heat'));
		if( $this->getVar('heat_control') ) $out.=$this->getElementIf('', 'Регулируемый температурный режим');
		$out.=$this->getElementIf('Водоснабжение:', $this->getVar('water'));
		if( $this->getVar('water_reserve') ) $out.=$this->getElementIf('', 'Резервная скважина');
		$out.=$this->getElementIf('Канализация:', $this->getVar('sewerage'));
		$out.=$this->getElementIf('Вентиляция:', $this->getVar('ventilation'));
		$out.=$this->getElementIf('Газоснабжение:', $this->getVar('gas'));

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'Материал наружных стен:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал перекрытий:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал несущих конструкций:', $this->getVar('material_carrying'));
		$construction.=$this->getElementIf($this->nbsp(4).'Минимальный шаг колонн:', $this->getVar('pillar_step'), ' м');
		$construction.=$this->getElementIf($this->nbsp(4).'Покрытие полов:', $this->getVar('paul_coating'));
		$construction.=$this->getElementIf($this->nbsp(4).'Уклон полов:', $this->getVar('paul_bias'));
		$construction.=$this->getElementIf($this->nbsp(4).'Нагрузка на пол:', $this->getVar('paul_loading'), ' кг/кв.м');
		if( $construction )	$out.=$this->getElement('Конструкция строения:', '').$construction;

		//	состояние
        $state = '';
		$state.=$this->getElementIf($this->nbsp(4).'Год постройки:', $this->getVar('build_year'));
		$state.=$this->getElementIf($this->nbsp(4).'Процент износа: ', $this->getVar('wearout'), '%');
		$state.=$this->getElementIf($this->nbsp(4).'Состояние:', $this->getVar('state'));
		if( $state ) $out.=$this->getElement('Состояние объекта:', '').$state;

        $ice = '';
		$ice.=$this->getElementIf($this->nbsp(4).'Холодильное оборудование:', $this->getVar('refrigerator'));
		$ice.=$this->getElementIf($this->nbsp(4).'Температурный режим:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
		$ice.=$this->getElementIf($this->nbsp(4).'Объем камер:', $this->getVar('refrigerator_capacity'), ' куб.м');
		if( $ice ) $out.=$this->getElement('Холодильное оборудование:', '').$ice;

		$lifts = '';
        $lifts.=$this->getElementIf($this->nbsp(8).'Пассажирский лифт:', $this->getVar('lift_passenger'), $this->getIf($this->getVar('lift_passenger_weight'),', до ', ' кг'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Грузовой лифт:', $this->getVar('lift_cargo'), $this->getIf($this->getVar('lift_cargo_weight'),', до ', ' кг'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Эскалатор:', $this->getVar('escalator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Травалатор:', $this->getVar('travelator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Тельфер:', $this->getVar('telpher'), $this->getIf($this->getVar('telpher_weight'),', до ', ' кг'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Кран-балка:', $this->getVar('crane_beam'), $this->getIf($this->getVar('crane_beam_weight'),', до ', ' т'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Козловой кран:', $this->getVar('crane_trestle'), $this->getIf($this->getVar('crane_trestle_weight'),', до ', ' т'));

        $infra = '';
        if( $lifts ) $infra.=$this->getElement($this->nbsp(4).'Грузоподъемные устройства:', '').$lifts;
        $infra.=$this->getElementIf($this->nbsp(4).'Охрана:', $this->getVar('security'));
		$infra.=$this->getElementIf($this->nbsp(4).'Сигнализация:', $this->getVar('signalizing'));
		$infra.=$this->getElementIf($this->nbsp(4).'Система пожаротушения:', $this->getVar('fire_prevention'));
		$infra.=$this->getElementIf($this->nbsp(4).'Столовая:', $this->getVar('dinning'));
		$infra.=$this->getElementIf($this->nbsp(4).'Количество санузлов:', $this->getVar('toilet_count'));
		$infra.=$this->getElementIf($this->nbsp(4).'Технические особенности:', $this->getVar('technical_note'));
		if( $infra ) $out.=$this->getElement('Инфраструктура:', '').$infra;


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

	public function getTransportBlock()
	{
		$a=&$this->object;
        $transp = $out = '';
		$transp.=$this->getElementIf($this->nbsp(4).'Ж/д пути:', $this->getVar('realroad'));
		if( $this->getVar('realroad_not_active') ) $transp.=$this->getElementIf('', 'Не действующие');
		$transp.=$this->getElementIf($this->nbsp(4).'Протяженность путей:', $this->getVar('realroad_length'), ' м');
		$transp.=$this->getElementIf($this->nbsp(4).'Фронт выгрузки:', $this->getVar('realroad_load_length'), ' м');
		if( $this->getVar('pandus') ) $transp.=$this->getElement('', 'Пандус');
		$transp.=$this->getElementIf($this->nbsp(4).'Подъезд, разворот авто:', $this->getVar('road'));
		$transp.=$this->getElementIf($this->nbsp(4).'Парковка:', $this->getVar('parking'));
		if( $this->getVar('parking_underground') ) $transp.=$this->getElement($this->nbsp(4).'', 'Подземная');
		if( $this->getVar('parking_many_floor') ) $transp.=$this->getElement($this->nbsp(4).'', 'Многоярусная');
		if( $transp ) $out.=$this->getElement('Выгрузка, погрузка, парковка:','').$transp;

        $road = '';
		$road.=$this->getElementIf($this->nbsp(4).'Интенсивность транспортного потока:', $this->getVar('transport_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'Интенсивность пешеходного потока:', $this->getVar('people_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'Покрытие дорог:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));
		$road.=$this->getElementIf($this->nbsp(4).'Пропускная способность, полос:', $this->getVar('lanes_count'));
		if( $this->getVar('one_way_traffic') ) $road.=$this->getElement($this->nbsp(4).'Одностороннее движение', '');
		if( $road ) $out.=$this->getElement('Дорожные условия:','').$road;

		if( $out )
		{
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
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('Удаленность от автотрассы:', $this->getVar('remote_highway'));
		$out.=$this->getElementIf('Удаленность от ж/д узла:', $this->getVar('remote_realroad'));
		$out.=$this->getElementIf('Рельеф:', $this->getVar('relief'));
		$out.=$this->getElementIf('Наличие леса:', $this->getVar('forest'));
		$out.=$this->getElementIf('Объекты на участке:', $this->getVar('objects'));
		$out.=$this->getElementIf('Непосредственное окружение:', $this->getVar('territory'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getFinanceBlock()
	{
		$a = &$this->object;
		$out ='';

		$a['rent_m2_min'] = (float) $this->getVar('rent_m2_min');
		$a['rent_m2_max'] = (float) $this->getVar('rent_m2_max');
		$a['rent_full'] = (float) $this->getVar('rent_full');

		$a['price_m2_min'] = (float) $this->getVar('price_m2_min');
		$a['price_m2_max'] = (float) $this->getVar('price_m2_max');
		$a['price_full'] = (float) $this->getVar('price_full');

		$price_ground_unit = $this->getVar('price_m2_unit')? $a['price_m2_unit'] : 'кв.м';

		$price = '';
		$price.= $this->getIf($this->getFormatedPrice($a['price_full'], $this->getVar('price_currency')));

		if( $this->getVar('offer_parts') ) $price.= $this->getIf($this->getPriceFromTo($a['price_m2_min'], $a['price_m2_max'], $this->getVar('price_currency')), ' (', '/ '.$price_ground_unit.')' );

        if( $this->getVar('active_sale') )
            $out.=$this->getElementIf('Цена:', $price);

		$rent ='';
		$rent_ground_unit = $this->getVar('rent_m2_unit')? $a['rent_m2_unit'] : 'кв.м';
		$rent.= $this->getIf($this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));

		if( $this->getVar('active_rent') )
        {
            $out.= $this->getElementIf('Арендная ставка:', $this->getPriceFromTo($a['rent_m2_min'], $a['rent_m2_max'], $this->getVar('rent_currency'), $this->getVar('rent_period')), '/ '.$rent_ground_unit );
            $out.= $this->getElementIf($this->nbsp(4).'За весь объект:', $this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));
            $out.= $this->getElementIf('Коммунальные платежи:', $this->getVar('rent_communal_payment'));
        }

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
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
					$this->getIf(strtolower($this->getPurpose()), '<h2>Назначение: ', '</h2>').
                    $this->getSecondHead().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getDelayBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getComplexBlock();
		$out.=$this->getComplexObjectsBlock();
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