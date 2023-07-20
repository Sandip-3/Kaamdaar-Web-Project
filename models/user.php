<?php
namespace Model;

class User
{
	public $id = 0;
	public $fname = "";
	public $lname = "";
	public $phone = "";
	public $password = "";
	public $gender = "";
	public $dateJoined = "";
	public $location = "";
	public $locLatLong = "";
	public $image;

	public function __construct(
		string $id,
		string $fname, 
		string $lname, 
		string $phone,
		string $password,
		string $gender,
		string $dateJoined, 
		string $location,
		string $locLatLong,
		string $image
	)
	{
		$this->id = $id;
		$this->fname = $fname;
		$this->lname = $lname;
		$this->phone = $phone;
		$this->password = $password;
		$this->gender = $gender;
		$this->dateJoined = $dateJoined;
		$this->location = $location;
		$this->locLatLong = $locLatLong;
		$this->image = $image;
	}
}
?>