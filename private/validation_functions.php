<?php

/**
 * Check if a string is blank.
 *
 * @param string $value The value to check.
 * @return bool True if blank, false otherwise.
 */
function is_blank($value)
{
  return !isset($value) || trim($value) === '';
}

/**
 * Check if a string is present (not blank).
 *
 * @param string $value The value to check.
 * @return bool True if present, false otherwise.
 */
function has_presence($value)
{
  return !is_blank($value);
}

/**
 * Check if a string's length is greater than a minimum value.
 *
 * @param string $value The string to check.
 * @param int $min The minimum length.
 * @return bool True if length is greater, false otherwise.
 */
function has_length_greater_than($value, $min)
{
  $length = strlen($value);
  return $length > $min;
}

/**
 * Check if a string's length is less than a maximum value.
 *
 * @param string $value The string to check.
 * @param int $max The maximum length.
 * @return bool True if length is less, false otherwise.
 */
function has_length_less_than($value, $max)
{
  $length = strlen($value);
  return $length < $max;
}

/**
 * Check if a string's length matches an exact value.
 *
 * @param string $value The string to check.
 * @param int $exact The exact length.
 * @return bool True if length matches, false otherwise.
 */
function has_length_exactly($value, $exact)
{
  $length = strlen($value);
  return $length == $exact;
}

/**
 * Check if a string's length falls within specified bounds.
 *
 * @param string $value The string to check.
 * @param array $options An array with 'min', 'max', or 'exact' keys.
 * @return bool True if the length matches the criteria, false otherwise.
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
 * Check if a value is included in a predefined set.
 *
 * @param mixed $value The value to check.
 * @param array $set The predefined set.
 * @return bool True if included, false otherwise.
 */
function has_inclusion_of($value, $set)
{
  return in_array($value, $set);
}

/**
 * Check if a value is excluded from a predefined set.
 *
 * @param mixed $value The value to check.
 * @param array $set The predefined set.
 * @return bool True if excluded, false otherwise.
 */
function has_exclusion_of($value, $set)
{
  return !in_array($value, $set);
}

/**
 * Check if a string contains a required substring.
 *
 * @param string $value The string to check.
 * @param string $required_string The required substring.
 * @return bool True if substring is found, false otherwise.
 */
function has_string($value, $required_string)
{
  return strpos($value, $required_string) !== false;
}

/**
 * Check if an email has a valid format.
 *
 * @param string $value The email to validate.
 * @return bool True if format is valid, false otherwise.
 */
function has_valid_email_format($value)
{
  $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
  return preg_match($email_regex, $value) === 1;
}

/**
 * Check if a username is unique in the database.
 *
 * @param string $username The username to check.
 * @param int|string $current_id The current user ID (0 for new users).
 * @return bool True if unique, false otherwise.
 */
function has_unique_username($username, $current_id = "0")
{
  $user = User::find_by_username($username);

  if ($user === false || $user->user_id == $current_id) {
    // Username is unique
    return true;
  } else {
    // Username is not unique
    return false;
  }
}
