<?php 
namespace Model;

class BusinessCategory 
{
    public $cat_id;
    public $cat_name;
    public $cat_icon;

    public function __construct($id, $name, $icon)
    {
        $this->cat_id = $id;
        $this->cat_name = $name;
        $this->cat_icon = $icon;
    }
}
?>