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
        try {
            $file = new \SplFileObject($this->filePath);
            $content = $file->fread($file->getSize());

            $this->data = json_decode($content, true) ?? [];
        } catch (\Exception $e) {
            $this->data = [];
        }
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

        try {
            $file = new \SplFileObject($this->filePath);
            $file->fwrite(json_encode($data, JSON_FORCE_OBJECT));
        } catch (\Exception $e) {

        }
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
