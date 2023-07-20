<?php 
namespace Model;

class Business 
{
    public function __construct($id, $type, $bid, $revenue, $rating, $total, $status)
    {
        $this->business_id = $id;
        $this->business_type = $type;
        $this->business_profile_id = $bid;
        $this->business_revenue = $revenue;
        $this->business_rating = $rating;
        $this->business_total = $total;
        $this->business_status = $status;
    }
}
?>