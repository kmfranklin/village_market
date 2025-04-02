<?php
require_once('../private/initialize.php');

// Handle logout
$session->logout();
redirect_to(url_for('/index.php'));
