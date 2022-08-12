<?php

namespace src\MediumApi;

class Request
{

    public $url = "";

    public $api_key = "";

    public $api_host = "";

    public $user_name = "";

    public $response = null;

    public $err = null;

    public $options = [

        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

    ];

    public $curl = null;

    public function __construct()
    {

        $this->init();

        $this->api_key = getenv("MEDIUM_API_KEY");
        $this->api_host = getenv("MEDIUM_API_HOST");
        $this->user_name = getenv("MEDIUM_USER_NAME");

    }

    public function setOptions($options = [])
    {

        if (isset($options)) {

            $this->options = $options;

        }

    }

    public function setHeaders($headers = [])
    {

        if (isset($headers)) {

            $this->headers = $headers;

        } else {

            $this->headers = [
                "X-RapidAPI-Host: " . $this->api_host,
                "X-RapidAPI-Key: " . $this->api_key,
            ];

        }

        $this->options[CURLOPT_HTTPHEADER] = $this->headers;

    }

    public function setURL($url = "")
    {

        if (isset($url)) {

            $this->url = $url;

        } else {

            $this->url = "";

        }

        $this->options[CURLOPT_URL] = $this->url;

    }

    public function setHttpVerb($verb = "")
    {

        if (isset($verb)) {

            $this->verb = $verb;

        } else {

            $this->verb = "GET";

        }

        $this->options[CURLOPT_CUSTOMREQUEST] = $this->verb;

    }

    public function init()
    {

        $curl = curl_init();

        $this->curl = $curl;

    }

    public function get($url = "", $options = [], $headers = [])
    {

        try {

            $this->build($url, $options, $headers, strtoupper(__FUNCTION__));

            $this->exec();

        } catch (\Exception $e) {

            echo "Error when building and sending request: " . $e->getMessage();

        }

    }

    public function post($url = "", $options = [], $headers = [])
    {


        try {

            $this->build($url, $options, $headers, strtoupper(__FUNCTION__));

            $this->exec();

        } catch (\Exception $e) {

            echo "Error when building and sending request: " . $e->getMessage();

        }

    }

    public function build($url = "", $options = [], $headers = [], $verb = "")
    {

        $this->setOptions($options);

        $this->setHeaders($headers);

        $this->setUrl($url);

        $this->setHttpVerb($verb);

        curl_setopt_array($this->curl, $this->options);

    }

    public function exec()
    {

        try {

            $this->response = curl_exec($this->curl);

            $this->err = curl_error($this->curl);

        } catch (\Exception $e) {

            echo "Error when executing request: " . $e->getMessage();

        } finally {

            $this->close();
        }

    }

    public function close()
    {

        curl_close($this->curl);

    }

}
