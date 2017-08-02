<?php

/**
 * Description of LinkToObject
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Transformer_LinkToObject implements Domstor_Transformer_Interface
{
    /**
     *
     * @var Domstor_Transformer_Interface
     */
    private $transformer;

    private $href;
    
    private $id_placeholder;

    public function __construct(Domstor_Transformer_Interface $transformer, $href, $id_placeholder)
    {
        $this->transformer = $transformer;
        $this->href = $href;
        $this->id_placeholder = $id_placeholder;
    }


    public function get($data)
    {
        return sprintf('<a href="%s">%s</a>', str_replace($this->id_placeholder, $data['id'], $this->href), $this->transformer->get($data));
    }    //put your code here
}
