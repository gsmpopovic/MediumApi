<?php

namespace gsmpopovic\MediumApi;

class MediumApi
{

    public $official_medium_api_access_token = null;

    public $version = "v1";

    public $user = [];

    public $request = null;

    public function __construct($request)
    {

        $this->request = $request;

        $this->setup();

    }

    /* Get environment variables and set request headers. */
    public function setup()
    {

        $this->setEnvVariables();

        $this->setMediumApiHeaders();

    }

    public function setEnvVariables()
    {

        $this->official_medium_api_access_token = getenv("OFFICIAL_MEDIUM_API_ACCESS_TOKEN");

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
    public function createUserPostForPublication($publication_id, $post = [])
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

    public function uploadImage()
    {
        // https://github.com/Medium/medium-api-docs#uploading-an-image
    }

    /* ********************************************************* */
    /* Medium Official API functions */
    /* ********************************************************* */

}
