<?php
class FormSanitizer
{

  public static function sanitizeString($input)
  {
    $input = strip_tags($input);
    $input = trim($input);
    $input = strtolower($input);
    $input = ucwords($input);
    return $input;
  }

  public static function sanitizeUsername($input)
  {
    $input = strip_tags($input);
    $input = trim($input);
    return $input;
  }

  public static function sanitizePassword($input)
  {
    $input = strip_tags($input);
    return $input;
  }

  public static function sanitizeEmail($input)
  {
    # you can add functionality later 
    $input = strip_tags($input);
    $input = str_replace(" ", "", $input);
    return $input;
  }
}
