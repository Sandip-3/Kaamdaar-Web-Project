<?php 
	require_once "db_controller.php";
	require_once "/Applications/XAMPP/htdocs/kaamdaar-php/constants.php";
	require_once ROOT_DIR . "/models/user.php";
	require_once ROOT_DIR . "/models/business-profile.php";
	require_once "kdb_constants.php";

	use Model\{User, BusinessProfile};

	const all_business_tables = array('plumber', 'carpenter');

	final class KaamdaarDBHandler extends MySQLDBHandler
	{
		public function __construct()
		{
			parent::__construct(
				DB_HOST,
				DB_USERNAME,
				DB_PASSWORD,
				DB_NAME,
				false
			);
		}

		public function addUser(User $user) : bool
		{
			$format = "sssssssss";

			$field_names = [
				'u_fname', 
				'u_lname', 
				'u_phone',
				'u_password',
				'u_gender',
				'u_date', 
				'u_location',
				'u_loc_latlong',
				'u_image'
			];

			$field_values = [
				$user->fname, 
				$user->lname, 
				$user->phone, 
				$user->password, 
				$user->gender, 
				$user->dateJoined, 
				$user->location,
				$user->locLatLong,
				$user->image
			];

			return $this->insertIntoTable(USER_TABLE, $field_names, $field_values, $format);
		}

		private function getUser(?string $field_name, $check_value) : ?User
		{
			if(!$field_name || !$check_value) return null;

			$user = null;
			$rows = $this->fetchTableWhere(USER_TABLE, ["$field_name=$check_value"]);
			if($rows && count($rows))
			{
				$row = $rows[0];
				$user = new User(
					$row->getField('u_id'),
					$row->getField('u_fname'),
					$row->getField('u_lname'),
					$row->getField('u_phone'),
					$row->getField('u_password'),
					$row->getField('u_gender'),
					$row->getField('u_date'),
					$row->getField('u_location'),
					$row->getField('u_loc_latlong')
				);
			}
			return $user;
		}

		public function getUserWithID(?int $uid) : ?User
		{
			return $this->getUser("u_id", $uid);
		}

		public function getUserWithPhone(?string $phone) : ?User
		{
			return $this->getUser("u_phone", $phone);
		}

		public function removeUserWithID(?int $uid) : bool
		{
			if(!$uid) return false;
			return $this->removeFromTableWhere(USER_TABLE, ["u_id=$uid"]);
		}

		public function getBusinessProfile(?int $uid) : ?BusinessUser 
		{
			if(!$uid) return null;

			$profile = null;
			$rows = $this->fetchTableWhere(BUSINESS_PROFILE_TABLE, ["u_id=$uid"]);

			if($rows && count($rows))
			{
				$row = $rows[0];
				$profile = new BusinessUser(
					$row->getField('B_PROFILE_ID'),
					$row->getField('B_PROFILE_NAME'),
					$row->getField('U_ID')
				);
			}
			return $profile;
		}

		public function getBusiness($bid, $type)
		{
			if(!$bid || !$type) return null;

			$business = null;
			$rows = $this->fetchTableWhere($type."", ["bp_id=$bid"]);

			if($rows and count($rows))
			{
				$row = $rows[0];
				$business_info = $this->fetchTableWhere($type."_business_info", ["i_id=".$row->getField('bi_id')]);
				
				if($business_info && count($business_info))
				{
					$bi = $business_info[0];
					$business = new BusinessInfo(
						$bid, 
						$type, 
						$bi->getField('i_total'),
						$bi->getField("i_revenue"),
						$bi->getField("i_rating")
					);
				}	
			}
			return $business;
		}

		public function getAllBusinesses(?int $bid) : ?array
		{
			if(!$bid) return null;

			$all = array();
			foreach(all_business_tables as $table)
			{
				$business = $this->getBusiness($bid, $table);
				if($business)
				{
					array_push($all, $business);
				}
			}
			return $all;
		}

		public function __toString()
		{
			return $this->database;
		}
	}

?>