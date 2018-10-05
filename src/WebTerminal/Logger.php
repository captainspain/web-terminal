<?php

namespace CaptainSpain\WebTerminal;

class Logger
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var \SplFileObject
     */
    private $file;

    /**
     * Logger constructor.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        try {
            $this->file = new \SplFileObject($filePath, 'r+');
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid file path provided');
        }
        $this->loadData();
    }

    /**
     * @return void
     */
    private function loadData(): void
    {
        $file = $this->file;

        $content = $file->fread($file->getSize());

        $this->data = json_decode($content, true) ?? [];
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
        $file = $this->file;
        $data = $this->data;

        $file->rewind();
        $file->fwrite(json_encode($data, JSON_FORCE_OBJECT));
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
