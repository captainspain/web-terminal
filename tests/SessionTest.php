<?php

namespace CaptainSpain\WebTerminal\Tests;

use CaptainSpain\WebTerminal\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     */
    public function testConstructor()
    {
        $session = new Session(['foo' => 'bar']);

        $this->assertAttributeSame(['foo' => 'bar'], 'data', $session);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::isEmpty
     * @covers \CaptainSpain\WebTerminal\Session::set
     */
    public function testIsEmpty()
    {
        $session = new Session([]);
        $this->assertTrue($session->isEmpty());

        $session->set('foo', true);
        $this->assertFalse($session->isEmpty());
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::get
     */
    public function testGet()
    {
        $session = new Session(['foo' => 'bar']);

        $this->assertSame('bar', $session->get('foo'));
        $this->assertSame(null, $session->get('bar'));
        $this->assertSame([], $session->get('bar', []));
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::set
     */
    public function testSet()
    {
        $session = new Session([]);
        $this->assertAttributeSame([], 'data', $session);

        $res = $session->set('foo', true);
        $this->assertAttributeSame(['foo' => true], 'data', $session);
        $this->assertInstanceOf(Session::class, $res);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::pull
     */
    public function testPull()
    {
        $session = new Session(['foo' => 'bar']);

        $value = $session->pull('foo');
        $this->assertSame('bar', $value);
        $this->assertAttributeSame([], 'data', $session);

        $value = $session->pull('baz', false);
        $this->assertSame(false, $value);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::save
     */
    public function testSave()
    {
        $session = new Session(['foo' => 'bar']);
        $session->save();

        $this->assertSame(['foo' => 'bar'], $_SESSION);
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::all
     */
    public function testAll()
    {
        $session = new Session(['foo' => 'bar']);

        $this->assertSame(['foo' => 'bar'], $session->all());
    }

    /**
     * @covers \CaptainSpain\WebTerminal\Session::__construct
     * @covers \CaptainSpain\WebTerminal\Session::flush
     */
    public function testFlush()
    {
        $session = new Session(['foo' => 'bar']);
        $this->assertAttributeSame(['foo' => 'bar'], 'data', $session);

        $session->flush();
        $this->assertAttributeSame([], 'data', $session);
    }
}