<?php
    namespace Core;

    use Error;

    class App {
        private static $app;

	    private $container;
        private $params;

        private static $db;

        public static function start()
        {
            self::$app = new self();
            Route::handle(REQUESTED_ROUTE);
        }

        public static function instance()
        {
            if (!self::$app) self::start();
            return self::$app;
        }

        public function container () { return $this->container; }
        public function params () { return $this->params; }

		private function __construct ()
        {
            // enable Error handling
            new ErrorHandler();

            // container and registry
            $this->container = new Container();
            $this->params = Params::getInstance();
            $this->setParams('params.php');

            // Connect to DB
            self::$db = DB::getInstance();

            // starting session
			session_start();
		}

        private function setParams($file)
        {
            $params = require_once CONF . '/' . $file;
            if (!empty($params) && is_array($params)) {
                foreach ($params as $key => $value) {
                    $this->params->set($key, $value);
                }
            } else {
                throw new Error('Invalid params given');
            }
        }
	}


?>