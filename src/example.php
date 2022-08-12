<?php 

require_once "MediumApi.php";
require_once "Env.php";
require_once "Request.php";

use gsmpopovic\MediumApi\MediumApi;
use gsmpopovic\MediumApi\Env;


$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new MediumApi();

$api->getUserId();

// $api->getUserArticlesIds();

// $api->getUserArticlesContents();

// $api->getUserArticlesMarkdowns();

// $api->getUserArticlesInfos();

//$api->getUserArticles();

var_dump($api);

?>