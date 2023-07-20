<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/kaamdaar-php/constants.php";
    require_once ROOT_DIR . "models/user.php";
    require_once ROOT_DIR . "models/business-profile.php";
    require_once ROOT_DIR . "models/business.php";
    require_once ROOT_DIR . "models/request.php";
    require_once ROOT_DIR . "models/request-notification.php";

    use Model\{User, Business, BusinessProfile, BusinessCategory, Request, RequestNotification};

    class ResultSet implements Iterator, Countable
    {
        private $items = [];
        private $pointer = 0;
        private $itemsCount = 0;

        public function __construct($result)
        {
            if(!($result instanceof mysqli_result))
                return;

            while($row = $result->fetch_assoc())
                array_push($this->items, $row);

            $this->itemsCount = count($this->items);
        }

        public function current() {
            return $this->items[$this->pointer];
        }
    
        public function key() {
            return $this->pointer;
        }
    
        public function next() {
            $this->pointer++;
        }

        public function rewind() {
            $this->pointer = 0;
        }
    
        public function valid() {
            return $this->pointer < count($this->items);
        }

        public function count()
        {
            return $this->itemsCount;
        }
    }

    class KaamdaarORM
    {
        public $connection = null;
        public function __construct()
        {
            $this->connection = new mysqli("localhost", "root", "", "kaamdaar");
            if($this->connection->connect_error)
            {
                echo "Failed to connect to MySQL database: " . $this->connection->connect_error;
                exit();
            }

            $this->connection->autocommit(true);
        }

        public function close()
        {
            $this->connection->close();
        }

        public function from(string $table)
        {
            $this->current_table = $table;
            return $this;
        }

        public function fetch(?array $attrs, ?array $where_clauses)
        {
            if($this->current_table === "")
            {
                echo "Choose a table using 'from()' first.";
                return null;
            }

            $SQL = "SELECT ";
            if($attrs && count($attrs))
                $SQL .= implode(",", $attrs);
            else $SQL .= "*";

            $SQL .= " FROM $this->current_table";
            $this->current_table = "";

            if($where_clauses && count($where_clauses))
            {
                $SQL .= " WHERE " . implode(" and ", $this->array_map_assoc(
                    function($key, $value) {
                        return "`$key`='$value'";
                    }, 
                $where_clauses));
            }
            $SQL .= ";";

            if($result = $this->connection->query($SQL))
                return new ResultSet($result);
            else 
                return null;
        }

        public function fetchAll()
        {
            return $this->fetch(null, null);
        }

        public function lastInsertId()
        {
            return $this->connection->insert_id;
        }

        private function array_map_assoc($func, array $arr)
        {
            $result = array();
            foreach($arr as $key => $value)
            {
                array_push($result, $func($key, $value));
            }
            return $result;
        }

        public function addNewUser(User $user)
        {
            $SQL = "INSERT INTO 
                    users(
                        u_id,
                        u_fname, 
                        u_lname, 
                        u_phone, 
                        u_password, 
                        u_gender, 
                        u_date,
                        u_location, 
                        u_latlong, 
                        u_image
                    ) 
                    VALUE(
                        '$user->id',
                        '$user->fname', 
                        '$user->lname',
                        '$user->phone',
                        '$user->password',
                        '$user->gender',
                        '$user->dateJoined',
                        '$user->location',
                        '$user->locLatLong',
                        ''
                    );";

            $this->connection->query($SQL);
        }

        public function addNewRequest(Request $request)
        {
            $SQL = "INSERT INTO 
                        request(
                            REQUEST_LOCATION, 
                            REQUEST_LATLONG, 
                            U_ID, 
                            REQUEST_TYPE, 
                            REQUEST_STATUS, 
                            REQUEST_TIME
                        ) 
                        VALUES(
                            '$request->location',
                            '$request->latlon',
                            '$request->uid',
                            $request->type,
                            $request->status,
                            '$request->time'
                        );";
                        
            $this->connection->query($SQL);
        }

        public function getUserByID($uid)
        {
            $user = null;
            $result_set = $this->from("users")->fetch(null, ["U_ID" => $uid]);
            if($result_set && count($result_set))
            {
                $row = $result_set->current();
                $user = new User(
                    $row['U_ID'],
                    $row["U_FNAME"],
                    $row["U_LNAME"],
                    $row["U_PHONE"],
                    $row["U_PASSWORD"],
                    $row["U_GENDER"],
                    $row["U_DATE"],
                    $row["U_LOCATION"],
                    $row["U_LATLONG"],
                    $row['U_IMAGE']
                );
            }
            return $user;
        }

        public function getUserByPhone($phone)
        {
            $user = null;
            $result_set = $this->from("users")->fetch(null, ["U_PHONE" => $phone]);
            if($result_set && count($result_set))
            {
                $row = $result_set->current();
                $user = new User(
                    $row['U_ID'],
                    $row["U_FNAME"],
                    $row["U_LNAME"],
                    $row["U_PHONE"],
                    $row["U_PASSWORD"],
                    $row["U_GENDER"],
                    $row["U_DATE"],
                    $row["U_LOCATION"],
                    $row["U_LATLONG"],
                    $row['U_IMAGE']
                );
            }
            return $user;
        }

        public function removeUserByID($uid)
        {
            $SQL = "CALL remove_user('$uid');";
            $this->connection->query($SQL);
        }

        public function getBusinessProfile($uid)
        {
            $result = $this->from('business_profile')->fetch(null, ["U_ID" => "$uid"]);
            if($result && count($result))
            {
                $row = $result->current();
                $profile = new BusinessProfile(
                    $row['B_PROFILE_ID'],
                    $row['B_PROFILE_NAME'],
                    $row['B_PROFILE_IMAGE'],
                    "",
                    "",
                    $row["U_ID"]
                );
                return $profile;
            }
            return null;
        }

        public function getAllRequests($uid)
        {
            $SQL = "SELECT * FROM request r INNER JOIN br_category brc ON r.REQUEST_TYPE = brc.BR_CAT_ID WHERE r.U_ID = '$uid' AND r.REQUEST_STATUS = 0 ORDER BY r.REQUEST_TIME DESC;";
            $result_set = new ResultSet($this->connection->query($SQL));

            if($result_set && count($result_set))
            {
                $all_requests = [];
                foreach($result_set as $result)
                {
                    array_push($all_requests, $result);
                }
                return $all_requests;
            }
            return [];
        }

        public function getAllBusinessInfo($bid)
        {
            $SQL = "SELECT b.business_id AS business_id, b.b_profile_id AS business_profile_id, cat.br_cat_name AS business_type, info.b_info_revenue AS business_revenue, info.b_info_rating AS business_rating, info.b_info_total AS business_total, b.business_status AS business_status FROM business b INNER JOIN br_category as cat on cat.br_cat_id = b.business_type INNER JOIN business_info AS info ON b.business_id = info.business_id WHERE b.b_profile_id = '$bid' AND b.business_status != 2;";
            $result_set = new ResultSet($this->connection->query($SQL));

            if(!count($result_set)) return [];
            
            $all_business = [];
            foreach($result_set as $result)
            {
                array_push($all_business, new Model\Business(
                    $result['business_id'],
                    $result['business_type'],
                    $result['business_profile_id'],
                    $result['business_revenue'],
                    $result['business_rating'],
                    $result['business_total'],
                    $result['business_status']
                ));
            }
            return $all_business;
        }

        public function getAllOwnedBusinessTypes($bid)
        {
            $result_set = $this->from("business")->fetch(["BUSINESS_TYPE"], ["B_PROFILE_ID='$bid'"]);
            $types = [];

            foreach($result_set as $result)
            {
                array_push($types, $result['BUSINESS_TYPE']);
            }
            return $types;
        }

        public function fetchRequestNotifications($bid)
        {
            $SQL = "SELECT business_type, business_start_date FROM business WHERE b_profile_id = '$bid';"; //get all the business types and the date they started which are owned by the provided business id
            $result_set = new ResultSet($this->connection->query($SQL));

            $notifications = [];
            foreach($result_set as $result)
            {
                $type = $result['business_type'];
                $business_start_date = $result['business_start_date'];
                // $notif_sql = "SELECT * FROM request_notifications rn INNER JOIN request r ON r.REQUEST_ID = rn.REQUEST_ID INNER JOIN users u ON u.U_ID = r.U_ID WHERE r.REQUEST_TYPE = $type AND TIMESTAMPDIFF(SECOND, r.REQUEST_TIME, '$business_start_date') >= 0;";
                $notif_sql = "SELECT * FROM request_notifications rn NATURAL JOIN request r NATURAL JOIN users u WHERE r.REQUEST_TYPE = $type AND TIMESTAMPDIFF(SECOND, r.REQUEST_TIME, '$business_start_date') >= 0;";
                $notifs = new ResultSet($this->connection->query($notif_sql));
                if(count($notifs))
                {
                    foreach($notifs as $notif)
                    {
                        $request = new Request(
                            $notif['REQUEST_ID'],
                            $notif['REQUEST_LOCATION'],
                            $notif['REQUEST_LATLONG'],
                            $notif['REQUEST_TYPE'],
                            $notif['REQUEST_STATUS'],
                            $notif['REQUEST_TIME'],
                            $notif['U_ID']
                        );

                        $user = new User(
                            $notif['U_ID'],
                            $notif["U_FNAME"],
                            $notif["U_LNAME"],
                            $notif["U_PHONE"],
                            $notif["U_PASSWORD"],
                            $notif["U_GENDER"],
                            $notif["U_DATE"],
                            $notif["U_LOCATION"],
                            $notif["U_LATLONG"],
                            $notif['U_IMAGE']
                        );

                        $request_notif = new RequestNotification(
                            $notif['RNOTIF_ID'], 
                            $request, 
                            $user
                        );

                        array_push($notifications, $request_notif);
                    }
                }
            }
            return $notifications;
        }

        public function getBusinessCategories()
        {
            $result_set = $this->from("br_category")->fetch(null, null);
            $cats = [];
            
            foreach($result_set as $result)
            {
                $cat = new BusinessCategory(
                    $result['BR_CAT_ID'],
                    $result['BR_CAT_NAME'],
                    $result['BR_CAT_ICON']
                );
                array_push($cats, $cat);
            }
            return $cats;
        }

        public function rawQuery($SQL)
        {
            return new ResultSet($this->connection->query($SQL));
        }
    }
?>