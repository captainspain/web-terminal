<?php

namespace CaptainSpain\WebTerminal;

class Session
{
    /** @var array */
    private $data = [];

    /**
     * Session constructor.
     * @param $sessions
     */
    public function __construct($sessions)
    {
        $this->data = $sessions;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($data);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        $session = $this->data;

        return array_key_exists($key, $session)
            ? $session[$key]
            : $default;
    }

    /**
     * @param string $key
     * @param $value
     * @return Session
     */
    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function pull(string $key, $default = null)
    {
        $value = $default;
        $session = $this->data;
        if (array_key_exists($key, $session)) {
            $value = $session[$key];
            unset($this->data[$key]);
        }

        return $value;
    }

    /**
     * Saves current data to $_SESSION
     */
    public function save()
    {
        $_SESSION = $this->data;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Flushes (removes) all data.
     */
    public function flush()
    {
        $this->data = [];
    }
}
