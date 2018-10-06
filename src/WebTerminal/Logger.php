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
            $this->file = new \SplFileObject($filePath, 'c+');
        } catch (\Exception $e) {
            throw new \RuntimeException('File is not readable');
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

        if ($file->ftell() !== 0) {
            $file->rewind();
        }
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
