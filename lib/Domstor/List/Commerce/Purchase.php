<?php

/**
 * Description of Purchase
 *
 * @author pahhan
 */
class Domstor_List_Commerce_Purchase extends Domstor_List_Demand
{
	protected $show_square_house=false;
	protected $show_square_ground=false;

	public function checkSquare()
	{
		foreach( $this->data as $a )
		{
			if( isset($a['Purposes'][1009]) and $a['Purposes'][1009] )
			{
				if( count($a['Purposes'])==1 )
				{
					$this->show_square_ground=true;
				}
				else
				{
					$this->show_square_ground=true;
					$this->show_square_house=true;
					return;
				}
			}
			else
			{
				$this->show_square_house=true;
			}
		}
	}

	public function __construct($attr)
	{
		parent::__construct($attr);

		$type_field = new Domstor_List_Field_Commerce_Purpose( array(
			'name'=>'type',
			'title'=>'Назначение',
			'css_class'=>'domstor_type',
			'sort_name'=>'sort-purpose',
			'position'=>100,
		) );

		$square_field = new Domstor_List_Field_Commerce_Square( array(
			'name'=>'square_house',
			'title'=>'Площадь',
			'css_class'=>'domstor_square_house',
			'sort_name'=>'sort-square',
			'position'=>232,
		) );

		$square_ground_field = new Domstor_List_Field_Commerce_SquareGround( array(
			'name'=>'square_ground',
			'title'=>'Площадь земельного участка',
			'css_class'=>'domstor_square_ground',
			'sort_name'=>'sort-groundsq',
			'position'=>233,
		) );

		$price_field = new Domstor_List_Field_Commerce_DemandPrice( array(
			'name'=>'price',
			'css_class'=>'domstor_price',
			'sort_name'=>'sort-price',
			'position'=>260,
			'action'=>$this->action,
		) );

		$this->checkSquare();
		$this->addField($type_field)
			 ->addField($price_field)
		;
		if( $this->show_square_house ) $this->addField($square_field);
		if( $this->show_square_ground ) $this->addField($square_ground_field);
	}
}