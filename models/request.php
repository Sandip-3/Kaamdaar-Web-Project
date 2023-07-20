<?php
namespace Model;

const REQUEST_TYPE_NAMES = [
    10 => "Auto Mobile Repair",
    20 => "Plumbing",
    30 => "Carpenting",
    40 => "Computer Repair",
    50 => "House Cleaning",
    60 => "Painter",
];

const REQUEST_STATUS = [
    0 => "Pending",
    1 => "Fulfilled"
];

class Request 
{
    public $id;
    public $location;
    public $latlon;
    public $type;
    public $status;
    public $time;
    public $uid;
    public $urgency;

    public function __construct($id, $location, $latlon, $type, $status, $time, $urgency, $uid)
    {
        $this->id = $id;
        $this->location = $location;
        $this->latlon = $latlon;
        $this->type = $type;
        $this->status = $status;
        $this->time = $time;
        $this->urgency = $urgency;
        $this->uid = $uid;
    }
}
?>