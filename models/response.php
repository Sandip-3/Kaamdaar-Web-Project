<?php 
namespace Model;

require_once "user.php";
require_once "request.php";

use Model\{User, Request};

define('REQUEST_RESPONSE', 1);
define('RESPONSE_RESPONSE', 2);
define('RESPONSE_CANCELLED', 3);
define('RESPONSE_OK', 4);

class Response
{
    public User $sender;
    public User $receiver;
    public Request $request;
    public int $responseType;
    public int $responseStatus;
    public string $responseDate;

    public function __construct(User $sender, User $receiver, Request $request, ResponseType $responseType, ResponseStatus $responseStatus, string $responseDate)
    {
        $this->sender = sender;
        $this->receiver = receiver;
        $this->request = request;
        $this->responseType = responseType;
        $this->responseStatus = responseStatus;
        $this->responseDate = responseDate;
    }
} 
?>