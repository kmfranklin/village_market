<?php
require_once '../private/initialize.php';
$page_title = "Home";
include_header($session, $page_title);

// Fetch homepage content from the database
$sql = "SELECT hc.homepage_id, hc.announcement_text, hc.market_hours, hc.contact_phone, 
               hc.contact_email, hc.contact_mailing_address, hc.contact_city, hc.contact_zip, 
               s.state_abbreviation AS contact_state, hc.hero_image_id
        FROM homepage_content hc
        LEFT JOIN state s ON hc.contact_state = s.state_id
        LIMIT 1";

$result = $database->query($sql);
$homepage = $result->fetch_assoc();

// Assign variables for use in the page
$hero_image_id = $homepage['hero_image_id'] ?? null;
$announcement = $homepage['announcement_text'] ?? '';
$market_hours = $homepage['market_hours'] ?? 'Unavailable';
$contact_phone = $homepage['contact_phone'] ?? 'N/A';
$contact_email = $homepage['contact_email'] ?? 'N/A';
$contact_mailing_address = $homepage['contact_mailing_address'] ?? '';
$contact_city = $homepage['contact_city'] ?? '';
$contact_state = $homepage['contact_state'] ?? '';
$contact_zip = $homepage['contact_zip'] ?? '';

// Fetch hero image URL from cms_image table
$hero_image_url = 'default_hero.jpg'; // Default fallback
if (!empty($hero_image_id)) {
  $sql = "SELECT image_url FROM cms_image WHERE image_id = ?";
  $stmt = $database->prepare($sql);
  $stmt->bind_param("i", $hero_image_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($row = $result->fetch_assoc()) {
    $hero_image_url = $row['image_url'];
  }
  $stmt->close();
}

// Apply Cloudinary transformation
$hero_image_large = get_cloudinary_image($hero_image_url, 1200, 600);
$hero_image_medium = get_cloudinary_image($hero_image_url, 800, 400);
$hero_image_small = get_cloudinary_image($hero_image_url, 400, 200);

// Fetch the next market date
$next_market_date = get_next_market_date();

// Fetch 3 random products
$sql = "SELECT product_id, product_name, product_description, product_image_url 
        FROM product 
        WHERE is_active = 1 
        ORDER BY RAND() 
        LIMIT 3";

$result = $database->query($sql);
$products = [];

while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}
?>

<!-- Hero Section -->
<section id="hero">
  <?php
  $hero_image_large = get_cloudinary_image($hero_image_url, 1200, 600);
  $hero_image_medium = get_cloudinary_image($hero_image_url, 800, 400);
  $hero_image_small = get_cloudinary_image($hero_image_url, 400, 200);
  ?>

  <img
    src="<?= htmlspecialchars($hero_image_large['url']); ?>"
    srcset="
    <?= htmlspecialchars($hero_image_small['url']); ?> 400w,
    <?= htmlspecialchars($hero_image_medium['url']); ?> 800w,
    <?= htmlspecialchars($hero_image_large['url']); ?> 1200w"
    sizes="(max-width: 480px) 400px, 
         (max-width: 1024px) 800px, 
         1200px"
    width="1200"
    height="600"
    class="hero-image lazyload"
    alt="Farmers Market">

  <div class="hero-content">
    <h1 class="hero-heading">Welcome to the Village Market!</h1>
    <p class="hero-subheading">Celebrate local flavors and support small businesses in our community.</p>

    <div class="hero-buttons">
      <a href="products.php" class="hero-btn">Shop the Market</a>
      <a href="vendors.php" class="hero-btn">Explore Vendors</a>
    </div>

    <?php if (!empty($announcement)) : ?>
      <div class="announcement">
        <p><?php echo htmlspecialchars($announcement); ?></p>
      </div>
    <?php endif; ?>
  </div>

</section>

<!-- Market Info Section -->
<section id="market-info" class="container my-5">
  <div class="row align-items-center justify-content-around">

    <div class="col-md-6">
      <h2 class="text-left">Discover Fresh, Local Goods</h2>
      <p class="text-left">Experience the heart of our community at the Village Market, where local farmers, bakers, and artisans come together to share their passion for fresh, high-quality goods.</p>
      <p class="text-left">Join us in celebrating local flavors and supporting small businesses. We're proud to be a gathering place for friends, families, and neighbors.</p>
    </div>

    <!-- Right Column: Market Event Box -->
    <div class="col-md-4">
      <div class="market-event p-4 shadow rounded">
        <h2 class="mb-3">Next Market Event</h2>

        <?php if (!empty($next_market_date)) : ?>
          <p class="market-date-text">
            <strong><?php echo date('F j, Y', strtotime($next_market_date)); ?></strong>
          </p>
        <?php else : ?>
          <p>No upcoming market dates available.</p>
        <?php endif; ?>

        <h3 class="mt-3">Market Hours</h3>
        <p><?php echo htmlspecialchars($market_hours); ?></p>

        <h3 class="mt-3">Location</h3>
        <p><?php echo htmlspecialchars($contact_mailing_address) . ', ' . htmlspecialchars($contact_city) . ', ' . htmlspecialchars($contact_state) . ' ' . htmlspecialchars($contact_zip); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- Featured Products Section -->
<section id="featured-products" class="container my-5">
  <h2 class="text-center">Discover Fresh Finds</h2>
  <div class="row justify-content-center">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <img src="<?= htmlspecialchars($product['product_image_url']) ?>"
            class="card-img-top"
            alt="<?= htmlspecialchars($product['product_name']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
            <p class="card-text"><?= htmlspecialchars(substr($product['product_description'], 0, 100)) ?></p>
            <a href="products/view.php?id=<?= $product['product_id'] ?>" class="btn btn-primary">View Product</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="text-center mt-4">
    <a href="products.php" class="btn btn-success">View All Products</a>
  </div>
</section>

<?php include(SHARED_PATH . '/footer.php'); ?>
