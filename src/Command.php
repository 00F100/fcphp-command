<?php

namespace FcPhp\Command
{
    use FcPhp\Command\Interfaces\ICommand;
    use FcPhp\SConsole\Interfaces\ISCEntity;
    use FcPhp\Autoload\Interfaces\IAutoload;
    use FcPhp\Cache\Interfaces\ICache;
    use FcPhp\Command\Interfaces\ICEntity;

    class Command implements ICommand
    {
        private $entity;
        private $autoload;
        private $cache;
        private $commandEntity;
        private $commands = [];

        public function __construct(ISCEntity $entity, IAutoload $autoload, ICache $cache, string $vendorPath, ICEntity $commandEntity, array $commands = [])
        {
            $this->entity = $entity;
            $this->autoload = $autoload;
            $this->cache = $cache;
            $this->commandEntity = $commandEntity;
            $this->commands = $commands;
        }
    }
}