<?php

/**
 * Description of Exchange
 *
 * @author pahhan
 */
class Domstor_List_Flat_Exchange extends Domstor_List_Flat_Sale
{
    protected $exchange_flat_href;
    protected $exchange_house_href;

    public function getExchangeFlatUrl($id)
    {
        $out=str_replace('%id', $id, $this->exchange_flat_href);
        return $out;
    }

    public function getExchangeHouseUrl($id)
    {
        $out=str_replace('%id', $id, $this->exchange_house_href);
        return $out;
    }

    protected function getRowHtml($classes = null)
    {
        $class = null;
        $demand_tr = '';
        $demands = $this->makeDemands();
        if ($demands) {
            $class = 'with_demand';
            $demand_tr = sprintf('<tr class="exchange_demand"><td></td><td class="zayav" colspan="%d">%s</td></tr>', count($this->fields)-1, $demands);
        }
        $parent = parent::getRowHtml($class);
        return $parent.$demand_tr;
    }

    protected function makeDemands()
    {
        $row = $this->row;
        $out = '';
        if (isset($row['FlatDemands']) and is_array($row['FlatDemands'])) {
            $demands = $row['FlatDemands'];
            foreach ($demands as $a) {
                $type=array(4=>'Квартира', 6=>'Дом');
                if ($a['data_class']==4) {
                    $href=$this->getExchangeFlatUrl($a['id']);
                } elseif ($a['data_class']==6) {
                    $href=$this->getExchangeHouseUrl($a['id']);
                }
                $annotation='<a href="'.$href.'" class="domstor_link">'.$a['code'].'</a> '.$type[$a['data_class']];
                $rooms = '';
                if ($a['new_building']) {
                    $annotation.=', Новостройка';
                }
                for ($room=1; $room<6; $room++) {
                    if ($a['room_count_'.$room]) {
                        $rooms.=$room.', ';
                    }
                }
                $rooms=substr($rooms, 0, -2);
                if ($rooms) {
                    $annotation.=', '.$rooms.' комн.';
                }
                if ($a['in_communal']) {
                    $annotation.=', (в коммуналке)';
                }
                if ($a['object_floor_limit']) {
                    $annotation.=', '.$a['object_floor_limit'].' эт.';
                }
                if ($a['district']) {
                    $annotation.=', '.$a['district'];
                }

                $price=Domstor_Detail_Demand::getPriceFromTo($a['price_full_min'], $a['price_full_max'], $a['price_currency']);
                if ($price) {
                    $annotation.=', '.$price;
                }
                if ($a['note_addition']) {
                    $annotation.=', '.$a['note_addition'];
                }
                $out.='<p>Заявка: '.$annotation.'</p>';
            }
        }
        
        if (isset($row['HouseDemands']) and is_array($row['HouseDemands'])) {
            $demands = $row['HouseDemands'];
            foreach ($demands as $a) {
                $href=$this->getExchangeHouseUrl($a['id']);
                $annotation='<a href="'.$href.'" class="domstor_link">'.$a['code'].'</a> '. 'Дом';
                if (isset($a['room_count_min']) && is_int($a['room_count_min']) && (int)($a['room_count_min'])>0)
                {
                     $annotation.=', '.$a['room_count_min'].' комн.';
                }
                $price=Domstor_Detail_Demand::getPriceFromTo($a['price_full_min'], $a['price_full_max'], 'р.');
                if ($price) {
                    $annotation.=', '.$price;
                }
                if ($a['note_addition']) {
                    $annotation.=', '.$a['note_addition'];
                }
                $out.='<p>Заявка: '.$annotation.'</p>';
            }
        }

        return $out;
    }
}
