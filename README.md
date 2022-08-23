# MediumApi
A small PHP SDK to interact with Medium's API, both through 
a 3rd-party API via RapidApi (https://rapidapi.com/nishujain199719-vgIfuFHZxVZ/api/medium2/),
and Medium's official REST API (https://github.com/Medium/medium-api-docs),
where the former fills in the gaps of the latter (e.g., allowing a user to obtain their own posts).  

At Packagist:

https://packagist.org/packages/gsmpopovic/medium-api

Install using Composer:

```
composer require gsmpopovic/medium-api

```

NB: 

To interact with the official API, you must generate an access token.

All credentials should be set as environment variables, but, barring this, 
can be manually assigned to the client as properties. 

Examples of API usage can be found in the src/examples directory, 
both for the official Medium API, and 3rd party Rapid API. 

e.g., 

```
// Official Medium API Use cases 

require_once "../MediumApi.php";
require_once "../Env.php";
require_once "../Request.php";

use gsmpopovic\MediumApi\MediumApi;
use gsmpopovic\MediumApi\Env;
use gsmpopovic\MediumApi\Request;


$path = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . ".env";

$env = new Env($path);

$env->load();

$api = new MediumApi(new Request());

$api->getUser();

```