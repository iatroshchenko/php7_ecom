<?

require_once dirname(__DIR__) . '/config/init.php';

use \Core\App;

$app = new App();
dd($_SERVER['REQUEST_URI']);
//dd($app::getRegistry()::all());