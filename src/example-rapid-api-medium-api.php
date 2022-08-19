<?php 

require_once "RapidApiMediumApi.php";
require_once "Env.php";
require_once "Request.php";

use gsmpopovic\MediumApi\RapidApiMediumApi;
use gsmpopovic\MediumApi\Env;
use gsmpopovic\MediumApi\Request;


$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new RapidApiMediumApi(new Request);

$api->getUserId();

// $api->getUserArticlesIds();

// $api->getUserArticlesContents();

// $api->getUserArticlesMarkdowns();

// $api->getUserArticlesInfos();

//$api->getUserArticles();

var_dump($api);