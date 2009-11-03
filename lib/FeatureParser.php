<?php
  require_once("lib/StepExecutor.php");

  class FeatureParser 
  {
    private $_file;
    private $_featurePattern;

    const STEP_PATTERN = "/^\s+(Given|When|Then|And)\s+(.+)$/";

    public function __construct($featureFile)
    {
      $this->_file = fopen($featureFile, "r");
    }

    public function __destruct()
    {
      fclose($this->_file);
    }

    public function parse()
    {
      $executor = StepExecutor::getInstance();
      $matches = array();

      while($line = fgets($this->_file))
      {
	$line = str_replace("\n", '', $line);

	if(preg_match(self::STEP_PATTERN, $line, $matches) == 1)
	{
          list($full, $step, $args) = $matches;

	  try {
	    $result = $executor->call($step, $args);

	    if( S_SUCCESS === $result ) {
	      Output::success($line);
	    } elseif( S_PENDING === $result ) {
	      Output::pending($line);
	    }
	  } catch(Exception $ex) {
	    Output::error($ex);
	  }
        }
	else
	{
	  Output::println($line);
	}
      }
    }
  }
?>
