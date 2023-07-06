<?php 

/*

// Rapid Api Medium Api use cases 

require_once "../RapidApiMediumApi.php";
require_once "../Env.php";
require_once "../Request.php";


// if using Composer, 
// require "vendor/autoload.php"; 
 
use gsmpopovic\MediumApi\RapidApiMediumApi;
use gsmpopovic\MediumApi\Env;
use gsmpopovic\MediumApi\Request;

// This path is relative, so point it to wherever your .env file is,
// if needed. 
$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new RapidApiMediumApi(new Request);

$api->getUserId();

$api->getUserArticlesIds();

$api->getUserArticlesContents();

$api->getUserArticlesMarkdowns();

$api->getUserArticlesInfos();

$api->getUserArticles();

var_dump($api);
 
*/
?>