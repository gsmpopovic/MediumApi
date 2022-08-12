<?php

namespace gsmpopovic\MediumApi;

// https://rapidapi.com/nishujain199719-vgIfuFHZxVZ/api/medium2/

use gsmpopovic\MediumApi\Request;

class MediumApi
{


    public $rapid_api_medium_api_key = null;

    public $rapid_api_medium_api_host = null;

    public $user_name = null;

    public $user_id = null;

    public $associated_articles = [];

    public $associated_articles_ids = [];

    public $associated_articles_infos = [];

    public $associated_articles_contents = [];

    public $associated_articles_markdowns = [];

    public $request = null; 

    public function __construct()
    {

        $this->rapid_api_medium_api_key = getenv("RAPID_API_MEDIUM_API_KEY");
        $this->rapid_api_medium_api_host = getenv("RAPID_API_MEDIUM_API_HOST");
        $this->user_name = getenv("MEDIUM_USER_NAME");

        $this->request = new Request();
        $this->setRapidApiHeaders();

    }


    /* ********************************************************* */
    /* Medium Rapid API functions */
    /* ********************************************************* */

        /* Set the HTTP headers that authorize whether we can access the 3rd party API on Rapid API. */
        public function setRapidApiHeaders(){

            $this->request->headers["X-RapidAPI-Host"] = "X-RapidAPI-Host: " . $this->rapid_api_medium_api_host;
            $this->request->headers["X-RapidAPI-Key"] = "X-RapidAPI-Key: " . $this->rapid_api_medium_api_key;
        
        }
        /* Erase the HTTP headers that authorize whether we can access the 3rd party API on Rapid API. */
        public function unsetRapidApiHeaders(){

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
                    "content" => $content
                ];

            }

        }

        /* ********************************************************* */
        /* Functions related to etrieving Articles */
        /* ********************************************************* */

    /* ********************************************************* */
    /* Medium Rapid API functions */
    /* ********************************************************* */
    
    /* DUmp user's articles for debugging purposes. */
    public function showUserArticles()
    {
        var_dump($this->associated_articles);
    }

}
