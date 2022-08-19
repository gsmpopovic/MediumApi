<?php

namespace gsmpopovic\MediumApi;

class Request
{

    /* A cURL wrapper. */

    public $url = null;

    public $response = null;

    public $exception = null; 

    public $err = null;

    public $options = [

        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => null,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

    ];

    public $curl = null;

    public function __construct()
    {

        $this->init();

    }

    public function init()
    {

        $curl = curl_init();

        $this->curl = $curl;

    }

    public function setOptions($options = [])
    {

        if (isset($options) && !empty($options)) {

            $this->options = $options;

        }

    }

    public function setHeaders($headers = [])
    {

        if (isset($headers) && !empty($headers)) {

            $this->headers = $headers;

        }

        $this->options[CURLOPT_HTTPHEADER] = $this->headers;

    }

    public function setURL($url = null)
    {

        if (isset($url)) {

            $this->url = $url;

        } else {

            $this->url = null;

        }

        $this->options[CURLOPT_URL] = $this->url;

    }

    public function setHttpVerb($verb = null)
    {

        if (isset($verb)) {

            $this->verb = $verb;

        } else {

            $this->verb = "GET";

        }

        $this->options[CURLOPT_CUSTOMREQUEST] = $this->verb;

    }

    public function delete($url = null, $options = [], $headers = [])
    {

        try {

            $this->build($url, $options, $headers, strtoupper(__FUNCTION__));

            $this->exec();

        } catch (\Exception $e) {

            $this->exception = $e;

            echo "Error when building and sending request: " . $e->getMessage();

        }

    }

    public function get($url = null, $options = [], $headers = [])
    {

        try {

            $this->build($url, $options, $headers, strtoupper(__FUNCTION__));

            $this->exec();

        } catch (\Exception $e) {

            $this->exception = $e;

            echo "Error when building and sending request: " . $e->getMessage();

        }

    }

    public function post($url = null, $post = [], $options = [], $headers = [])
    {


        try {

            $this->build($url, $options, $headers, strtoupper(__FUNCTION__));

            $this->setPostFields($post);

            $this->exec();

        } catch (\Exception $e) {

            $this->exception = $e;

            echo "Error when building and sending request: " . $e->getMessage();

        }

    }

    public function setPostFields($post = []){

        $post_fields = json_encode($post);

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_fields);

    }


    public function build($url = null, $options = [], $headers = [], $verb = null)
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
