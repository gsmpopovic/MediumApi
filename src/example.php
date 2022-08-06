<?php 

require_once "NishuJainMediumApi/NishuJainMediumApi.php";
require_once "NishuJainMediumApi/Env.php";

use src\NishuJainMediumApi\NishuJainMediumApi;
use src\NishuJainMediumApi\Env;


$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new NishuJainMediumApi();

$api->getUserId();

$api->getUserArticlesIds();

$api->getUserArticlesContents();

$api->getUserArticlesMarkdowns();

$api->getUserArticlesInfos();

var_dump($api);

?>