<?php

/**
 * Description of Sale
 *
 * @author pahhan
 */
class Domstor_List_Garage_Sale extends Domstor_List_Supply
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('type')->setTitle('Вид гаража');

		$placing_type_field = new HtmlTableField( array(
			'name'=>'placing_type',
			'title'=>'Вид размещения',
			'css_class'=>'domstor_placing_type',
			'position'=>101
		) );

		$material_wall_field = new HtmlTableField( array(
			'name'=>'material_wall',
			'title'=>'Материал стен',
			'css_class'=>'domstor_material_wall',
			'sort_name'=>'sort-wall',
			'position'=>102
		) );

		$size_field = new HtmlDelimitedTableField( array(
			'name'=>'size',
			'title'=>'Размеры,&nbsp;м<br />Ш&nbsp;х&nbsp;Д&nbsp;х&nbsp;В',
			'css_class'=>'domstor_size',
			'params' => array('size_x','size_y', 'size_z'),
			'delimiter' => '&nbsp;x&nbsp;',
			'dont_show_if' => '0',
			'position'=>232
		) );

		$cellar_field = new HtmlTableField( array(
			'name'=>'cellar',
			'title'=>'Погреб',
			'css_class'=>'domstor_cellar',
			'position'=>234
		) );

		$this->addField($placing_type_field)
			 ->addField($material_wall_field)
			 ->addField($size_field)
			 ->addField($cellar_field)
		;
	}
}
