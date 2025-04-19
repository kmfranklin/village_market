<?php
// This file has access to all variables defined on the page
if ($session->is_super_admin()) {
  require(SHARED_PATH . '/admin_header.php');
} elseif ($session->is_admin()) {
  require(SHARED_PATH . '/admin_header.php');
} elseif ($session->is_vendor()) {
  require(SHARED_PATH . '/vendor_header.php');
} else {
  require(SHARED_PATH . '/public_header.php');
}
