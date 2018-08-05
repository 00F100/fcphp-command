<?php

use FcPhp\Command\Command;
use PHPUnit\Framework\TestCase;
use FcPhp\Command\Interfaces\ICEntity;
use FcPhp\Command\Interfaces\ICommand;

class CommandUnitTest extends TestCase
{
    public function setUp()
    {
        $this->di = $this->createMock('FcPhp\Di\Interfaces\IDi');
        $this->entity = $this->createMock('FcPhp\SConsole\Interfaces\ISCEntity');
        $this->autoload = $this->createMock('FcPhp\Autoload\Interfaces\IAutoload');
        $this->cache = $this->createMock('FcPhp\Cache\Interfaces\ICache');
        $this->vendorPath = 'tests/var/unit/config';

        $centity = $this->createMock('FcPhp\Command\Interfaces\ICEntity');
        $centity
            ->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('ControllerDatasource@method'));
        $centity
            ->expects($this->any())
            ->method('getCommand')
            ->will($this->returnValue('connect'));
        $centity
            ->expects($this->any())
            ->method('getRule')
            ->will($this->returnValue('connect-rule'));
        $centity
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(200));

        $this->factory = $this->createMock('FcPhp\Command\Interfaces\ICommandFactory');
        $this->factory
            ->expects($this->any())
            ->method('getEntity')
            ->will($this->returnValue($centity));

        $this->commands = require 'tests/var/unit/config/commands.php';

        $this->instance = new Command($this->entity, $this->autoload, $this->cache, $this->vendorPath, $this->factory, $this->commands);
    }

    public function testInstance()
    {
        $this->assertTrue($this->instance instanceof ICommand);
    }

    public function testMatchCommand()
    {
        $args = [
            'package',
            'datasource',
            'connect',
            '-h',
            'localhost',
            '-u',
            'user',
            '-p',
            'password'
        ];
        $match = $this->instance->match($args);
        $this->assertTrue($match instanceof ICEntity);
        $this->assertTrue(is_array($match->getParams()));
        $this->assertEquals($match->getAction(), 'ControllerDatasource@method');
        $this->assertEquals($match->getCommand(), 'connect');
        $this->assertEquals($match->getRule(), 'connect-rule');
        $this->assertEquals($match->getStatusCode(), 200);
    }

    public function testNotFound()
    {
        $args = [
            'packunit',
            '-u',
            'user',
            '-p',
            'password'
        ];

        $match = $this->instance->match($args);
        $this->assertTrue($match instanceof ICEntity);
    }
}
