<?php

class MarketAttendance extends DatabaseObject
{

  protected static $table_name = 'market_attendance';
  protected static $db_columns = ['attendance_id', 'vendor_id', 'market_date_id', 'is_confirmed'];

  public $attendance_id;
  public $vendor_id;
  public $market_date_id;
  public $is_confirmed;

  protected static $primary_key = 'attendance_id';
  /**
   * Find all market_date_ids this vendor is signed up for
   *
   * @param int $vendor_id
   * @return array - List of market_date_ids
   */
  public static function find_by_vendor($vendor_id)
  {
    $sql = "SELECT market_date_id FROM " . static::$table_name;
    $sql .= " WHERE vendor_id = ?";

    $stmt = static::$database->prepare($sql);
    $stmt->bind_param("i", $vendor_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $market_dates = [];
    while ($row = $result->fetch_assoc()) {
      $market_dates[] = (int) $row['market_date_id'];
    }

    return $market_dates;
  }

  public static function delete_all_for_vendor($vendor_id)
  {
    $sql = "DELETE FROM " . static::$table_name . " WHERE vendor_id = ?";
    $stmt = static::$database->prepare($sql);
    $stmt->bind_param("i", $vendor_id);
    return $stmt->execute();
  }
}
