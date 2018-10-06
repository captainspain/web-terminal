<?php

namespace CaptainSpain\WebTerminal;

class Logger
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $filePath;

    /**
     * Logger constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
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
    public function getData(): array
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
        file_put_contents($this->filePath, json_encode($data, \JSON_FORCE_OBJECT));
    }

    /**
     * @param string $key
     * @param mixed $default
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
