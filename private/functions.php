<?php

function url_for($script_path)
{
  // Ensure WWW_ROOT does not end with '/'
  $root = rtrim(WWW_ROOT, '/');

  // Ensure $script_path does start with '/'
  $path = ltrim($script_path, '/');

  return $root . '/' . $path;
}

function u($string = "")
{
  return urlencode($string);
}

function raw_u($string = "")
{
  return rawurlencode($string);
}

function h($string = "")
{
  return htmlspecialchars($string);
}

function error_404()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

function error_500()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}

function redirect_to($location)
{
  header("Location: " . $location);
  exit;
}

function is_post_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request()
{
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function include_header($session)
{
  if ($session->is_super_admin()) {
    include(SHARED_PATH . '/admin_header.php');
  } elseif ($session->is_admin()) {
    include(SHARED_PATH . '/admin_header.php');
  } elseif ($session->is_vendor()) {
    include(SHARED_PATH . '/vendor_header.php');
  } else {
    include(SHARED_PATH . '/public_header.php');
  }
}

function get_states()
{
  global $database;
  $sql = "SELECT * FROM state ORDER BY state_name ASC";
  $result = $database->query($sql);
  $states = [];

  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $states[] = $row;
    }
    $result->free();
  } else {
    die("Database query failed: " . $database->error);
  }

  return $states;
}

function display_delete_modal($entity_type, $delete_url, $entity_id, $entity_name, $user_id = null)
{
?>
  <div id="delete-modal-<?php echo h($entity_type) . '-' . h($entity_id); ?>" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo h($entity_id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header text-center">
          <h5 class="modal-title w-100">Confirm Deletion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body text-center">
          <p>Are you sure you want to delete this <?php echo h($entity_type); ?>?</p>
          <p class="fw-bold"><?php echo h($entity_name); ?></p>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
          <form class="delete-form w-100 w-sm-auto" action="<?php echo h($delete_url); ?>" method="POST">
            <input type="hidden" name="entity_type" value="<?php echo h($entity_type); ?>">
            <input type="hidden" name="entity_id" class="delete-entity-id" value="<?php echo h($entity_id); ?>">

            <!-- Only include user_id if applicable (for vendors, not products) -->
            <?php if (!is_null($user_id)) : ?>
              <input type="hidden" name="user_id" value="<?php echo h($user_id); ?>">
            <?php endif; ?>

            <button type="submit" class="btn btn-danger w-100">Yes, Delete</button>
          </form>

          <!-- Ensure the cancel button has `data-bs-dismiss="modal"` -->
          <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancel</button>

        </div>

      </div>
    </div>
  </div>
<?php
}

function display_suspend_modal($entity_type, $suspend_url, $entity_id, $user_id, $entity_name)
{
?>
  <div class="modal fade" id="suspend-modal-<?php echo h($entity_type) . '-' . h($entity_id); ?>" tabindex="-1" aria-labelledby="suspendModalLabel-<?php echo h($entity_id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title">Confirm Suspension</h5>
          <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body text-center">
          <p class="suspend-message">Are you sure you want to suspend "<strong><?php echo h($entity_name); ?></strong>"?</p>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
          <form class="suspend-form w-100 w-sm-auto" action="<?php echo h($suspend_url); ?>" method="POST">
            <input type="hidden" name="vendor_id" class="suspend-vendor-id" value="<?php echo h($entity_id); ?>">
            <input type="hidden" name="user_id" class="suspend-user-id" value="<?php echo h($user_id); ?>">
            <button type="submit" class="btn btn-danger w-100 fw-bold">Yes, Suspend</button>
          </form>
          <button type="button" class="btn btn-secondary w-100 w-sm-auto close-modal" data-bs-dismiss="modal">Cancel</button>
        </div>

      </div>
    </div>
  </div>
<?php
}

function display_restore_modal($entity_type, $restore_url, $user_id, $entity_name)
{
?>
  <div id="restore-modal-user-<?php echo h($user_id); ?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header text-center">
          <h5 class="modal-title w-100">Confirm Restore</h5>
          <button type="button" class="btn-close close-modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body text-center">
          <p>Are you sure you want to restore "<strong><?php echo h($entity_name); ?></strong>"?</p>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer d-grid gap-2 d-sm-flex justify-content-center">
          <form class="restore-form w-100 w-sm-auto" action="<?php echo h($restore_url); ?>" method="POST">
            <input type="hidden" name="user_id" class="restore-user-id" value="<?php echo h($user_id); ?>">
            <button type="submit" class="btn btn-primary w-100">Yes, Restore</button>
          </form>
          <button type="button" class="btn btn-secondary w-100 w-sm-auto close-modal">Cancel</button>
        </div>

      </div>
    </div>
  </div>
<?php
}


/**
 * Fetches the next available market date.
 * If no future market dates exist, it generates new ones.
 *
 * @return string|null - The next upcoming market date or null if not found.
 */
function get_next_market_date()
{
  global $database;

  // Query for the next available market date
  $sql = "SELECT market_date FROM market_date WHERE market_date >= CURDATE() ORDER BY market_date ASC LIMIT 1";
  $result = $database->query($sql);
  $next_market = $result->fetch_assoc();

  // If no upcoming date exists, generate new dates
  if (!$next_market) {
    generate_market_dates();
    // Re-run the query after inserting new dates
    $result = $database->query($sql);
    $next_market = $result->fetch_assoc();
  }

  return $next_market ? $next_market['market_date'] : null;
}

/**
 * Generates market dates for the next 6 months if none exist.
 * Adjust the day based on your actual market schedule.
 */
function generate_market_dates($months_ahead = 6)
{
  global $database;

  $num_weeks = $months_ahead * 4; // Approximate weekly markets for the next 6 months
  $today = new DateTime();
  $today->modify('next Saturday'); // Adjust this for your actual market day

  for ($i = 0; $i < $num_weeks; $i++) {
    $market_date = $today->format('Y-m-d');

    // Check if this date already exists
    $sql = "SELECT COUNT(*) AS count FROM market_date WHERE market_date = ?";
    $stmt = $database->prepare($sql);
    $stmt->bind_param("s", $market_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['count'] == 0) {
      // Insert the new market date
      $insert_sql = "INSERT INTO market_date (market_date, is_active) VALUES (?, 1)";
      $stmt = $database->prepare($insert_sql);
      $stmt->bind_param("s", $market_date);
      $stmt->execute();
      $stmt->close();
    }

    // Move to the next scheduled market day
    $today->modify('+7 days');
  }
}
