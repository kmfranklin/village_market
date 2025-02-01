<?php

/**
 * Base class for database interactions.
 *
 * Provides shared CRUD operations for all database objects.
 * All model classes (e.g., User, Vendor, Product) should extend this class.
 *
 * @package FarmersMarket
 */
class DatabaseObject
{
  static protected $database;
  static protected $table_name = "";
  static protected $db_columns = [];
  static protected $primary_key = "id"; // Default primary key
  public $errors = [];

  /**
   * Set the database connection.
   *
   * @param mysqli $database The database connection object.
   * @return void
   */
  static public function set_database($database)
  {
    self::$database = $database;
  }

  /**
   * Run a custom SQL query and return an array of objects.
   *
   * @param string $sql The SQL query to execute.
   * @return array Array of instantiated objects.
   */
  static public function find_by_sql($sql)
  {
    $result = self::$database->query($sql);
    if (!$result) {
      exit("Database query failed.");
    }

    $object_array = [];
    while ($record = $result->fetch_assoc()) {
      $object_array[] = static::instantiate($record);
    }
    $result->free();

    return $object_array;
  }

  /**
   * Retrieve all records from the table.
   *
   * @return array
   */
  static public function find_all()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    return static::find_by_sql($sql);
  }

  /**
   * Retrieve a record by its ID.
   *
   * @param int|string $id The primary key value.
   * @return static|false The object if found, otherwise false.
   */
  static public function find_by_id($id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE " . static::$primary_key . "='" . self::$database->escape_string($id) . "'";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

  /**
   * Convert a database record into an object.
   *
   * @param array $record
   * @return static
   */
  static protected function instantiate($record)
  {
    $object = new static;
    foreach ($record as $property => $value) {
      if (property_exists($object, $property)) {
        $object->$property = $value;
      }
    }
    return $object;
  }

  /**
   * Validate the object's data.
   *
   * @return array An array of validation errors (if any).
   */
  protected function validate()
  {
    $this->errors = [];
    return $this->errors;
  }

  /**
   * Create a new record in the database.
   *
   * @return bool
   */
  public function create()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    $attributes = $this->sanitized_attributes();
    $sql = "INSERT INTO " . static::$table_name . " (" . join(', ', array_keys($attributes)) . ")";
    $sql .= " VALUES ('" . join("', '", array_values($attributes)) . "')";

    $result = static::$database->query($sql);
    if ($result) {
      $this->{static::$primary_key} = static::$database->insert_id;
    }
    return $result;
  }

  /**
   * Update an existing record in the database.
   *
   * @return bool
   */
  public function update()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    $attributes = $this->sanitized_attributes();
    $attribute_pairs = [];
    foreach ($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }

    $sql = "UPDATE " . static::$table_name . " SET " . join(", ", $attribute_pairs);
    $sql .= " WHERE " . static::$primary_key . "='" . self::$database->escape_string($this->{static::$primary_key}) . "' LIMIT 1";

    return self::$database->query($sql);
  }

  /**
   * Delete a record from the database.
   *
   * @return bool
   */
  public function delete()
  {
    $sql = "DELETE FROM " . static::$table_name . " ";
    $sql .= "WHERE " . static::$primary_key . "='" . self::$database->escape_string($this->{static::$primary_key}) . "' ";
    $sql .= "LIMIT 1";
    return self::$database->query($sql);
  }

  /**
   * Return an array of object properties that match database columns.
   *
   * @return array
   */
  public function attributes()
  {
    $attributes = [];
    foreach (static::$db_columns as $column) {
      if (property_exists($this, $column)) {
        $attributes[$column] = $this->$column;
      }
    }
    return $attributes;
  }

  /**
   * Sanitize object attributes for safe database insertion.
   *
   * @return array
   */
  protected function sanitized_attributes()
  {
    $sanitized = [];
    foreach ($this->attributes() as $key => $value) {
      $sanitized[$key] = self::$database->escape_string($value);
    }
    return $sanitized;
  }
}
