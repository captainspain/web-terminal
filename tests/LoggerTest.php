<?php

namespace CaptainSpain\WebTerminal\Tests;

use CaptainSpain\WebTerminal\Logger;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    protected function setUp()
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Logger::__construct
     */
    public function testConstructorWithNotReadableFilePath()
    {
        $config = ['foo' => 'bar'];

        $file = $this->createFile($config, 0222);

        $logger = new Logger($file->url());

        $this->assertAttributeSame([], 'data', $logger);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Logger::__construct
     * @covers \CaptainSpain\WebTerminal\Logger::loadData
     */
    public function testConstructor()
    {
        $config = ['foo' => 'bar'];

        $file = $this->createFile($config);

        $logger = new Logger($file->url());

        $this->assertAttributeSame($file->url(), 'filePath', $logger);
        $this->assertAttributeSame($config, 'data', $logger);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Logger::__construct
     * @covers \CaptainSpain\WebTerminal\Logger::loadData
     * @covers \CaptainSpain\WebTerminal\Logger::getData
     */
    public function testGetData()
    {
        $config = ['foo' => 'bar'];

        $file = $this->createFile($config);

        $logger = new Logger($file->url());
        $this->assertSame($config, $logger->getData());
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Logger::__construct
     * @covers \CaptainSpain\WebTerminal\Logger::loadData
     * @covers \CaptainSpain\WebTerminal\Logger::getData
     * @covers \CaptainSpain\WebTerminal\Logger::setData
     */
    public function testSetData()
    {
        $config = ['foo' => 'bar'];

        $file = $this->createFile($config);

        $logger = new Logger($file->url());
        $this->assertSame($config, $logger->getData());

        $config = ['bar' => 'foo'];
        $logger->setData($config);
        $this->assertSame($config, $logger->getData());
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Logger::__construct
     * @covers \CaptainSpain\WebTerminal\Logger::loadData
     * @covers \CaptainSpain\WebTerminal\Logger::setData
     * @covers \CaptainSpain\WebTerminal\Logger::save
     */
    public function testSave()
    {
        $file = $this->createFile(new \stdClass());
        $this->assertSame(json_encode([], JSON_FORCE_OBJECT), $file->getContent());

        $logger = new Logger($file->url());
        $logger->setData(['bar' => 'foo']);
        $logger->save();

        $this->assertSame(json_encode(['bar' => 'foo'], JSON_FORCE_OBJECT), $file->getContent());
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Logger::__construct
     * @covers \CaptainSpain\WebTerminal\Logger::loadData
     * @covers \CaptainSpain\WebTerminal\Logger::getLast
     */
    public function testGetLast()
    {
        $config = [
            ['foo' => 'bar'],
            ['foo' => 'baz'],
        ];

        $file = $this->createFile($config);
        $logger = new Logger($file->url());

        $this->assertSame('bar', $logger->getLast('foo'));
        $this->assertSame('', $logger->getLast('bar', ''));
    }

    /**
     * @param array|object $config
     * @param int|null $permissions
     * @return vfsStreamFile
     */
    private function createFile($config, ?int $permissions = null): vfsStreamFile
    {
        return vfsStream::newFile('config', $permissions)
            ->withContent(json_encode($config))
            ->at($this->root);
    }
}