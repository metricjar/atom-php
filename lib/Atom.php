<?php
/**
 */

namespace IronSourceAtom;


/**
 * Class Atom low level API class, supports putEvent() and putEvents();
 * @package IronSourceAtom
 */
class Atom
{
    private $authKey;
    private $url;

    /**
     * Atom constructor.
     * @param string $authKey
     * @param string $url
     */
    public function __construct($authKey = "", $url = "http://track.atom-data.io/")
    {
        $this->authKey = $authKey;
        $this->url = $url;

    }

    /**
     * Writes a single data event into ironSource.atom delivery stream.
     * To write multiple data records into a delivery stream, use putEvents().
     * @param string $stream  the name of Atom stream to send data
     * @param string $data  data in JSON format to be send
     * @return string response from server
     */
    public function putEvent($stream, $data)
    {

        if (empty($stream)) {
            throw new \InvalidArgumentException('Param $strem must not neither null nor empty string!');
        }

        if ($data == null) {
            throw new \InvalidArgumentException('Param $data must not be null!');
        }
    // @codeCoverageIgnoreStart
        $contentArray = array(
            'table' => $stream,
            'data'  => $data,
            'auth'  => $this->makeAuth($data)
        );

        return $this->post(json_encode($contentArray), $this->url);
    }
    // @codeCoverageIgnoreEnd

    /**
     * Writes a multiple data events into ironSource.atom delivery stream.
     * To write  single data event into a delivery stream, use putEvent().
     * @param string $stream  the name of Atom stream to send data
     * @param string $data  data in JSON format to be send. Must be a valid JSON of array
     * @return string response from server
     */
    public function putEvents($stream, $data)
    {

        if (empty($stream)) {
            throw new \InvalidArgumentException('Param $strem must not neither null nor empty string!');
        }

        if (!is_array(json_decode($data))) {
            throw new \InvalidArgumentException('Param $data must not be valid JSON of array!');
        }
    // @codeCoverageIgnoreStart
        $contentArray = array(
            'table' => $stream,
            'data'  => $data,
            'bulk'  => true,
            'auth'  => $this->makeAuth($data)

        );
        $bulkUrl = $this->url . 'bulk';
        return $this->post(json_encode($contentArray), $bulkUrl);
    }

    /**
     * @param string $authKey
     */
    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    // @codeCoverageIgnoreEnd

    /**
     * @codeCoverageIgnore
     */
    private function post($content, $url)
    {

        $headers = 'Content-Type: application/json,
                    x-ironsource-atom-sdk-type: atom-php,
                    x-ironsource-atom-sdk-version: 1.0.0';

        $options = array(
            'http' => array(
                'header'  => $headers,
                'method'  => 'POST',
                'content' => $content
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { /* Handle error */
        }

        return $result;

    }
    /**
     * @codeCoverageIgnore
     */
    private function makeAuth($data)
    {

        return hash_hmac('sha256', $data, $this->authKey);
    }

}