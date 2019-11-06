<?php
    namespace Core;

    use Error;

    class App {
        private static $app;

	    private $container;
        private $params;

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

        public function container() { return $this->container; }
        public function params () { return $this->params; }

		private function __construct ()
        {
            // enable Error handling
            new ErrorHandler();

            // DI container
            $this->container = new Container();
            $this->params = Params::getInstance();
            $this->setParams('params.php');

            // starting session
			session_start();
		}

		private function guardParamsValid(array $params)
        {
            if (empty($params)) throw new Error('Application parameters are invalid!');
        }

        private function setParams($file)
        {
            $params = require_once CONF . '/' . $file;
            $this->guardParamsValid($params);
            foreach ($params as $key => $value) {
                $this->params->set($key, $value);
            }
        }
	}


?>