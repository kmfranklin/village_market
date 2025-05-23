<?php
require_once('../../private/initialize.php');
$page_title = 'Browse Products';
require_once(SHARED_PATH . '/include_header.php');

echo display_session_message();

// Get next market date
$market_date = get_next_market_date();
$next_market_date_id = get_next_market_date_id();

// Get filtered products
require_once(SHARED_PATH . '/product_filters.php');
$sql = get_filtered_products_query($database, [
  'next_market_date_id' => $next_market_date_id
]);

$product_result = $database->query($sql);

// Get filter dropdowns
$filter_results = get_filter_dropdowns($database);
$vendor_result = $filter_results['vendor_result'];
$category_result = $filter_results['category_result'];
?>

<div class="container my-5">
  <h1 class="mb-4">Browse Products Available at the Next Market</h1>
  <p class="lead">Showing products from vendors confirmed for <?php echo date('F j, Y', strtotime($market_date)); ?>.</p>

  <?php
  $is_public_page = true;
  include(SHARED_PATH . '/product_filter_form.php');
  ?>

  <section id="products" class="container my-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <?php
      if ($product_result->num_rows === 0) {
        echo "No products found";
      }
      ?>
      <?php foreach ($product_result as $product): ?>
        <div class="col"
          data-name="<?= htmlspecialchars(strtolower($product['product_name'])) ?>"
          data-vendor="<?= $product['vendor_id'] ?>"
          data-category="<?= $product['category_id'] ?>">
          <div class="card h-100 shadow-sm d-flex flex-column">
            <?php
            $image_url = !empty($product['product_image_url'])
              ? htmlspecialchars($product['product_image_url'])
              : url_for('/assets/images/product_placeholder.webp');
            ?>
            <img src="<?= $image_url ?>"
              class="card-img-top"
              alt="<?= htmlspecialchars($product['product_name']) ?>"
              onerror="this.onerror=null;this.src='<?= url_for('/assets/images/product_placeholder.png'); ?>';">

            <div class="card-body homepage-product-card">
              <h5 class="card-title text-capitalize">
                <?= htmlspecialchars($product['product_name']) ?>
              </h5>
              <?php
              $desc = $product['product_description'];
              $truncated = strlen($desc) > 100 ? substr($desc, 0, 100) . '…' : $desc;
              ?>
              <p class="card-text"><?= htmlspecialchars($truncated) ?></p>
              <p class="text-muted small">
                Category: <strong><?= htmlspecialchars($product['category_name']); ?></strong><br>
                Sold by: <strong><?= htmlspecialchars($product['business_name']); ?></strong>
              </p>
              <a href="view.php?id=<?= $product['product_id'] ?>" class="btn btn-primary w-100">View Product</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div id="product-pagination" class="pagination justify-content-center mt-4"></div>

    <p id="no-results" class="text-center text-muted mt-5">
      No matching products found. Try adjusting your filters.
    </p>
  </section>
</div>
</main>

<?php include(SHARED_PATH . '/footer.php'); ?>
