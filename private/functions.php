<?php

function url_for($script_path)
{
  if ($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
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

function display_delete_modal($entity_type, $delete_url, $entity_id, $user_id, $entity_name)
{
?>
  <div id="delete-modal-<?php echo h($entity_type) . '-' . h($entity_id); ?>" class="modal" style="display: none;">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Confirm Deletion</h2>
      <p class="delete-message">Are you sure you want to delete this <?php echo h($entity_type); ?>: "<strong><?php echo h($entity_name); ?></strong>"?</p>
      <form class="delete-form" action="<?php echo h($delete_url); ?>" method="POST">
        <input type="hidden" name="entity_type" value="<?php echo h($entity_type); ?>">
        <input type="hidden" name="entity_id" class="delete-entity-id" value="<?php echo h($entity_id); ?>">
        <div class="modal-buttons">
          <button type="submit" class="danger-button">Yes, Delete</button>
          <button type="button" class="cancel-button close-modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
<?php
}

function display_suspend_modal($entity_type, $suspend_url, $vendor_id, $user_id, $entity_name)
{
  include(SHARED_PATH . '/suspend_modal.php');
}

function display_restore_modal($entity_type, $restore_url, $user_id, $entity_name)
{
  include(SHARED_PATH . '/restore_modal.php');
}
