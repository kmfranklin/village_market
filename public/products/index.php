<?php
require_once('../../private/initialize.php');

$page_title = 'All Products';
include_header($session, $page_title);

echo display_session_message();

// Fetch active products
$sql = "SELECT 
          product.product_id,
          product.product_name,
          product.product_description,
          product.product_image_url,
          vendor.business_name
        FROM product
        JOIN vendor ON product.vendor_id = vendor.vendor_id
        WHERE product.is_active = 1
        ORDER BY product.product_name ASC";

$product_result = $database->query($sql);
if (!$product_result) {
  die("Database query failed: " . $database->error);
}

?>

<main class="container my-4">
  <h1 class="mb-4">Browse All Products</h1>

  <!-- Filter/Search UI will go here -->
  <section id="products" class="container my-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php foreach ($product_result as $product): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm d-flex flex-column">
            <img src="<?= htmlspecialchars($product['product_image_url']) ?>"
              class="card-img-top"
              alt="<?= htmlspecialchars($product['product_name']) ?>">
            <div class="card-body homepage-product-card">
              <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars(substr($product['product_description'], 0, 100)) ?></p>
              <p class="text-muted small">Sold by: <strong><?= htmlspecialchars($product['business_name']); ?></strong></p>
              <a href="products/view.php?id=<?= $product['product_id'] ?>" class="btn btn-primary w-100">View Product</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
