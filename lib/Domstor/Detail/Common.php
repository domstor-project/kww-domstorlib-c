<?php

/**
 * Description of Common
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
abstract class Domstor_Detail_Common
{
    protected $object;
    protected $action; //изачально задумывался чтобы хранить значения rent или sale
    protected $_action; // но потом понадобились варианты sale, rent, purchase, rentuse ну и т.д
    protected $object_href;
    protected $exchange_flat_href;
    protected $exchange_house_href;
    protected $commerce_href;
    protected $id_placeholder;
    protected $server_name;
    protected $show_contact = true;
    protected $_show_agency = true;
    protected $in_region;
    protected $show_second_head = false;
    protected $block_disables = array();
    protected $tmpl_dir;

    public function getData($key = null)
    {
        if (is_null($key)) {
            return $this->object;
        }

        return $this->object[$key];
    }

    protected function _escapeData($data)
    {
        $escaped = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $escaped[$key] = $this->_escapeData($value);
            } elseif (is_string($value)) {
                $escaped[$key] = htmlspecialchars($value, ENT_COMPAT, 'WINDOWS-1251');
            } else {
                $escaped[$key] = $value;
            }
        }

        return $escaped;
    }

    public function setData($data)
    {
        $this->object = $this->_escapeData($data);
    }

    abstract public function getHtml();

    //реализована в domstorlib, но оказалось что может понадобиться и тут
    public function getNavigationHtml()
    {
        $prev = $this->getVar('prev_id');
        $next = $this->getVar('next_id');

        if ($this->_action == 'rentuse' or $this->_action == 'purchase') {
            $name = 'заявка';
            $text_end = 'ая';
        } else {
            $name = 'объект';
            $text_end = 'ий';
        }

        $prev_href = '';
        $prev_text = '';
        $next_href = '';
        $next_text = '';

        if ($prev) {
            $prev_href = str_replace($this->id_placeholder, $prev, $this->object_href);
            $prev_text = 'Предыдущ' . $text_end . ' ' . $name;
        }

        if ($next) {
            $next_href = str_replace($this->id_placeholder, $next, $this->object_href);
            $next_text = 'Следующ' . $text_end . ' ' . $name;
        }

        ob_start();
        include $this->tmpl_dir . '/navigation.html.php';
        $out = ob_get_contents();
        ob_end_clean();

        return $out;
    }

    public function __construct($attr = null)
    {
        $this->setVars($attr);
        if (!$this->tmpl_dir) {
            $this->tmpl_dir = dirname(__FILE__) . '/views';
        }
    }

    public function getVar($name, $default = null)
    {
        if (array_key_exists($name, $this->object) and $this->object[$name]) {
            return $this->object[$name];
        }

        return $default;
    }

    public function setVars($attr = null)
    {
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getTitle()
    {
        return 'Undefined title';
    }

    public function getCode()
    {
        return $this->object['code'];
    }

    public function getObjectUrl($id)
    {
        $out = str_replace('%id', $id, $this->object_href);
        return $out;
    }

    public function getCommerceUrl($id)
    {
        $out = str_replace('%id', $id, $this->commerce_href);
        return $out;
    }

    public function getExchangeFlatUrl($id)
    {
        $out = str_replace('%id', $id, $this->exchange_flat_href);
        return $out;
    }

    public function getExchangeHouseUrl($id)
    {
        $out = str_replace('%id', $id, $this->exchange_house_href);
        return $out;
    }

    protected function blockIsDisabled($name)
    {
        $key = array_search($name, $this->block_disables);
        return $key !== false;
    }

    public function enableBlock($name)
    {
        $key = array_search($name, $this->block_disables);
        if ($key !== false) {
            unset($this->block_disables[$key]);
        }
    }

    public function disableBlock($name)
    {
        if (!$this->blockIsDisabled($name)) {
            $this->block_disables[] = $name;
        }
    }

    public function render()
    {
        return $this->getHtml();
    }

    public function display()
    {
        echo $this->render();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function nbsp($count = 1)
    {
        $out = '';
        for ($i = 0; $i < $count; $i++) {
            $out.='&nbsp;';
        }
        return $out;
    }

    public function getElement($title, $value, $after = null, $before = null)
    {
        return '<tr><th>' . $title . '</th><td>' . $before . $value . $after . '</td></tr>';
    }

    public function getElementIf($title, $value, $after = null, $before = null)
    {
        if ($value) {
            return $this->getElement($title, $value, $after, $before);
        }
    }

    public function getIf($value, $before = null, $after = null)
    {
        if ($value) {
            return $before . $value . $after;
        }
    }

    public function getFromTo($from, $to, $after = null, $before = null)
    {
        $from_string = 'от&nbsp;';
        $to_string = 'до&nbsp;';
        $not_show = '0';
        $out = '';
        $space = '';
        if (($from !== $not_show and isset($from)) or ($to !== $not_show and isset($to))) {
            if ($from === $to) {
                $out = $from;
            } else {
                if ($from !== $not_show and isset($from)) {
                    $out.=$from_string . $from;
                    $space = ' ';
                }
                if ($to !== $not_show and isset($to)) {
                    $out.=$space . $to_string . $to;
                }
            }
            $out = $before . $out . $after;
        }
        return $out;
    }

    public function getPriceFromTo($from, $to, $currency, $period = null)
    {
        $from_string = 'от&nbsp;';
        $to_string = 'до&nbsp;';
        $out = '';
        $space = '';
        if ($from !== null or $to !== null) {
            if ($from != '0' or $to != '0') {
                if ($from == $to) {
                    $price = number_format($from, 0, ',', ' ');
                    $price = str_replace(' ', '&nbsp;', $price);
                    $period = $period ? '&nbsp;' . $period : '';
                    $out = $price . '&nbsp;' . $currency . $period;
                } else {
                    if ($from != '0') {
                        $price = number_format($from, 0, ',', ' ');
                        $price = str_replace(' ', '&nbsp;', $price);
                        $out.=$from_string . $price;
                        $space = ' ';
                    }

                    if ($to != '0') {
                        $price = number_format($to, 0, ',', ' ');
                        $price = str_replace(' ', '&nbsp;', $price);
                        $out.=$space . $to_string . $price;
                    }
                    $out.='&nbsp;' . $currency . '&nbsp;' . $period;
                }
            }
        }
        return $out;
    }

    public function showContact($value)
    {
        $this->show_contact = (bool) $value;
    }

    public function showAgency($value)
    {
        $this->_show_agency = (bool) $value;
    }

    public function showSecondHead($value)
    {
        $this->show_second_head = (bool) $value;
    }

    public function getSecondHead()
    {
        if (!$this->show_second_head) {
            return '';
        }

        $tmpl = '<h3>%s %s</h3>';
        return sprintf($tmpl, $this->getEntityType(), $this->getCode());
    }

    public function getCommentBlock()
    {
        $a = &$this->object;
        $out = '';
        if ($a['note_web']) {
            $out = '
			<div class="domstor_object_comments">
				<h3>Комментарий</h3>
				<p>' . $a['note_web'] . '</p>
			</div>';
        }
        return $out;
    }

    public function getContactBlock()
    {
        if (!$this->show_contact or $this->blockIsDisabled('contact')) {
            return '';
        }
        $a = &$this->object;
        $out = $space = '';
        if ($a['Agent']['tel_work'] and $a['Agent']['tel_sot']) {
            $space = ', ';
        }
        switch ($a['Agency']['tipcont']) {
            case '1':
                $phone = $a['Agency']['tel_cont'];
                $mail = $a['Agency']['mail_cont'];
                break;
            case '2':
                $phone = $a['Filial']['phone'];
                $mail = $a['Filial']['mail'];
                break;
            case '3':
                $phone = (isset($a['agent_phone']) && !empty($a['agent_phone'])) ? $a['agent_phone'] : $a['Agent']['tel_work'] . $space . $a['Agent']['tel_sot'];
                $mail = $a['Agent']['mail'];
                break;
            default:
                $out = '';
                break;
        }


        if (strlen($phone)>0)
        {
            $phone = str_replace(',', ', ', $phone);

            $words = explode(', ', $phone);

            foreach ($words as &$word)
            {
                $cleared = preg_replace('/[^0-9]/', '', $word);
                if (strlen($cleared) > 10)
                {
                    if ($cleared[0] === '8')
                    {
                        $cleared[0] = '7';
                    }
                    $word = sprintf('<a href="tel:+%s">+%s</a>', $word, $word);
                }
            }
            $phone = implode(', ', $words);
        }

        if (strlen($mail)>0)
        {
            $mail = sprintf('<a href="mailto:%s">%s', $mail, $mail);
        }


        if (!$a['Agency']['hide_agent'] or $a['Agency']['tipcont'] == '3') {
            $out.=$this->getIf($a['Agent']['name_as'], '<p>Агент: ', '</p>');
        }
        if ($this->_show_agency) {
            $out.=$this->getIf($a['Agency']['shotname'], '<p>Агентство: ', '</p>');
        }
        $out.=$this->getIf($phone, '<p>Телефон: ', '</p>');
        $out.=$this->getIf($mail, '<p>Эл. почта: ', '</p>');
        $edit_dt = '';
        if ($a['edit_dt']) {
            $edit_dt = '<p>Обновлено: ' . date('d.m.Y', strtotime($a['edit_dt'])) . '</p>';
        }
        $edit_dt.= '<p>Просмотров: ' . ((int) $a['view_count'] + 1) . '</p>';
        if ($out) {
            $out = '<div class="domstor_object_contacts">
					<h3>Контактная информация</h3>
					<table><tr>
						<td>' .
                $out .
                '</td>
						<td>' .
                $edit_dt .
                '</td>
					</tr></table>
				</div>';
        }
        return $out;
    }

    public function getDateBlock()
    {
        $out = '';
        return $out;
    }

    public function getPurpose()
    {
        $purp = $this->getVar('Purposes');
        $out = '';
        if ($purp) {
            if (isset($purp[1001])) {
                unset($purp[1002], $purp[1003]);
            }
            if (isset($purp[1004])) {
                unset($purp[1005], $purp[1006]);
            }
            if (isset($purp[1009])) {
                for ($i = 1013; $i < 1022; $i++) {
                    unset($purp[$i]);
                }
            }
            $out = implode(', ', $purp);
        }
        return $out;
    }

    public function isEmpty()
    {
        return empty($this->object['code']);
    }

    public function getServerName()
    {
        return $this->server_name;
    }

    public function setServerName($value)
    {
        return $this->server_name;
    }

    abstract public function getEntityType();
}
