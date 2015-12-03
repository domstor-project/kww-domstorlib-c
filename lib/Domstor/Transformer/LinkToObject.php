<?php

/**
 * Description of LinkToObject
 *
 * @author pahhan
 */
class Domstor_Transformer_LinkToObject implements Domstor_Transformer_Interface
{
    /**
     *
     * @var Domstor_Transformer_Interface
     */
    private $transformer;

    private $href;

    function __construct(Domstor_Transformer_Interface $transformer, $href) {
        $this->transformer = $transformer;
        $this->href = $href;
    }


    public function get($data) {
        return sprintf('<a href="%s">%s</a>', str_replace('%id', $data['id'], $this->href), $this->transformer->get($data));
    }    //put your code here
}

