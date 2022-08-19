<?php

namespace gsmpopovic\MediumApi;

class MediumApi
{

    public $official_medium_api_access_token = null;

    public $version = null;

    public $user = [];

    public $base_uri = null;

    public $endpoint = null;

    public $request = null;

    public function __construct($request)
    {

        $this->request = $request;

        $this->setup();

    }

    /* Get environment variables and set request headers. */
    public function setup()
    {

        $this->setVersion();

        $this->setEnvVariables();

        $this->setMediumApiHeaders();

    }

    public function setEnvVariables()
    {

        $this->official_medium_api_access_token = getenv("OFFICIAL_MEDIUM_API_ACCESS_TOKEN");

    }

    /* Set the Api version. */
    public function setVersion($version = null){

        $this->version = $version ?? getenv("OFFICIAL_MEDIUM_API_VERSION"); 

    }

    /* ********************************************************* */
    /* Medium Official API functions */
    /* ********************************************************* */

    /* Set the HTTP headers used to interact with / access the official Medium API. */
    public function setMediumApiHeaders()
    {

        $this->setMediumApiAuthHeaders();

        $this->request->headers["Content-Type"] = "Content-Type: application/json";
        $this->request->headers["Accept-Charset"] = "Accept-Charset: utf-8";
        $this->request->headers["Accept"] = "Accept: application/json";

    }

    /* Set the HTTP headers that authorize whether we can access the official Medium API. */
    public function setMediumApiAuthHeaders()
    {

        $this->request->headers["Authorization"] = "Authorization: Bearer " . $this->official_medium_api_access_token;
        $this->request->headers["Host"] = "Host: api.medium.com";

    }

    /* Unset the HTTP headers that authorize whether we can access the official Medium API. */
    public function unsetMediumApiAuthHeaders()
    {

        unset($this->request->headers["Authorization"]);
        unset($this->request->headers["Host"]);

    }

    /* ********************************************************* */
    /* Functions related to etrieving Medium API user */
    /* ********************************************************* */

    /* Get an object representing the user authorized to access the API. */
    public function getUser()
    {

        $url = "https://api.medium.com/$this->version/me";

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["data"]) && !empty($api_response["data"])) {

            $this->user = $api_response["data"];

        } else {

            // Access token probably isn't set.

            // string(67) "{"errors":[{"message":"An access token is required.","code":6000}]}"

        }

    }

    /* Get an object representing a user's publications. */
    public function getUserPublications()
    {

        if (isset($this->user['id'])) {

            $user_id = $this->user['id'];

            $url = "https://api.medium.com/$this->version/users/$user_id/publications";

            $this->request->get($url);

            $api_response = json_decode($this->request->response, true);

            if (isset($api_response["data"]) && !empty($api_response["data"])) {

                $this->publications = $api_response["data"];

            } else {

                // Access token probably isn't set.

                // string(67) "{"errors":[{"message":"An access token is required.","code":6000}]}"

                // OR

                // Trying to access publications for another user (403)

            }

        } else {

            echo "User ID isn't set.";

        }

    }

    /* ********************************************************* */
    /* Functions related to etrieving Medium API user */
    /* ********************************************************* */

    /* ********************************************************* */
    /* Functions related to creating a post. */
    /* ********************************************************* */

    /* Create a post for a user. */
    public function createUserPost($post = [])
    {
        // https://github.com/Medium/medium-api-docs#creating-a-post

        if (isset($this->user['id'])) {

            $user_id = $this->user['id'];

            $url = "https://api.medium.com/$this->version/users/$user_id/posts";

            $this->request->post($url, $post);

            $api_response = json_decode($this->request->response, true);

            if (isset($api_response["data"]) && !empty($api_response["data"])) {

                echo "\nPost ID #" . $api_response["data"]["id"] . " was successfully created.\n";

            } else {

                // Access token probably isn't set.

                // string(67) "{"errors":[{"message":"An access token is required.","code":6000}]}"

                // OR

                // Post fields probably malformed.

            }

        } else {

            echo "User ID isn't set.";

        }

    }

    /* Create a post for a user under a publication. */
    public function createUserPostForPublication($publication_id = null, $post = [])
    {
        //https://github.com/Medium/medium-api-docs#creating-a-post-under-a-publication

        if (isset($publication_id)) {

            $url = "https://api.medium.com/$this->version/publications/$publication_id/posts";

            $this->request->post($url, $post);

            $api_response = json_decode($this->request->response, true);

            if (isset($api_response["data"]) && !empty($api_response["data"])) {

                echo "\nPost ID #" . $api_response["data"]["id"] . " was successfully created for Publication: $publication_id.\n";

            } else {

                // Access token probably isn't set.

                // string(67) "{"errors":[{"message":"An access token is required.","code":6000}]}"

                // OR

                // Post fields probably malformed.

            }

        } else {

            echo "Publication ID must be set.";

        }

    }

    /* ********************************************************* */
    /* Functions related to creating a post. */
    /* ********************************************************* */

    /* Upload an image */

    public function uploadImage($image = null, $filename = null, $type=null)
    {
        // https://github.com/Medium/medium-api-docs#uploading-an-image

        // https://stackoverflow.com/questions/38922677/using-curl-and-php-script-to-upload-an-image-and-text

        // Image types 

        /*
        image/jpeg
        image/png
        image/gif
        image/tiff
        */

        if(isset($image) && isset($filename) && isset($type)){

            // ToDo: Make it work. 
            
            $chars = md5(rand());

            $boundary = "FormBoundary$chars";
            
            $this->request->headers["Content-Type"] = "Content-Type: multipart/form-data; boundary=$boundary";

            /* 
                --FormBoundaryXYZ
                Content-Disposition: form-data; name="image"; filename="filename.png"
                Content-Type: image/png

                IMAGE_DATA
                --FormBoundaryXYZ--
            */

            $this->request->headers["Content-Disposition"] = `--$boundary\r\n`;
            $this->request->headers["Content-Disposition"] .=  `Content-Disposition: form-data; name="image"; filename="$filename"\r\n`;
            $this->request->headers["Content-Disposition"] .= `Content-Type: $type\r\n`; 
            $this->request->headers["Content-Disposition"] .=  `$image\r\n`;
            $this->request->headers["Content-Disposition"] .= `--$boundary--`;

            $url = "https://api.medium.com/$this->version/images";

            $this->request->post($url);

            $api_response = json_decode($this->request->response, true);

            if (isset($api_response["data"]) && !empty($api_response["data"])) {

                $image_url =  $api_response['url'];

                echo "\n Image successfuly uploaded. Url: $image_url";

            } else {

                // Access token probably isn't set.

                // string(67) "{"errors":[{"message":"An access token is required.","code":6000}]}"

                // OR

                // Post fields probably malformed.

            }

        }

    }

    /* ********************************************************* */
    /* Medium Official API functions */
    /* ********************************************************* */

}
