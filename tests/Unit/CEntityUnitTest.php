<?php

use FcPhp\Command\CEntity;
use PHPUnit\Framework\TestCase;
use FcPhp\Command\Interfaces\ICEntity;

class CEntityUnitTest extends TestCase
{
    public function setUp()
    {
        $params = [
            'command' => 'test-command',
            'action' => 'test@action',
            'rule' => 'test-rule',
            'statusCode' => 408,
            'statusMessage' => 'test message',
            'params' => [20, 30],
        ];
        $this->instance = new CEntity($params);
    }

    public function testInstance()
    {
        $this->assertTrue($this->instance instanceof ICEntity);
    }

    public function testGetCommand()
    {
        $this->assertEquals($this->instance->getCommand(), 'test-command');
    }

    public function testGetAction()
    {
        $this->assertEquals($this->instance->getAction(), 'test@action');
    }

    public function testGetRule()
    {
        $this->assertEquals($this->instance->getRule(), 'test-rule');
    }

    public function testGetStatusCode()
    {
        $this->assertEquals($this->instance->getStatusCode(), 408);
    }

    public function testGetStatusMessage()
    {
        $this->assertEquals($this->instance->getStatusMessage(), 'test message');
    }

    public function testGetParams()
    {
        $this->assertEquals($this->instance->getParams(), [20, 30]);
    }
}
