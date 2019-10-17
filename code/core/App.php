<?php
    namespace Core;

	class App {

        private static $registry;

		public function __construct ()
        {
            // Error handler power on
            new ErrorHandler();

            // starting session
			session_start();

			// setting application props container
			self::$registry = Registry::getInstance();

			// setting application props (from config/params.php)
			$this->setParams();

			// send user to the page
            Route::handle(REQUESTED_ROUTE);
		}

		private function setParams()
        {
            $params = require_once CONF . '/params.php';
            if (!empty($params)) {
                foreach ($params as $k => $v) {
                    self::$registry->setProperty($k, $v);
                }
            }
        }

		public static function getRegistry ()
        {
            return self::$registry;
        }
	}


?>