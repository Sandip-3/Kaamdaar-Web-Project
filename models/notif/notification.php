<?php 
namespace Model;

require_once "../response.php";
require_once "../request.php";

use Model\{Request, Response};

abstract class Notification 
{
    public int      $id;
    public string   $title;
    public string   $description;
    public string   $creationDate;
    public string   $icon;

    public function __construct(int $id, string $title, string $description, string $creationDate, string $icon)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->creationDate = $creationDate;
        $this->icon = $icon;
    }
}

class RequestNotification extends Notification
{
    public Request $request;

    public function __construct(int $id, Request $request, string $description)
    {
        parent::__construct($id, REQUEST_TYPE_NAMES[$request->type], $description, $request->time, "");
        $this->request = $request;
    }
}

class ResponseNotification extends Notification
{
    public Response $response;

    public function __construct(int $id, Response $response, string $title)
    {
        parent::__construct($id, $title, "", $response->responseDate, "");
        $this->response = $response;
    }
}
?>