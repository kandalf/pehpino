<?php
	require_once('IniReader.php');
	/**
	 * Settings INI reader class.
	 * @name 	TestHelperSettings
	 * @author 	Leonardo Mateo
	 */
	class TestHelperSettings extends IniReader 
	{
		public static function get($key)
		{
			if(is_null(self::$_file))
				self::$_file = "conf/settings.ini";

			return parent::get($key);
		}
	}
?>
