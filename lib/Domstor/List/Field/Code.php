<?php

/**
 * Description of Code
 *
 * @author pahhan
 */
class Domstor_List_Field_Code extends Domstor_List_Field_Common
{
	protected $object_href;

	public function getValue()
	{
		$a=$this->getTable()->getRow();
		$href=str_replace('%id', $a['id'], $this->object_href);
		$out='<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$a['code'].'</a>';
		return $out;
	}
}



