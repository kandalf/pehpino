<?php
	class StepExecutor
	{
		const STATE_FEATURE 	= 'Feature';
		const STATE_SCENARIO 	= 'Scenario';
		const STATE_GIVEN 		= 'Given';
		const STATE_WHEN 		= 'When';
		const STATE_THEN 		= 'Then';

		private static $_instance;
		private $_state;

		
		protected function __construct()
		{
			self::$_state = self::STATE_FEATURE;
		}


		public function getInstance()
		{
			if (is_null(self::$_instance))
				self::$_instance = new StepExecutor();	
			return self::$_instance;
		}

		public function call($state, $step)
		{
			
		}
	}
?>
