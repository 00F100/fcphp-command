<?php

namespace FcPhp\Command
{
    use FcPhp\Command\Interfaces\ICEntity;

    class CEntity implements ICEntity
    {
        private $command;
        private $action;
        private $rule;
        private $statusCode = 200;
        private $statusMessage;
        private $params = [];

        public function __construct(array $params = [])
        {
            foreach ($params as $index => $value) {
                if(property_exists($this, $index)) {
                    $this->{$index} = $value;
                }
            }
        }

        public function getCommand()
        {
            return $this->command;
        }

        public function getAction()
        {
            return $this->action;
        }

        public function getRule()
        {
            return $this->rule;
        }

        public function getParams() :array
        {
            return $this->params;
        }

        public function getStatusCode() :int
        {
            return $this->statusCode;
        }

        public function getStatusMessage()
        {
            return $this->statusMessage;
        }
    }
}
