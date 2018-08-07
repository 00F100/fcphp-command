<?php

namespace FcPhp\Command\Interfaces
{
    use FcPhp\SConsole\Interfaces\ISCEntity;
    use FcPhp\Autoload\Interfaces\IAutoload;
    use FcPhp\Cache\Interfaces\ICache;
    use FcPhp\Command\Interfaces\ICommandFactory;
    use FcPhp\Command\Interfaces\ICEntity;
    
    interface ICommand
    {
        /**
         * Method to construct instance
         *
         * @param FcPhp\SConsole\Interfaces\ISCEntity $entity Security Command Entity
         * @param FcPhp\Autoload\Interfaces\IAutoload $autoloa Autoload files
         * @param FcPhp\Cache\Interfaces\ICache $cache Cache commands
         * @param string $vendorPath Path to load files using Autoload
         * @param FcPhp\Command\Interfaces\ICommandFactory $factory Factory to create instance of CEntity
         * @param array $commands
         * @param bool $noCache No use cache?
         * @return void
         */
        public function __construct(ISCEntity $entity, IAutoload $autoload, ICache $cache, string $vendorPath, ICommandFactory $factory, array $commands = [], bool $noCache = false);

        /**
         * Method to match command and return CEntity
         *
         * @param array $args Console args
         * @return FcPhp\Command\Interfaces\ICEntity
         */
        public function match(array $args) :ICEntity;
    }
}
