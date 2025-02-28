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
}
