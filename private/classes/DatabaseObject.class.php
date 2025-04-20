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

  static public function find_by_sql($sql, $params = [])
  {
    $stmt = self::$database->prepare($sql);
    if ($stmt === false) {
      throw new Exception("Database query failed: " . self::$database->error);
    }

    if (!empty($params)) {
      $types = str_repeat('s', count($params));
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
      return false;
    }

    $object_array = [];
    while ($record = $result->fetch_assoc()) {
      $object_array[] = static::instantiate($record);
    }

    $stmt->close();
    return $object_array;
  }

  static public function find_all()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    return static::find_by_sql($sql);
  }

  static public function find_by_id($id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE " . static::$primary_key . " = ?";
    $params = [$id];
    $obj_array = static::find_by_sql($sql, $params);
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

    $attributes = $this->attributes();
    $columns = array_keys($attributes);
    $placeholders = array_fill(0, count($columns), '?');

    $sql = "INSERT INTO " . static::$table_name;
    $sql .= " (" . join(', ', $columns) . ") ";
    $sql .= "VALUES (" . join(', ', $placeholders) . ")";

    $stmt = self::$database->prepare($sql);
    if ($stmt === false) {
      throw new Exception("Prepare failed: " . self::$database->error);
    }

    $types = str_repeat('s', count($columns));
    $values = array_values($attributes);
    $stmt->bind_param($types, ...$values);

    $result = $stmt->execute();
    if ($result) {
      $this->{static::$primary_key} = self::$database->insert_id;
    }

    $stmt->close();
    return $result;
  }

  public function update()
  {
    $this->validate();
    if (!empty($this->errors)) {
      return false;
    }

    $attributes = $this->attributes();
    $columns = array_keys($attributes);

    $set_clause = join(', ', array_map(fn($col) => "$col = ?", $columns));
    $sql = "UPDATE " . static::$table_name . " SET $set_clause";
    $sql .= " WHERE " . static::$primary_key . " = ? LIMIT 1";

    $stmt = self::$database->prepare($sql);
    if ($stmt === false) {
      throw new Exception("Prepare failed: " . self::$database->error);
    }

    $types = str_repeat('s', count($columns)) . 'i';
    $values = array_values($attributes);
    $values[] = $this->{static::$primary_key};

    $stmt->bind_param($types, ...$values);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
  }

  public function save()
  {
    $primary_key = static::$primary_key ?? 'id';

    if (isset($this->$primary_key) && !is_null($this->$primary_key)) {
      return $this->update();
    } else {
      return $this->create();
    }
  }

  public function delete()
  {
    $sql = "DELETE FROM " . static::$table_name . " WHERE " . static::$primary_key . " = ? LIMIT 1";
    $stmt = self::$database->prepare($sql);

    if ($stmt === false) {
      return false;
    }

    $stmt->bind_param("i", $this->{static::$primary_key});
    return $stmt->execute();
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
