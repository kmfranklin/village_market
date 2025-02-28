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
}
