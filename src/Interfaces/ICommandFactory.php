<?php

namespace FcPhp\Command\Interfaces
{
    interface ICommandFactory
    {
        /**
         * Method to construct instance
         *
         * @param FcPhp\Di\Interfaces\IDi $di Instance of Di 
         * @return void
         */
        public function __construct(IDi $di = null);

        /**
         * Method to construct instance of Entity
         *
         * @param array $params Params to CEntity
         * @return FcPhp\Command\Interfaces\ICEntity
         */
        public function getEntity(array $params = []) :ICEntity;
    }
}
