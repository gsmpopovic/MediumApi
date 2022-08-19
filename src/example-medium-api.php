<?php 

// Official Medium API Use cases 

require_once "MediumApi.php";
require_once "Env.php";
require_once "Request.php";

use gsmpopovic\MediumApi\MediumApi;
use gsmpopovic\MediumApi\Env;
use gsmpopovic\MediumApi\Request;


$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new MediumApi(new Request());

$api->getUser();

// $api->getUserPublications();

//     $post = [
//     "title"=> "jhhhhhhhhhhhhhhhhhhhhhhhhh FC",
//     "contentFormat"=> "html",
//     "content"=> "<h1>Liverpool FC</h1><p>You’ll never walk alone.</p>",
//     "canonicalUrl"=> "http://jamietalbot.com/posts/liverpool-fc",
//     "tags"=> ["football", "sport", "Liverpool"],
//     "publishStatus"=> "draft"];
  

// $api->createUserPost($post);

var_dump($api);

?>