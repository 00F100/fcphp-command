<?php

namespace FcPhp\Command\Facades
{
    use FcPhp\Command\Command;
    use FcPhp\Autoload\Autoload;
    use FcPhp\Di\Facades\DiFacade;
    use FcPhp\Cache\Facades\CacheFacade;
    use FcPhp\Command\Interfaces\ICommand;
    use FcPhp\SConsole\Interfaces\ISCEntity;
    use FcPhp\Command\Factories\CommandFactory;

    class CommandFacade
    {
        private static $instance;

        public static function getInstance(ISCEntity $entity, array $commands = [], string $vendorAutoload = null, bool $di = true)
        {
            if(!self::$instance instanceof ICommand) {
                if($di) {
                    $di = DiFacade::getInstance();
                }
                $autoload = new Autoload();
                $cache = CacheFacade::getInstance('tests/var/cache');
                $factory = new CommandFactory($di);

                self::$instance = new Command($entity, $autoload, $cache, $vendorAutoload, $factory, $commands);
            }
            return self::$instance;
        }
    }
}
