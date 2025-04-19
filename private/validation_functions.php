<?php

/**
 * Returns true if the value is not set or is an empty string after trimming.
 *
 * @param mixed $value
 * @return bool
 */
function is_blank($value)
{
  return !isset($value) || trim($value) === '';
}

/**
 * Returns true if the value is set and not blank.
 *
 * @param mixed $value
 * @return bool
 */
function has_presence($value)
{
  return !is_blank($value);
}

/**
 * Checks whether a string's length is greater than a given minimum.
 *
 * @param string $value The string to check.
 * @param int $min The minimum length (exclusive).
 * @return bool True if the string is longer than $min, false otherwise.
 */
function has_length_greater_than($value, $min)
{
  $length = strlen($value);
  return $length > $min;
}

/**
 * Checks whether a string's length is less than a given maximum.
 *
 * @param string $value The string to check.
 * @param int $max The maximum length (exclusive).
 * @return bool True if the string is shorter than $max, false otherwise.
 */
function has_length_less_than($value, $max)
{
  $length = strlen($value);
  return $length < $max;
}

/**
 * Checks whether a string's length matches an exact value.
 *
 * @param string $value The string to check.
 * @param int $exact The exact length the string must match.
 * @return bool True if the string length equals $exact, false otherwise.
 */
function has_length_exactly($value, $exact)
{
  $length = strlen($value);
  return $length == $exact;
}

/**
 * Validates that a string meets specified length requirements.
 *
 * @param string $value The string to validate.
 * @param array $options Associative array with keys: 'min', 'max', or 'exact'.
 * @return bool
 */
function has_length($value, $options)
{
  if (isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
    return false;
  }
  if (isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
    return false;
  }
  if (isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
    return false;
  }
  return true;
}

/**
 * Checks whether the value exists in the given set.
 *
 * @param mixed $value
 * @param array $set
 * @return bool
 */
function has_inclusion_of($value, $set)
{
  return in_array($value, $set);
}

function has_exclusion_of($value, $set)
{
  return !in_array($value, $set);
}

function has_string($value, $required_string)
{
  return strpos($value, $required_string) !== false;
}

/**
 * Validates whether a string is a properly formatted email address.
 *
 * @param string $value
 * @return bool
 */
function has_valid_email_format($value)
{
  $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
  return preg_match($email_regex, $value) === 1;
}

/**
 * Checks if the email address is unique in the database, excluding a specific user ID.
 *
 * @param string $email Email address to check.
 * @param int|string $current_id Optional user ID to exclude.
 * @return bool True if unique or belongs to current user; false otherwise.
 */
function has_unique_email($email, $current_id = "0")
{
  $user = User::find_by_email($email);

  if ($user === false || $user->user_id == $current_id) {
    return true;
  } else {
    return false;
  }
}
