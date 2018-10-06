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
     *
     * @param array $post
     * @param array $get
     * @param array $server
     * @param Session $session
     */
    public function __construct(array $post, array $get, array $server, Session $session)
    {
        $this->data['post'] = $post;
        $this->data['get'] = $get;
        $this->data['server'] = $server;
        $this->session = $session;
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

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getPostData(?string $key = null)
    {
        $post = $this->data['post'];

        return ($key !== null && array_key_exists($key, $post))
            ? $post[$key]
            : $post;
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return isset($this->data['server']['REQUEST_METHOD']) && $this->data['server']['REQUEST_METHOD'] === 'POST';
    }
}
