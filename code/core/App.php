<?php
    namespace Core;

    use Core\Registry as Registry;

	class App {

        public static $app;

		public function __construct ()
        {
			$query = trim($_SERVER['QUERY_STRING'], '/');
			session_start();
			var_dump($query);
			self::$app = Registry::getInstance();
		}

		public static function getApp ()
        {
            return self::$app;
        }
	}


?>