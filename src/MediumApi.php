<?php

namespace gsmpopovic\MediumApi;

// https://rapidapi.com/nishujain199719-vgIfuFHZxVZ/api/medium2/

use gsmpopovic\MediumApi\Request;

class MediumApi
{

    /* Official API properties */

    public $official_medium_api_access_token = null;

    public $version = "v1";

    public $user = [];

    /* Official API properties */

    /* ********************************************************* */

    /* 3rd-party API (Rapid API) properties */

    public $rapid_api_medium_api_key = null;

    public $rapid_api_medium_api_host = null;

    public $user_name = null;

    public $user_id = null;

    public $associated_articles = [];

    public $associated_articles_ids = [];

    public $associated_articles_infos = [];

    public $associated_articles_contents = [];

    public $associated_articles_markdowns = [];

    /* 3rd-party API properties */

    public $request = null;

    public function __construct($using_medium_rapid_api = false, $using_official_medium_api = true)
    {

        $this->using_medium_rapid_api = $using_medium_rapid_api;
        $this->using_official_medium_api = $using_official_medium_api;

        $this->request = new Request();

        $this->setup();

    }

    public function setup()
    {

        if ($this->using_medium_rapid_api === true) {

            $this->rapid_api_medium_api_key = getenv("RAPID_API_MEDIUM_API_KEY");
            $this->rapid_api_medium_api_host = getenv("RAPID_API_MEDIUM_API_HOST");
            $this->user_name = getenv("MEDIUM_USER_NAME");

            $this->setRapidApiHeaders();

        }

        if ($this->using_official_medium_api === true) {

            $this->official_medium_api_access_token = getenv("OFFICIAL_MEDIUM_API_ACCESS_TOKEN");
            $this->setMediumApiHeaders();

        }

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

    public function uploadImage(){
        // https://github.com/Medium/medium-api-docs#uploading-an-image
    }

    /* ********************************************************* */
    /* Medium Official API functions */
    /* ********************************************************* */

    /* ********************************************************* */

    /* ********************************************************* */
    /* Medium Rapid API functions */
    /* ********************************************************* */

    /* Set the HTTP headers that authorize whether we can access the 3rd party API on Rapid API. */
    public function setRapidApiHeaders()
    {

        $this->request->headers["X-RapidAPI-Host"] = "X-RapidAPI-Host: " . $this->rapid_api_medium_api_host;
        $this->request->headers["X-RapidAPI-Key"] = "X-RapidAPI-Key: " . $this->rapid_api_medium_api_key;

    }

    /* Erase the HTTP headers that authorize whether we can access the 3rd party API on Rapid API. */
    public function unsetRapidApiHeaders()
    {

        unset($this->request->headers["X-RapidAPI-Host"]);
        unset($this->request->headers["X-RapidAPI-Key"]);

    }

    /* Get the user's ID associated with their username. */
    public function getUserId()
    {

        $url = "https://medium2.p.rapidapi.com/user/id_for/" . $this->user_name;

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["id"])) {

            $this->user_id = $api_response["id"];

        }

    }

    /* ********************************************************* */
    /* Functions related to etrieving Articles */
    /* ********************************************************* */

    /* Getting a user's articles, their info, and their contents */
    public function getUserArticlesIds()
    {
        $url = "https://medium2.p.rapidapi.com/user/" . $this->user_id . "/articles";

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["associated_articles"])) {

            $this->associated_articles_ids = $api_response["associated_articles"];

        }

    }

    /* Get the content (non-html/non-markdown) for a particular article. */
    public function getUserArticleContent($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id . "/content";

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["content"])) {

            return $api_response["content"];

        }

    }

    /* Get the content (non-html/non-markdown) for all user's articles associated with our object. */
    public function getUserArticlesContents()
    {

        foreach ($this->associated_articles_ids as $associated_article_id) {

            $content = $this->getUserArticleContent($associated_article_id);

            $this->associated_articles_contents[] = [
                "article_id" => $associated_article_id,
                "content" => $content,
            ];

        }

    }

    /* Get the content (markdown) for a particular article. */
    public function getUserArticleMarkdown($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id . "/markdown";

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["markdown"])) {

            return $api_response["markdown"];

        }

    }

    /* Get the content (markdown) for all user's articles associated with our object. */
    public function getUserArticlesMarkdowns()
    {

        foreach ($this->associated_articles_ids as $associated_article_id) {

            $markdown = $this->getUserArticleMarkdown($associated_article_id);

            $this->associated_articles_markdowns[] = [
                "article_id" => $associated_article_id,
                "markdown" => $markdown,
            ];

        }

    }

    /* Get the info/metadata for a particular article. */
    public function getUserArticleInfo($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id;

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        return $api_response;

    }

    /* Get the info/metadata for all user's articles associated with our object. */
    public function getUserArticlesInfos()
    {

        foreach ($this->associated_articles_ids as $associated_article_id) {

            $info = $this->getUserArticleInfo($associated_article_id);

            $this->associated_articles_infos[] = [
                "article_id" => $associated_article_id,
                "info" => $info,
            ];

        }

    }

    /* Get the content, markdown, and metadata for all of our user's article associated with our object,
    and file it away nicely, in memory.
     */
    public function getUserArticles()
    {

        $this->getUserId();

        $this->getUserArticlesIds();

        foreach ($this->associated_articles_ids as $associated_article_id) {

            $info = $this->getUserArticleInfo($associated_article_id);
            $markdown = $this->getUserArticleMarkdown($associated_article_id);
            $content = $this->getUserArticleContent($associated_article_id);

            $this->associated_articles[] = [
                "article_id" => $associated_article_id,
                "info" => $info,
                "markdown" => $markdown,
                "content" => $content,
            ];

        }

    }

    /* ********************************************************* */
    /* Functions related to etrieving Articles */
    /* ********************************************************* */

    /* ********************************************************* */
    /* Medium Rapid API functions */
    /* ********************************************************* */

}
