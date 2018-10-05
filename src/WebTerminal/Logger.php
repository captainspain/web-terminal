<?php

namespace CaptainSpain\WebTerminal;

class Logger
{
    /** @var array */
    private $data;
    /** @var string */
    private $filePath;

    /**
     * Logger constructor.
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadData();
    }

    /**
     * @return void
     */
    private function loadData(): void
    {
        if (($json = @file_get_contents($this->filePath)) === false) {
            $this->data = [];

            return;
        }

        $this->data = json_decode($json, true) ?? [];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Logger
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $data = $this->data;
        file_put_contents($this->filePath, json_encode($data, JSON_FORCE_OBJECT));
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function getLast($key, $default = null)
    {
        $data = reset($this->data);

        return array_key_exists($key, $data)
            ? $data[$key]
            : $default;
    }
}
