<?php 

class detailCart {
    public $quantity;
    public ProductDTO $ProductDTO;

    function __construct($quantity,$ProductDTO)
    {
   
        $this->quantity = $quantity;
        $this->ProductDTO = $ProductDTO;
    }
//     function jsonSerialize() {
//         return Array('quantity'=>$this->quantity);// Encode this array instead of the current element
// }
    function __get($name)
    {
        return $this->$name;
    }
    function __set($name, $value)
    {
        $this->$name = $value;
    }

}
