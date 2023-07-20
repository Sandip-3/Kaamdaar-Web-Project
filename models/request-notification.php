<?php 
namespace Model;

require_once "user.php";
require_once "request.php";

use Model\{User, Request};

class RequestNotification
{
    public $id;
    public $request;
    public $user;
    
    public function __construct($id, Request $request, User $user)
    {
        $this->id = $id;
        $this->request = $request;
        $this->user = $user;
    }
}
?>