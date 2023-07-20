<?php
	class MySQLDBHandler extends mysqli
	{
		private const servername	= "";
		private const username 		= "";
		private const password 		= "";
		private const database 		= "";

		private $autoCommit;

		public function __construct(
			string $servername 	= "127.0.0.1", 
			string $username 	= "root", 
			string $password 	= "root", 
			string $database 	= "",
			bool $autoCommit 	= false
		)
		{
			$this->servername 	= $servername;
			$this->username 	= $username;
			$this->password 	= $password;
			$this->database 	= $database;
			$this->autoCommit 	= $autoCommit;

			parent::__construct(
				$this->servername, 
				$this->username, 
				$this->password, 
				$this->database
			);

			parent::autocommit($this->autoCommit);
		}


		public function rawExecute($sql)
		{

		}


		public function rawExecuteQuery($sql)
		{

		}


		public function rawExecuteUpdate($sql)
		{

		}


		public function fetchTableAll(string $table_name) : array
		{
			return $this->fetchTableWhere($table_name, null);
		}


		public function fetchTableWhere(
			string $table_name, 
			?array $where_clauses, 
			string $operator = "AND"
		) : ?array
		{
			$SQL = "SELECT * FROM $table_name ";

			if($where_clauses && count($where_clauses))
				$SQL .= " WHERE " . implode(" " . $operator . " ", $where_clauses);

			$SQL .= ";";

			if($result = parent::query($SQL, MYSQLI_STORE_RESULT))
			{
				$rows = $this->mysqliResObjToDBRowArray($result);
				$result->close();
				return $rows;
			}
			else
			{
				return null;
			}
		}


		private function mysqliResObjToDBRowArray(mysqli_result $mysqli_result_obj) : array
		{
			$rows = array();
			while($row = $mysqli_result_obj->fetch_object())
			{
				$table_row = new DBRow();
				foreach($row as $field => $value)
				{
					$table_row->addField($field, $value);
				}
				array_push($rows, $table_row);
			}
			return $rows;
		}


		public function insertIntoTable(
			string $table_name, 
			array $field_names, 
			array $field_values,
			string $format
		) : bool
		{
			$SQL = "INSERT INTO $table_name("
					. implode(", ", $field_names)
					. ") VALUES("
					. implode(", ", array_fill(0, count($field_values), "?"))
					. ");";
			
			$sql_stmt = $this->prepare($SQL);
			$sql_stmt->bind_param($format, ...$field_values);

			if($sql_stmt === False) return False;

			return $this->autoCommit 
					? $sql_stmt->execute() 
					: $this->mysqliStmtExecuteAndCommit($sql_stmt, $field_values);
		}


		private function mysqliStmtExecuteAndCommit(mysqli_stmt $sql_stmt, array $values) : bool
		{
			try
			{
				$this->begin_transaction();
				$sql_stmt->execute();
				$this->commit();
				return true;
			}
			catch(mysqli_sql_exception $exception)
			{
				$this->rollback();
			}
			return false;
		}


		public function removeFromTable(string $table_name)
		{
			return removeFromTableWhere($table_name, null);
		}


		public function removeFromTableWhere(
			string $table_name, 
			?array $where_clauses, 
			string $operator = "AND")
		{
			$SQL = "DELETE FROM $table_name ";

			if($where_clauses && count($where_clauses))
				$SQL .= " WHERE " . implode(" " . $operator . " ", $where_clauses);

			$SQL .= ";";
			return $this->autoCommit ? $this->query($SQL) : $this->mysqliQueryAndCommit($SQL);
		}


		public function mysqliQueryAndCommit(string $sql)
		{
			$this->begin_transaction();
			$result = $this->query($SQL);
			$this->commit();
			return $result;
		}
	}


	class DBRow
	{
		public $data;

		public function __construct(?array ...$kwargs)
		{
			$this->data = $kwargs ? $kwargs : null;
		}

		public function addField(string $field_name, ?string $field_value)
		{
			$this->data[$field_name] = $field_value;
		}

		public function getField(string $field_name)
		{
			return $this->data[$field_name];
		}
	}
?>