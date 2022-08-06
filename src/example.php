<?php 

require_once "MediumApi/MediumApi.php";
require_once "MediumApi/Env.php";

use src\MediumApi\MediumApi;
use src\MediumApi\Env;


$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new MediumApi();

// $api->getUserId();

// $api->getUserArticlesIds();

// $api->getUserArticlesContents();

// $api->getUserArticlesMarkdowns();

// $api->getUserArticlesInfos();

//$api->getUserArticles();

//var_dump($api);

?>