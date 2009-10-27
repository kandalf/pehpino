<?php
  
  class FeatureParser {
    private $_file;
    private $_featurePattern;

    public function __construct($featureFile)
    {
      $this->_file = fopen($featureFile, "r");
      $this->_featurePattern = "/^\s+((Given|And)|(When|And)|(Then|And))(.+)$/";

      $this->parse();
    }

    public function __destruct()
    {
      fclose($this->_file);
    }

    public function parse()
    {
      $matches = array();
      while($line = fgets($this->_file))
      {
        preg_match($this->_featurePattern, $line, $matches);
        if(count($matches) > 0)
        {
          $sentenceParts = split(" ", trim($matches[1]));
          echo $sentenceParts[0];
          echo "   " .  $matches[count($matches) - 1] . "\n";
        }
      }
    }
  }

?>
