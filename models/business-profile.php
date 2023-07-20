<?php 
namespace Model;

class BusinessProfile
{
    public string $b_profile_id;
    public string $b_profile_name;
    public string $b_profile_image;
    public string $b_profile_location;
    public string $b_profile_latlong;
    public string $u_id;

    public function __construct(string $bid, string $name, string $bimage, string $bloc, string $blatlong, string $uid)
    {
        $this->b_profile_id = $bid;
        $this->b_profile_name = $name;
        $this->b_profile_image = $bimage;
        $this->b_profile_location = $bloc;
        $this->b_profile_latlong = $blatlong;
        $this->u_id = $uid;
    }
}
?>