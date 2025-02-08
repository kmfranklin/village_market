<?php

class DatabaseObject
{
  static public $database;
  static protected $table_name = "";
  static protected $db_columns = [];
  static protected $primary_key = "id";
  public $errors = [];

  static public function set_database($database)
  {
    self::$database = $database;
  }

  static public function find_by_sql($sql)
  {
    $result = self::$database->query($sql);
    if (!$result) {
      return false;
    }

    $object_array = [];
    while ($record = $result->fetch_assoc()) {
      $object_array[] = static::instantiate($record);
    }
    $result->free();

    return $object_array;
  }

  static public function find_all()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    return static::find_by_sql($sql);
  }

  static public function find_by_id($id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " ";
    $sql .= "WHERE " . static::$primary_key . "='" . self::$database->escape_string($id) . "'";
    $obj_array = static::find_by_sql($sql);
    return !empty($obj_array) ? array_shift($obj_array) : false;
  }

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

  protected function validate()
  {
    $this->errors = [];
    return $this->errors;
  }

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

  public function delete()
  {
    $sql = "DELETE FROM " . static::$table_name . " ";
    $sql .= "WHERE " . static::$primary_key . "='" . self::$database->escape_string($this->{static::$primary_key}) . "' ";
    $sql .= "LIMIT 1";
    return self::$database->query($sql);
  }

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

  protected function sanitized_attributes()
  {
    $sanitized = [];
    foreach ($this->attributes() as $key => $value) {
      $sanitized[$key] = self::$database->escape_string($value);
    }
    return $sanitized;
  }
}
