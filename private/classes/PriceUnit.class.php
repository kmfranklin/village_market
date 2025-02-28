<?php
class PriceUnit extends DatabaseObject
{
  static protected $table_name = 'price_unit';
  static protected $db_columns = ['price_unit_id', 'unit_name'];

  public $price_unit_id;
  public $unit_name;

  public static function find_all()
  {
    return static::find_by_sql("SELECT * FROM " . static::$table_name);
  }

  /**
   * Find a price unit by ID.
   *
   * @param int $id The price unit ID.
   * @return PriceUnit|null The price unit object or null if not found.
   */
  public static function find_by_id($id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE price_unit_id = ? LIMIT 1";
    $result = static::find_by_sql($sql, [$id]);
    return !empty($result) ? array_shift($result) : null;
  }
}
