<?php

namespace FcPhp\Command\Interfaces
{
    interface ICEntity
    {
        /**
         * Method to contruct instance
         *
         * @param array $params Params to entity
         * @return void
         */
        public function __construct(array $params = []);

        /**
         * Method to get command
         *
         * @return string|null
         */
        public function getCommand();

        /**
         * Method to get action
         *
         * @return string|null
         */
        public function getAction();

        /**
         * Method to get rule
         *
         * @return string|null
         */
        public function getRule();

        /**
         * Method to get params to controller
         *
         * @return array
         */
        public function getParams() :array;

        /**
         * Method to get status code
         *
         * @return int
         */
        public function getStatusCode() :int;

        /**
         * Method to get status message
         *
         * @return string|null
         */
        public function getStatusMessage();
    }
}
