<?php

require_once('../private/initialize.php');

$sql_categories = "SELECT * FROM category";
$result_categories = $database->query($sql_categories);

if (!$result_categories) {
  die("Database query failed: " . $database->error);
}

$sql_states = "SELECT * FROM state";
$result_states = $database->query($sql_states);

if (!$result_states) {
  die("Database query failed: " . $database->error);
}

echo "<h1>Welcome to the Village Market</h1>";
echo "<h2>Category List:</h2>";
echo "<ul>";
while ($row = $result_categories->fetch_assoc()) {
  echo "<li>" . htmlspecialchars($row['category_name']) . "</li>";
}
echo "</ul>";


echo "<h2>States List:</h2>";
echo "<p>The Village Market proudly supports local, homegrown, and handmade products. To uphold this commitment, we exclusively partner with vendors based in the following states:</p>";
echo "<ul>";
while ($row = $result_states->fetch_assoc()) {
  echo "<li>" . htmlspecialchars($row['state_name']) . "</li>";
}
echo "</ul>";

$result_categories->free();
$result_states->free();
