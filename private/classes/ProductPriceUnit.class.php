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
}
