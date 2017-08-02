<?php

/**
 * Description of Common
 *
 * @author pahhan
 */
class Domstor_List_Field_Common extends HtmlTableField
{
    protected $in_region;

    /**
     *
     * @var Domstor_Transformer_Interface
     */
    protected $transformer;

    public function setTransformer(Domstor_Transformer_Interface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function getValue()
    {
        if ($this->transformer) {
            return $this->transformer->get($this->getRow());
        }
        return parent::getValue();
    }

    public function isInRegion()
    {
        return $this->getTable()->isInRegion();
    }

    public function getIf($value, $before = null, $after = null, $not = null)
    {
        if (is_null($not)) {
            if ($value) {
                return $before.$value.$after;
            }
        } elseif ($value !== $not) {
            return $before.$value.$after;
        }
    }

    public function getFromTo($from, $to, $after=null, $before=null, $not_prefixed_one=false, $not_show='0')
    {
        $out = $space = '';
        $from_string='от&nbsp;';
        $to_string='до&nbsp;';
        if (($from!==$not_show and isset($from)) or ($to!==$not_show and isset($to))) {
            if ($from===$to) {
                $out=$from;
            } else {
                if ($from and $to) {
                    $both=true;
                    $space=' ';
                    $not_prefixed_one=false;
                }
                if ($from!==$not_show and isset($from)) {
                    $prefix = $not_prefixed_one? '' : $from_string;
                    $out.=$prefix.$from;
                }
                if ($to!==$not_show and isset($to)) {
                    $prefix = $not_prefixed_one? '' : $to_string;
                    $out.=$space.$prefix.$to;
                }
            }
            $out=$before.$out.$after;
        }
        return $out;
    }

    public function getPriceFromTo($from, $to, $currency, $period=null)
    {
        $out = $space = '';
        $from_string='от&nbsp;';
        $to_string='до&nbsp;';
        if ($from!==null or $to!==null) {
            if ($from != '0' or  $to != '0') {
                if ($from == $to) {
                    $price=number_format($from, 0, ',', ' ');
                    $price=str_replace(' ', '&nbsp;', $price);
                    $period = $period? '&nbsp;'.$period : '';
                    $out=$price.'&nbsp;'.$currency.$period;
                } else {
                    if ($from != '0') {
                        $price=number_format($from, 0, ',', ' ');
                        $price=str_replace(' ', '&nbsp;', $price);
                        $out.=$from_string.$price;
                        $space=' ';
                    }

                    if ($to != '0') {
                        $price=number_format($to, 0, ',', ' ');
                        $price=str_replace(' ', '&nbsp;', $price);
                        $out.=$space.$to_string.$price;
                    }
                    $out.='&nbsp;'.$currency.'&nbsp;'.$period;
                }
            }
        }
        return $out;
    }
}
