<?php
  Given("I have $amount objects named $commaSeparatedNames") {
    $objects = array();
    foreach(split($commaSeparatedNames, ",") as $name)
    {
      $obj = new stdClass();
      $obj->name = $name;
      $objects[] = $obj;
    }
  }

  When ("I click $link") {
    echo "Click $link";
  }

  Given ("I am on the $name page") {
    echo $name;
  }

?>
