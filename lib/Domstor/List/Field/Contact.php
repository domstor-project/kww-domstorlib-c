<?php

/**
 * Description of Contact
 * @author pahhan
 */
class Domstor_List_Field_Contact extends Domstor_List_Field_Common
{
    public function getValue()
    {
        $a = $this->getTable()->getRow();
        $out = $space = '';
        if ($a['agent_tel_work'] and $a['agent_tel_sot'])
        {
            $space = ', ';
        }
        switch ($a['agency_tipcont'])
        {
            case '1':
                $out = $a['agency_tel_cont'];
                break;
            case '2':
                $out = $a['filial_phone'];
                break;
            case '3':
                $out = (isset($a['agent_phone']) && !empty($a['agent_phone'])) ? $a['agent_phone'] : $a['agent_tel_work'] . $space . $a['agent_tel_sot'];
                break;
            default:
                $out = '';
                break;
        }
        $out = str_replace(',', ', ', $out);

        $words = explode(', ', $out);

        $formatted = '';

        foreach ($words as &$word)
        {
            $cleared = preg_replace('/[^0-9]/', '', $word);
            if (strlen($cleared) > 10)
            {
                if ($cleared[0] === '8')
                {
                    $cleared[0] = '7';
                }
                $word = sprintf('<a href="tel:+%s">%s</a>', $cleared, $word);
            }

        }
        $formatted = implode(', ', $words);

        return $formatted;
    }
}
