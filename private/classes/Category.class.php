<?php

class Category extends DatabaseObject
{
  protected static $table_name = "category";
  protected static $db_columns = ['category_id', 'category_name'];

  public $category_id;
  public $category_name;

  /**
   * Find a category by its ID.
   *
   * @param int $id The category ID.
   * @return Category|null Category object or null if not found.
   */
  public static function find_by_id($id)
  {
    $sql = "SELECT * FROM " . static::$table_name . " WHERE category_id = ? LIMIT 1";
    $result = static::find_by_sql($sql, [$id]);
    return !empty($result) ? array_shift($result) : null;
  }
}
