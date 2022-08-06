<?php

namespace src\MediumApi;

// https://rapidapi.com/nishujain199719-vgIfuFHZxVZ/api/medium2/

class MediumApi
{

    public $api_key = "";

    public $api_host = "";

    public $user_name = "";

    public $user_id = null;

    public $associated_articles = [];

    public $associated_articles_ids = [];

    public $associated_articles_infos = [];

    public $associated_articles_contents = [];

    public $associated_articles_markdowns = [];

    public $response = null;

    public $err = null;

    public function __construct()
    {

        $this->api_key = getenv("MEDIUM_API_KEY");
        $this->api_host = getenv("MEDIUM_API_HOST");
        $this->user_name = getenv("MEDIUM_USER_NAME");

    }

    public function request($args)
    {

        try {

            $url = $args["url"];

            $return_transfer = isset($args["return_transfer"]) ? $args["return_transfer"] : true;

            $follow_location = isset($args["follow_location"]) ? $args["follow_location"] : true;

            $encoding = isset($args["encoding"]) ? $args["encoding"] : "";

            $http_verb = isset($args["http_verb"]) ? $args["http_verb"] : "GET";

            $http_version = isset($args["http_version"]) ? $args["http_version"] : CURL_HTTP_VERSION_1_1;

            $max_redirects = isset($args["max_redirects"]) ? $args["max_redirects"] : 10;

            $timeout = isset($args["timeout"]) ? $args["timeout"] : 30;

            $http_version = isset($args["http_version"]) ? $args["http_version"] : CURL_HTTP_VERSION_1_1;

            $headers = isset($args["headers"]) ? $args["headers"] :
            [
                "X-RapidAPI-Host: " . $this->api_host,
                "X-RapidAPI-Key: " . $this->api_key,
            ];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "$url",
                CURLOPT_RETURNTRANSFER => $return_transfer,
                CURLOPT_FOLLOWLOCATION => $follow_location,
                CURLOPT_ENCODING => "$encoding",
                CURLOPT_MAXREDIRS => $max_redirects,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_HTTP_VERSION => $http_version,
                CURLOPT_CUSTOMREQUEST => "$http_verb",
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            $this->response = $response;

            $this->err = $err;

        } catch (\Exception$e) {

            echo "Error: " . $e->getMessage();

        }

    }

    public function getUserId()
    {

        $url = "https://medium2.p.rapidapi.com/user/id_for/" . $this->user_name;

        $params["url"] = $url;

        $this->request($params);

        $api_response = json_decode($this->response, true);

        if (isset($api_response["id"])) {

            $this->user_id = $api_response["id"];

        }

    }

    public function getUserArticlesIds()
    {
        $url = "https://medium2.p.rapidapi.com/user/" . $this->user_id . "/articles";

        $params["url"] = $url;

        $this->request($params);

        $api_response = json_decode($this->response, true);

        if (isset($api_response["associated_articles"])) {

            $this->associated_articles_ids = $api_response["associated_articles"];

        }

    }

    public function getUserArticleContent($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id . "/content";

        $params["url"] = $url;

        $this->request($params);

        $api_response = json_decode($this->response, true);

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

            break;
        }

    }

    public function getUserArticleMarkdown($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id . "/markdown";

        $params["url"] = $url;

        $this->request($params);

        $api_response = json_decode($this->response, true);

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

            break;
        }

    }

    public function getUserArticleInfo($associated_article_id)
    {

        $url = "https://medium2.p.rapidapi.com/article/" . $associated_article_id;

        $params["url"] = $url;

        $this->request($params);

        $api_response = json_decode($this->response, true);

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

            break;
        }

    }

    public function showUserArticles()
    {
        var_dump($this->associated_articles);
    }

}
