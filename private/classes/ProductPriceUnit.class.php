<?php
class ProductPriceUnit extends DatabaseObject
{
  static protected $table_name = 'product_price_unit';
  static protected $db_columns = ['product_price_unit_id', 'product_id', 'price_unit_id', 'price'];

  public $product_price_unit_id;
  public $product_id;
  public $price_unit_id;
  public $price;

  public function __construct($args = [])
  {
    $this->product_id = $args['product_id'] ?? null;
    $this->price_unit_id = $args['price_unit_id'] ?? null;
    $this->price = $args['price'] ?? null;
  }

  /**
   * Get all price units and prices for a specific product.
   *
   * @param int $product_id The product ID.
   * @return array List of price units and prices.
   */
  public static function find_by_product_id($product_id)
  {
    $sql = "SELECT * FROM product_price_unit WHERE product_id = ?";
    return static::find_by_sql($sql, [$product_id]);
  }

  /**
   * Find a price unit by ID.
   *
   * @param int $id The price unit ID.
   * @return object|null PriceUnit object or null if not found.
   */
  public static function find_by_id($id)
  {
    $sql = "SELECT * FROM price_unit WHERE price_unit_id = ? LIMIT 1";
    $result = static::find_by_sql($sql, [$id]);
    return !empty($result) ? array_shift($result) : null;
  }

  /**
   * Get the unit name for this price unit.
   *
   * @return string The unit name or 'Unknown' if not found.
   */
  public function get_unit_name()
  {
    $unit = PriceUnit::find_by_id($this->price_unit_id);
    return $unit ? $unit->unit_name : 'Unknown';
  }

  public static function update_product_prices($product_id, $price_data)
  {
    if (!isset(self::$database)) {
      throw new Exception("Database connection is not set.");
    }

    // Fetch existing price units for the product
    $existing_units = self::find_by_product_id($product_id);
    $existing_unit_ids = [];

    foreach ($existing_units as $unit) {
      $existing_unit_ids[$unit->price_unit_id] = $unit->price;
    }

    foreach ($price_data as $unit_id => $data) {
      if (isset($data['selected'])) {
        $price = floatval($data['price']);

        // If unit exists and price has changed, update it
        if (isset($existing_unit_ids[$unit_id])) {
          if ($existing_unit_ids[$unit_id] != $price) {
            $sql = "UPDATE product_price_unit SET price = ? WHERE product_id = ? AND price_unit_id = ?";
            $stmt = self::$database->prepare($sql);
            $stmt->bind_param("dii", $price, $product_id, $unit_id);
            $stmt->execute();
            $stmt->close();
          }
          // Remove from existing list to prevent deletion
          unset($existing_unit_ids[$unit_id]);
        } else {
          // Insert new price unit if it doesn't exist
          $sql = "INSERT INTO product_price_unit (product_id, price_unit_id, price) VALUES (?, ?, ?)";
          $stmt = self::$database->prepare($sql);
          $stmt->bind_param("iid", $product_id, $unit_id, $price);
          $stmt->execute();
          $stmt->close();
        }
      }
    }

    // Delete unchecked price units
    if (!empty($existing_unit_ids)) {
      $unit_ids_to_delete = implode(',', array_keys($existing_unit_ids));
      $sql = "DELETE FROM product_price_unit WHERE product_id = ? AND price_unit_id IN ($unit_ids_to_delete)";
      $stmt = self::$database->prepare($sql);
      $stmt->bind_param("i", $product_id);
      $stmt->execute();
      $stmt->close();
    }
  }
}
