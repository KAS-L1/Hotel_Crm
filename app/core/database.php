<?php

/**
 * DATABASE CLASS AND SQL FUNCTIONS
 **/

class Database
{
	public $DB_HOST = "localhost";
	public $DB_USER = "root";
	public $DB_PASSWORD = "";
	public $DB_NAME = "hotel_crm";
	public $DB;

	// INITIATE CONNECTION 	
	function __construct()
	{
		$this->DB_CONNECTION();
	}

	// CONNECTION INSTANCE
	public function DB_CONNECTION()
	{
		$this->DB = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD, $this->DB_NAME);
		if (!$this->DB) {
			print($this->DB->connect_error);
			exit();
		}
	}

	// CLOSE CONNECTION
	public function CLOSE()
	{
		$this->DB->close();
	}

	// ESCAPE SPECIAL CHARACTER ANTI SQL INJECTION
	public function ESCAPE($data)
	{
		return $this->DB->real_escape_string($data);
	}

	// CUSTOM QUERY
	public function SQL($query)
	{
		return $this->DB->query($query);
	}

	// SELECT FIELD
	public function SELECT($table, $fields = '*', $options = '')
	{
		$query = "SELECT {$fields} FROM {$table} {$options} ";
		$result = $this->DB->query($query) or die("Cannot execute SELECT command: " . $this->DB->error);
		$data = $result->fetch_all(MYSQLI_ASSOC);
		return $data;
	}

	// SELECT MULTITPLE ROW WHERE CONDITION
	public function SELECT_WHERE($table, $fields, $where, $options = '')
	{
		$condition = "";
		foreach ($where as $key => $value) {
			$condition .= $key . " = '" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$query = "SELECT {$fields} FROM {$table} WHERE {$condition} {$options}";
		$result = $this->DB->query($query) or die("Cannot execute SELECT_WHERE command: " . $this->DB->error);
		$data = $result->fetch_all(MYSQLI_ASSOC);
		return $data;
	}

	// SELECT SINGLE ROW
	public function SELECT_ONE($table, $fields = '*', $options = null)
	{
		$query = "SELECT {$fields} FROM {$table} {$options} LIMIT 1";
		$result = $this->DB->query($query);
		$data = $result->fetch_assoc();
		return $data;
	}

	// SELECT SINGLE ROW WHERE CONDITION
	public function SELECT_ONE_WHERE($table, $fields, $where, $options = null)
	{
		$condition = "";
		foreach ($where as $key => $value) {
			$condition .= $key . " = '" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$query = "SELECT {$fields} FROM {$table} WHERE {$condition} {$options} LIMIT 1";
		$result = $this->DB->query($query) or die("Cannot execute SELECT_ONE_WHERE command: " . $this->DB->error);
		$data = $result->fetch_assoc();
		return $data;
	}

	// INSERT DATA
	public function INSERT($table, $data)
	{
		$field_key = implode(",", array_keys($data));
		$field_value = implode("','", array_values($data));
		$query = "INSERT INTO {$table} ({$field_key}) VALUES('{$field_value}')";
		if ($this->DB->query($query)) {
			return array("success" => true, "message" => "Data inserted successfully");
		} else {
			return array("success" => false, "message" => 'Failed to insert data in ' . $table . ' table: ' . $this->DB->error);
		}
	}

	// UPDATE DATA
	public function UPDATE($table, $data, $where)
	{
		$statement = "";
		$condition = "";
		foreach ($data as $key => $value) {
			$statement .= $key . " = '" . $value . "', ";
		}
		$statement = substr($statement, 0, -2);
		foreach ($where as $key => $value) {
			$condition .= $key . " = '" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$query = "UPDATE {$table} SET {$statement} WHERE {$condition} ";
		if ($this->DB->query($query)) {
			return array("success" => true, "message" => "Data updated successfully");
		} else {
			return array("success" => false, "message" => 'Failed to update data in ' . $table . ' table: ' . $this->DB->error);
		}
	}

	// DELETE DATA
	public function DELETE($table, $where)
	{
		$condition = "";
		foreach ($where as $key => $value) {
			$condition .= $key . " = '" . $value . "' AND ";
		}
		$condition = substr($condition, 0, -5);
		$query = "DELETE FROM {$table} WHERE {$condition} ";
		if ($this->DB->query($query)) {
			return array("success" => true, "message" => "Data deleted successfully");
		} else {
			return array("success" => false, "message" => 'Failed to delete data in ' . $table . ' table: ' . $this->DB->error);
		}
	}
}
