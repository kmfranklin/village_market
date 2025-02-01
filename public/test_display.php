<?php

require_once('../private/initialize.php');

$sql = "SELECT * FROM category";
$result = $database->query($sql);

if (!$result) {
  die("Database query failed: " . $database->error);
}

echo "<h1>Category List</h1>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
  echo "<li>" . htmlspecialchars($row['category_name']) . "</li>";
}
echo "</ul>";

$result->free();
