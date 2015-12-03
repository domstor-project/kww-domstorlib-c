<?php

/**
 * Description of DemandRooms
 *
 * @author pahhan
 */
class Domstor_List_Field_Flat_DemandRooms extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a=$this->getRow();
		$rooms=array();
		for($room=1; $room<6; $room++)
		{
			if( $a['room_count_'.$room] ) $rooms[]=$room;
		}
		$out=implode(', ', $rooms);
		return $out;
	}
}

