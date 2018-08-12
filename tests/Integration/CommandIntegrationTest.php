<?php

use PHPUnit\Framework\TestCase;
use FcPhp\Command\Command;
use FcPhp\Command\Interfaces\ICommand;
use FcPhp\Command\Interfaces\ICEntity;
use FcPhp\Command\CEntity;
use FcPhp\Command\Factories\CommandFactory;
use FcPhp\Autoload\Autoload;
use FcPhp\SConsole\SCEntity;
use FcPhp\Cache\Facades\CacheFacade;
use FcPhp\Di\Facades\DiFacade;
use FcPhp\Command\Facades\CommandFacade;

class CommandIntegrationTest extends TestCase
{
    public function setUp()
    {
        $this->entity = new SCEntity();
        $this->entity->setType('user');
        $this->entity->setPermissions(['example-permission', 'connect-datasource', 'connect-rule']);
        $this->vendorPath = 'tests/*/*/config';
        $this->commands = [
            'package' => [
                [
                    'command' => 'inject',
                    'action' => 'Example@method',
                    'rule' => 'inject-permission',
                    'params' => [],
                ],
            ]
        ];

        $this->instance = CommandFacade::getInstance($this->entity, $this->commands, $this->vendorPath);
    }

    public function testInstance()
    {
        $this->assertTrue($this->instance instanceof ICommand);
    }

    public function testMatchCommand()
    {
        // $ package datasource connect -h localhost -u user -p password
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
            'package',
            '-u',
            'user',
            '-p',
            'password'
        ];

        $match = $this->instance->match($args);
        $this->assertEquals($match->getStatusCode(), 404);
        $this->assertEquals($match->getStatusMessage(), 'Not Found');
    }

    public function testNoPermission()
    {
        $args = [
            'package',
            'exampleNonPermission',
            '-u',
            'user',
            '-p',
            'password'
        ];

        $match = $this->instance->match($args);
        $this->assertEquals($match->getStatusCode(), 403);
        $this->assertEquals($match->getStatusMessage(), 'Forbidden');
    }

    public function testNonUseDi()
    {
        $entity = new SCEntity();
        $entity->setType('user');
        $entity->setPermissions(['example-permission', 'connect-datasource', 'connect-rule']);
        $autoload = new Autoload();
        $cache = CacheFacade::getInstance('tests/var/cache');
        $vendorPath = 'tests/*/*/config';
        $factory = new CommandFactory();
        $commands = [
            'package' => [
                [
                    'command' => 'inject',
                    'action' => 'Example@method',
                    'rule' => 'inject-permission',
                    'params' => [],
                ],
            ]
        ];

        $instance = new Command($entity, $autoload, $cache, $vendorPath, $factory, $commands);
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
        $match = $instance->match($args);
        $this->assertTrue($match instanceof ICEntity);
        $this->assertTrue(is_array($match->getParams()));
        $this->assertEquals($match->getAction(), 'ControllerDatasource@method');
        $this->assertEquals($match->getCommand(), 'connect');
        $this->assertEquals($match->getRule(), 'connect-rule');
        $this->assertEquals($match->getStatusCode(), 200);
    }
}
