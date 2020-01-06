<?php

class ErrorRender
{
  public static function show($text)
  {
    return "<h1 class='display-4 error text-center'>$text</h1>";
  }
}
