<?php

/**
 * Description of Thumb
 *
 * @author pahhan
 */
class Domstor_List_Field_Thumb extends Domstor_List_Field_Common
{
	protected $object_href;

	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = '';
		$href=str_replace('%id', $a['id'], $this->object_href);
		if( isset($a['thumb']) )
		{
			$out = '<img src="http://'.$this->getTable()->getServerName().'/'.$a['thumb'].'" alt="" />';
			$out = '<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}
		return $out;
	}
}



