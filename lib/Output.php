<?php

  /**
   * Class that deals with information output (STDOUT, STDERR)
   */
class Output
{
  /**
   * This is a static class, it cannot be instantiated.
   */
  private function __construct() {
  }

  /**
   * Colors
   */
  const RED = "\033[0;31m";
  const BLUE = "\033[0;34m";
  const GREEN = "\033[1;32m";
  const YELLOW = "\033[1;33m";
  const BROWN = "\033[0;33m";

  public static function println($message, $color = null) {
    if( !is_null($color) )
      echo $color;

    echo $message;

    if( !is_null($color) )
      echo "\033[0m";

    echo "\n";
  }


  public static function error($message) {
    self::println($message, self::RED);
  }

  public static function success($message) {
    self::println($message, self::GREEN);
  }

  public static function pending($message) {
    self::println($message, self::BROWN);
  }
}
?>
