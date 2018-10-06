<?php

namespace CaptainSpain\WebTerminal\Tests;

use CaptainSpain\WebTerminal\Request;
use CaptainSpain\WebTerminal\Session;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Request::__construct
     */
    public function testConstructor()
    {
        $session = new Session([]);
        $request = new Request($_POST, $_GET, [], $session);

        $this->assertInstanceOf(Session::class, $request->session);
        $this->assertAttributeSame(['post' => [], 'get' => [], 'server' => []], 'data', $request);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Request::__construct
     * @covers \CaptainSpain\WebTerminal\Request::get
     */
    public function testGet()
    {
        $session = new Session([]);
        $request = new Request($_POST, $_GET, $_SERVER, $session);

        $this->assertSame($_SERVER, $request->get('server'));
        $this->assertSame(null, $request->get('foo'));
        $this->assertSame([], $request->get('foo', []));
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Request::__construct
     * @covers \CaptainSpain\WebTerminal\Request::getPostData
     */
    public function testGetPostData()
    {
        $session = new Session([]);
        $request = new Request(['command' => 'bar'], $_GET, $_SERVER, $session);

        $this->assertSame(['command' => 'bar'], $request->getPostData());
        $this->assertSame('bar', $request->getPostData('command'));
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Request::__construct
     * @covers \CaptainSpain\WebTerminal\Request::isPost
     */
    public function testIsPost()
    {
        $session = new Session([]);
        $request = new Request($_POST, $_GET, $_SERVER, $session);

        $this->assertFalse($request->isPost());
    }
}