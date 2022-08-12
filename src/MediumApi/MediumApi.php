<?php

namespace src\MediumApi;

// https://rapidapi.com/nishujain199719-vgIfuFHZxVZ/api/medium2/

use src\MediumApi\Request;

class MediumApi
{


    public $api_key = null;

    public $api_host = null;

    public $user_name = null;

    public $user_id = null;

    public $associated_articles = [];

    public $associated_articles_ids = [];

    public $associated_articles_infos = [];

    public $associated_articles_contents = [];

    public $associated_articles_markdowns = [];

    public $request = null; 

    public $response = null;

    public $err = null;

    public function __construct()
    {

        $api_key = getenv("MEDIUM_API_KEY");
        $api_host = getenv("MEDIUM_API_HOST");

        $this->request = new Request();

        $this->request->headers = [
            "X-RapidAPI-Host: " . $api_host,
            "X-RapidAPI-Key: " . $api_key,
        ];
        
        $this->user_name = getenv("MEDIUM_USER_NAME");

    }

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
    /* Articles */
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

    public function getUserArticleContent($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id . "/content";

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["content"])) {

            return $api_response["content"];

        }

    }

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

    public function getUserArticleMarkdown($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id . "/markdown";

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        if (isset($api_response["markdown"])) {

            return $api_response["markdown"];

        }

    }

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

    public function getUserArticleInfo($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id;

        $this->request->get($url);

        $api_response = json_decode($this->request->response, true);

        return $api_response;

    }

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
    /* Articles */
    /* ********************************************************* */

    public function showUserArticles()
    {
        var_dump($this->associated_articles);
    }

}
