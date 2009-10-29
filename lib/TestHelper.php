<?php
	require_once('TestHelperSettings.php');

	class TestHelper
	{
		const BASE_INCLUDE_DIR 		= '../lib';
		const SETTINGS_INCLUDE_DIR 	= '..';

		private static $_instance;
		private static $_includePath;


		private function __construct()
		{
			self::init();
		}

		public static function init()
		{
			if(!self::$_includePath)
				self::$_includePath = self::_buildIncludePath();

			if (!is_null(TestHelperSettings::get("database")))
				Database::set(self::_getDatabaseAdapter(TestHelperSettings::get("database")));

		}

		private static function _buildIncludePath()
		{
			$dHnd = opendir(self::BASE_INCLUDE_DIR);
			$entries = array(self::SETTINGS_INCLUDE_DIR, self::BASE_INCLUDE_DIR);
			
			if($dHnd)
			{
				while(($entry = readdir($dHnd)) !== false)
				{
					if (is_dir(self::BASE_INCLUDE_DIR . "/" . $entry) && $entry != "." && $entry != ".."){
						$entries[] = self::BASE_INCLUDE_DIR . "/" . $entry;
					}
				}
				closedir($dHnd);
			}

			return join($entries, ":");
		}

		private static function _getDatabaseAdapter($settings)
		{
			if(array_key_exists("adapter", $settings))
			{
				$adapter = $settings["adapter"];
				unset($settings["adapter"]);
				$db = Zend_Db::factory($adapter, $settings);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				return $db;
			}
			else
			{
				throw new Exception("DB Adapter is not specified", ErrorCodes::MISSING_REQUIRED_SETTING);
			}

		}

		public static function getInstance()
		{
			if(is_null(self::$_instance))
				self::$_instance = new TestHelper();
			return self::$_instance;
		}

		public static function getIncludePath()
		{
			self::init();
			return self::$_includePath;
			
		}

		public static function parseClassName($className)
		{
			$path = split("_", $className);

			return  join($path, "/") . ".php";
			
		}
	}
?>
