<?php
class ProductDTO 
{
    public $id;
    public $name;
    public $price;
    public $content;
    public $image;
    
    function __construct()
    {
        
    }

    function __toString()
    {
        return "{"."\"id\":\"$this->id\"".",\"name\":\"$this->name\"" .",\"price\":\"$this->price\"".",\"content\":\"$this->content\"".",\"image\":\"$this->image\""."}";
    }
}
