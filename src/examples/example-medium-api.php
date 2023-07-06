<?php 
/* 
// Official Medium API Use cases 

require_once "../MediumApi.php";
require_once "../Env.php";
require_once "../Request.php";

// if using Composer, 
// require "vendor/autoload.php"; 

use gsmpopovic\MediumApi\MediumApi;
use gsmpopovic\MediumApi\Env;
use gsmpopovic\MediumApi\Request;

// This path is relative, so point it to wherever your .env file is,
// if needed. 
$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new MediumApi(new Request());

$api->getUser();

$api->getUserPublications();

    $post = [
    "title"=> "jhhhhhhhhhhhhhhhhhhhhhhhhh FC",
    "contentFormat"=> "html",
    "content"=> "<h1>Liverpool FC</h1><p>You’ll never walk alone.</p>",
    "canonicalUrl"=> "http://jamietalbot.com/posts/liverpool-fc",
    "tags"=> ["football", "sport", "Liverpool"],
    "publishStatus"=> "draft"];
  

$api->createUserPost($post);

var_dump($api);
 */ 
?>
