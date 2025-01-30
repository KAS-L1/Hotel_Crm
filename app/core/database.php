<?php

/**
 * DATABASE CLASS AND SQL FUNCTIONS
 **/

class Database
{
	public $DB_HOST = "localhost";
	public $DB_USER = "root";
	public $DB_PASSWORD = "";
	public $DB_NAME = "hotelcrm";
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
		$statement = substr($statement, 0, -2); // Remove the last comma and space
		foreach ($where as $key => $value) {
			// Check for != condition and handle separately
			if (strpos($key, '!=') !== false) {
				$condition .= $key . " '" . $value . "' AND ";
			} else {
				$condition .= $key . " = '" . $value . "' AND ";
			}
		}
		$condition = substr($condition, 0, -5); // Remove the last ' AND '
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

	// Join Data
		public function SELECT_JOIN($tables, $fields, $onConditions = [], $joinTypes = [], $where = [], $options = '')
		{
			// Validate input arrays
			$isCrossJoin = in_array('CROSS JOIN', $joinTypes);

			if (!$isCrossJoin && (count($tables) < 2 || count($onConditions) < 1 || count($onConditions) != count($tables) - 1 || count($joinTypes) != count($tables) - 1)) {
				throw new Exception("Invalid number of tables, join conditions, or join types provided.");
			}

			// Ensure each table has an alias, e.g., t1, t2, ...
			for ($i = 0; $i < count($tables); $i++) {
				$tables[$i] = "{$tables[$i]} AS t" . ($i + 1);
			}

			// Start building the query with the first table
			$query = "SELECT {$fields} FROM {$tables[0]}";

			// Loop through each table and join conditions with specified join types
			for ($i = 1; $i < count($tables); $i++) {
				$joinType = strtoupper($joinTypes[$i - 1]);

				// Validate join type
				if (!in_array($joinType, ['INNER JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'CROSS JOIN', 'FULL JOIN'])) {
					throw new Exception("Invalid join type specified: {$joinType}");
				}

				// Handle CROSS JOIN without an ON clause
				if ($joinType === 'CROSS JOIN') {
					$query .= " {$joinType} {$tables[$i]}";
				} else {
					// For other join types, add ON conditions
					$onClause = '';
					foreach ($onConditions[$i - 1] as $on) {
						$onClause .= "{$on[0]} = {$on[1]} AND ";
					}
					$onClause = substr($onClause, 0, -5); // Remove the last ' AND '
					$query .= " {$joinType} {$tables[$i]} ON {$onClause}";
				}
			}

			// Construct the WHERE clause, if provided
			if (!empty($where)) {
				$condition = '';
				foreach ($where as $key => $value) {
					$condition .= "{$key} = ? AND ";
				}
				$condition = substr($condition, 0, -5); // Remove the last ' AND '
				$query .= " WHERE {$condition}";
			}

			// Add any additional options (e.g., ORDER BY, LIMIT)
			$query .= " {$options}";

			// Prepare and execute the statement
			$stmt = $this->DB->prepare($query);

			// Bind parameters if WHERE conditions are provided
			if ($stmt !== false && !empty($where)) {
				$types = str_repeat('s', count($where)); // Adjust types as needed
				$stmt->bind_param($types, ...array_values($where));
			}

			if ($stmt === false) {
				throw new Exception("Failed to prepare statement: " . $this->DB->error);
			}

			if (!$stmt->execute()) {
				throw new Exception("Query execution failed: " . $this->DB->error);
			}

			$result = $stmt->get_result();

			if ($result === false) {
				throw new Exception("Query execution failed: " . $this->DB->error);
			}

			$data = $result->fetch_all(MYSQLI_ASSOC);
			return $data;
		}
}
