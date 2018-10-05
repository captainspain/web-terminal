<?php

namespace CaptainSpain\WebTerminal;

class Request
{
    /** @var array */
    private $data = [
        'post' => [],
        'get' => [],
        'server' => [],
    ];
    /** @var Session */
    public $session;

    /**
     * Request constructor.
     * @param array $post
     * @param array $get
     * @param array $server
     * @param Session $session
     * @internal param array $request
     */
    public function __construct(array $post, array $get, array $server, Session $session)
    {
        $this->data['post'] = $post;
        $this->data['get'] = $get;
        $this->data['server'] = $server;
        $this->session = $session;

        if ($this->isFetchRequest()) {
            $this->data['post'] = json_decode(file_get_contents('php://input'), true);
        }
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $data = $this->data;

        return array_key_exists($key, $data)
            ? $data[$key]
            : $default;
    }

    public function isFetchRequest()
    {
        return (
            isset($this->data['server']['HTTP_REQUEST_BY'])
            && $this->data['server']['HTTP_REQUEST_BY'] === 'web-terminal'
        );
    }

    /**
     * @param null|string $key
     * @param mixed $default
     * @return mixed
     */
    public function getPostData($key = null, $default = null)
    {
        $post = $this->data['post'];

        return ($key !== null && array_key_exists($key, $post))
            ? $post[$key]
            : $post;
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->data['server']['REQUEST_METHOD'] === 'POST';
    }
}
