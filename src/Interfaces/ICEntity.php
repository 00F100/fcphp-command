<?php

namespace FcPhp\Command\Interfaces
{
    interface ICEntity
    {
        
        public function __construct(array $params = []);

        public function getCommand();

        public function getAction();

        public function getRule();

        public function getParams() :array;

        public function getStatusCode() :int;

        public function getStatusMessage();
    }
}
