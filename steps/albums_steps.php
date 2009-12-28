<?php

  Given("I am logged in") {
    $logged_in = true;
  }

When("I go to the $p page") {
    echo "I went to $p page...\n";
}

  When("I follow $link") {
	echo "Following $link...\n";
  }

  When("I fill in $field with $value") {
	echo "Filling $field with $value ...\n";
  }

?>
