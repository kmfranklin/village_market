<?php

class MarketDate extends DatabaseObject
{
  protected static $table_name = 'market_date';
  protected static $db_columns = ['market_date_id', 'market_date', 'is_active'];

  public $market_date_id;
  public $market_date;
  public $is_active;

  /**
   * Find all future market dates that are active.
   *
   * @return array List of MarketDate objects
   */
  public static function upcoming()
  {
    $sql = "SELECT * FROM " . static::$table_name;
    $sql .= " WHERE market_date > NOW() AND is_active = 1";
    $sql .= " ORDER BY market_date ASC";
    return static::find_by_sql($sql);
  }

  /**
   * Find all market dates that fall within the next calendar month.
   *
   * @return array List of MarketDate objects for next month only
   */
  public static function next_month_only()
  {
    $first_day = date('Y-m-01', strtotime('+1 month'));
    $last_day = date('Y-m-t', strtotime('+1 month'));

    $sql = "SELECT * FROM " . static::$table_name;
    $sql .= " WHERE market_date BETWEEN ? AND ? AND is_active = 1";
    $sql .= " ORDER BY market_date ASC";

    $stmt = static::$database->prepare($sql);
    $stmt->bind_param("ss", $first_day, $last_day);
    $stmt->execute();

    $result = $stmt->get_result();
    $dates = [];
    while ($row = $result->fetch_assoc()) {
      $dates[] = static::instantiate($row);
    }

    return $dates;
  }
}
