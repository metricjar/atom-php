<?php
/**
 */

namespace IronSourceAtom;


class Atom {
    private $authKey;
    private $url;

    public function __construct($authKey="", $url="http://track.atom-data.io/") {

        if($authKey == null) {
            throw new \InvalidArgumentException('Param $auth must not be null!');
        }

        $this->authKey = $authKey;
        $this->url = $url;

    }

    public function putEvent($stream, $data) {

        $contentArray = array(
            'table' => $stream,
            'data' => $data,
            'auth' => $this->makeAuth($data)
        );

        $this->post(json_encode($contentArray), $this->url);
    }

    public function putEvents($stream, $data) {

        $contentArray = array(
            'table' => $stream,
            'data' => $data,
            'bulk' => true,
            'auth' => $this->makeAuth($data)

        );
        $bulkUrl = $this->url.'bulk';
        $this->post(json_encode($contentArray), $bulkUrl);
    }

    private function post($content, $url) {

        $headers =  'Content-Type: application/json,
                    x-ironsource-atom-sdk-type: atom-php,
                    x-ironsource-atom-sdk-version: 1.0.0';

        $options = array(
            'http' => array(
                'header' => $headers,
                'method' => 'POST',
                'content' => $content
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */
        }

        var_dump($result);

    }
    
    private function makeAuth($data) {
        
        return hash_hmac('sha256', $data, $this ->authKey);
    }

}