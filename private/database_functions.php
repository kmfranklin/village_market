<?php

/**
 * Establish a connection to the database.
 *
 * @return mysqli Database connection object.
 * @throws Exception If the connection fails.
 */
function db_connect()
{
  $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

  if ($connection->connect_errno) {
    throw new Exception("Database connection failed: " . $connection->connect_error);
  }

  // Set character encoding to UTF-8
  $connection->set_charset("utf8mb4");

  return $connection;
}

/**
 * Confirm that a database query executed successfully.
 *
 * @param mysqli_result|bool $result The result of a query.
 * @return void
 * @throws Exception If the query fails.
 */
function confirm_query($result)
{
  if (!$result) {
    throw new Exception("Database query failed.");
  }
}

/**
 * Close the database connection if it is open.
 *
 * @param mysqli|null $connection Database connection object.
 * @return void
 */
function db_disconnect($connection)
{
  if (isset($connection)) {
    $connection->close();
  }
}
