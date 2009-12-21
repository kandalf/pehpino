<?php

define('S_SUCCESS', 0);
define('S_PENDING', 1);

	class StepExecutor
	{
		const STATE_FEATURE		= 'Feature';
		const STATE_SCENARIO	= 'Scenario';
		const STATE_GIVEN		= 'Given';
		const STATE_WHEN		= 'When';
		const STATE_THEN		= 'Then';

    const STEPS_DIR       = 'steps';

		private static $_instance;
		protected $_state;
    private $_steps;
		
		protected function __construct()
		{
			$this->_state = self::STATE_FEATURE;
      $this->_initializeSteps();
      $this->_loadSteps();
		}

    private function _initializeSteps()
    {
      $this->_steps = array();
      $this->_steps[self::STATE_GIVEN]  = array();
      $this->_steps[self::STATE_WHEN]   = array();
      $this->_steps[self::STATE_THEN]   = array();
    }

    private function _loadSteps()
    {
      if (is_dir(self::STEPS_DIR))
      {
        $dHnd = opendir(self::STEPS_DIR);
        while (false !== ($file = readdir($dHnd)))
        {
          if (!is_dir($file))
            $this->_parseFile($file);
        }
        closedir($dHnd);

      } else {
        throw new Exception("Invalid Steps directory");
      }
    }

    private function _parseFile($fileName)
    {
      $contents = file_get_contents(self::STEPS_DIR . "/" . $fileName);
      $this->_createFunctionsFor(self::STATE_GIVEN, $contents);
      $this->_createFunctionsFor(self::STATE_WHEN, $contents);
      $this->_createFunctionsFor(self::STATE_THEN, $contents);
    }

    private function _createFunctionsFor($state, $contents)
    {
      $matches = array();
      $functionPrototype = "$state\s*\((.+)\)";
      $functionBody = "(\s*{\s*(.+\n.*)*})";
      $function = $functionPrototype . $functionBody;
      $parameters = "((\s\\$([a-z]|[A-Z])+\s*))";

      preg_match_all("/$function/", $contents, $matches);
      
      $steps = $matches[1];
      $functionBodies = $matches[2];
      $evalStrings = array();

      foreach($steps as $idx => $step) 
      {
        preg_match_all("/$parameters/", $step, $matches);
        $functionName = str_replace('"', "", $this->_parseFunctionName($step));
        $this->_steps[$state][$functionName] = "function " . $functionName . "(" . trim(implode($matches[0], ",")) . ")" . $functionBodies[$idx];
      }

    }

    private function _parseFunctionName($step)
    {
      $parameters = "((\s\\$([a-z]|[A-Z])+\s*))";
      return trim(preg_replace("/$parameters|\s/", "_", $step));
    }

    private function _execute($step)
    {
      //isolate parameters and function name parsing from step
      $matches = array();
      $parameters = "((\"(([a-z]|[A-Z])+\s*)+\"))";
	  $step = trim($step);
      preg_match_all("/$parameters/", $step, $matches);
	  $functionName = str_replace(" ", "_", preg_replace("/$parameters/", "", $step));
     
/*  
	  print_r("Function: $functionName\n");
	  print_r($matches);
	  print_r("------\n");
  */    
      if (array_key_exists($functionName, $this->_steps[$this->_state]))
	{
	  //find parameters to eval the function call with them
	  //Output::success('Executing ' . $functionName . "(" . implode($matches[0], ",") . ")");
	  //Output::pending($this->_steps[$this->_state][$functionName]);
	
	  $evalString = $functionName . "(" . implode($matches[0], ",") . ");";
	  //print_r($this->_steps[$this->_state]);
	  eval($evalString);
	  return S_SUCCESS;
	}
      else
	{
	  //Output::error('Undefined step ' . print_r($this->_steps[$this->_state], true));
	  //exit;
	  //throw new UndefinedStepException($functionName);
	  return S_PENDING;
	}
    }
    
    protected function _setState($state){
        $this->_state = $state;
        //add check to know if it has been already evaluated
        eval(implode($this->_steps[$this->_state], " "));
    }

    public static function getInstance()
    {
      if (is_null(self::$_instance))
	self::$_instance = new StepExecutor();  
      return self::$_instance;
    }

    public function getSteps()
    {
      return $this->_steps;
    }

    public function call($state, $step)
    {
        switch($state)
        {
            case self::STATE_GIVEN:
            case self::STATE_WHEN:
            case self::STATE_THEN:
                $this->_setState($state);
            default:
                return $this->_execute($step);
        }
    }

	}
?>
