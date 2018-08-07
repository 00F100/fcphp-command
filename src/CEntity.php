<?php

namespace FcPhp\Command
{
    use FcPhp\Command\Interfaces\ICEntity;

    class CEntity implements ICEntity
    {
        /**
         * @var string Command to execute
         */
        private $command;

        /**
         * @var string Action to command
         */
        private $action;

        /**
         * @var string Rule of command
         */
        private $rule;

        /**
         * @var string Status code of request
         */
        private $statusCode = 200;

        /**
         * @var string Status message of request
         */
        private $statusMessage;

        /**
         * @var string Params to controller
         */
        private $params = [];

        /**
         * Method to contruct instance
         *
         * @param array $params Params to entity
         * @return void
         */
        public function __construct(array $params = [])
        {
            foreach ($params as $index => $value) {
                if(property_exists($this, $index)) {
                    $this->{$index} = $value;
                }
            }
        }

        /**
         * Method to get command
         *
         * @return string|null
         */
        public function getCommand()
        {
            return $this->command;
        }

        /**
         * Method to get action
         *
         * @return string|null
         */
        public function getAction()
        {
            return $this->action;
        }

        /**
         * Method to get rule
         *
         * @return string|null
         */
        public function getRule()
        {
            return $this->rule;
        }

        /**
         * Method to get params to controller
         *
         * @return array
         */
        public function getParams() :array
        {
            return $this->params;
        }

        /**
         * Method to get status code
         *
         * @return int
         */
        public function getStatusCode() :int
        {
            return $this->statusCode;
        }

        /**
         * Method to get status message
         *
         * @return string|null
         */
        public function getStatusMessage()
        {
            return $this->statusMessage;
        }
    }
}
