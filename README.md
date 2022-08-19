# MediumApi
A small PHP SDK to interact with Medium's API, both through 
a 3rd-party API via RapidApi (https://rapidapi.com/nishujain199719-vgIfuFHZxVZ/api/medium2/),
and Medium's official REST API (https://github.com/Medium/medium-api-docs),
where the former fills in the gaps of the latter (e.g., allowing a user to obtain their own posts).  

At Packagist:

https://packagist.org/packages/gsmpopovic/medium-api

Install using Composer:

composer require gsmpopovic/medium-api

NB: 

To interact with the official API, you must generate an access token.

All credentials should be set as environment variables, but, barring this, 
can be manually assigned to the client as properties. 
