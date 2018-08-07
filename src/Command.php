<?php

namespace FcPhp\Command
{
    use FcPhp\Command\Interfaces\ICommand;
    use FcPhp\SConsole\Interfaces\ISCEntity;
    use FcPhp\Autoload\Interfaces\IAutoload;
    use FcPhp\Cache\Interfaces\ICache;
    use FcPhp\Command\Interfaces\ICEntity;
    use FcPhp\Command\Interfaces\ICommandFactory;

    class Command implements ICommand
    {
        const TTL_COMMAND = 84000;

        /**
         * @var string Key to cache
         */
        private $key;

        /**
         * @var FcPhp\SConsole\Interfaces\ISCEntity
         */
        private $entity;

        /**
         * @var FcPhp\Autoload\Interfaces\IAutoload
         */
        private $autoload;

        /**
         * @var FcPhp\Cache\Interfaces\ICache
         */
        private $cache;

        /**
         * @var FcPhp\Command\Interfaces\ICommandFactory
         */
        private $factory;

        /**
         * @var array List of commands 
         */
        private $commands = [];

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
        public function __construct(ISCEntity $entity, IAutoload $autoload, ICache $cache, string $vendorPath, ICommandFactory $factory, array $commands = [], bool $noCache = false)
        {
            $this->key = md5(serialize($vendorPath) . serialize($commands));
            $this->entity = $entity;
            $this->factory = $factory;
            $this->autoload = $autoload;
            $this->cache = $cache;
            $this->commands = $this->cache->get($this->key);
            if(empty($this->commands)) {
                $this->commands = $commands;
                $this->autoload->path($vendorPath, ['commands'], ['php']);
                $this->commands = $this->merge($this->commands, $this->autoload->get('commands'));
                if(!$noCache) {
                    $this->cache->set($this->key, $this->commands, self::TTL_COMMAND);
                }
            }
        }

        /**
         * Method to match command and return CEntity
         *
         * @param array $args Console args
         * @return FcPhp\Command\Interfaces\ICEntity
         */
        public function match(array $args) :ICEntity
        {
            return $this->find($args, $args);
        }

        /**
         * Method to find command into list
         *
         * @param array $args Console args
         * @param array $fullArgs Console args non updated
         * @return FcPhp\Command\Interfaces\ICEntity
         */
        private function find(array $args, array $fullArgs) :ICEntity
        {
            $count = 0;
            $commands = $this->commands;
            $command = current($args);
            if(isset($commands[$command])) {
                $oldCommand = $command;
                $command = next($args);
                foreach($commands[$oldCommand] as $blockCommand) {
                    if($blockCommand['command'] == $command) {
                        if(substr($blockCommand['action'], 0, 1) == ':') {
                            foreach($args as $index => $item) {
                                if($command !== $item) {
                                    unset($args[$index]);
                                }else{
                                    break;
                                }
                            }
                            return $this->find($args, $fullArgs);
                        }else{
                            foreach($args as $index => $item) {
                                if($command !== $item) {
                                    unset($args[$index]);
                                }else{
                                    unset($args[$index]);
                                    break;
                                }
                            }
                            $blockCommand['params'] = $args;
                            if(!empty($blockCommand['rule'])) {
                                if(!$this->entity->check($blockCommand['rule'])) {
                                    $this->resetCountArray($args);
                                    return $this->factory->getEntity([
                                        'command' => $blockCommand['command'],
                                        'action' => $blockCommand['action'],
                                        'rule' => $blockCommand['rule'],
                                        'statusCode' => 403,
                                        'statusMessage' => 'Forbidden',
                                        'params' => $args,
                                    ]);
                                }
                            }

                            return $this->factory->getEntity($blockCommand);
                        }
                    }
                }
            }

            return $this->factory->getEntity([
                'statusCode' => 404,
                'statusMessage' => 'Not Found',
                'params' => $fullArgs,
            ]);
        }

        /**
         * Method to reset count of arrau
         *
         * @param array $args Array to reset
         * @return void
         */
        private function resetCountArray(array &$args) :void
        {
            $array = [];
            foreach($args as $value) {
                $array[] = $value;
            }
            $args = $array;
        }

        /**
         * Method to merge commands
         *
         * @param array $array1 Array Commands A
         * @param array $array2 Array Commands B
         * @param array $array3 Array Commands C
         * @return array
         */
        private function merge() :array
        {
            $commands = [];
            $listCommands = func_get_args();

            foreach($listCommands as $item) {
                foreach($item as $command => $params) {
                    if(!isset($commands[$command])) {
                        $commands[$command] = [];
                    }
                    foreach($params as $param) {
                        $commands[$command][] = $this->defaults($param);
                    }
                }
            }
            return $commands;
        }

        /**
         * Method to add commands into map
         *
         * @param array $command Configuration to command
         * @return array
         */
        private function defaults(array $command) :array
        {
            $defaults = [
                'command' => null,
                'action' => null,
                'rule' => null,
                'statusCode' => 200,
                'statusMessage' => null,
                'params' => [],
            ];
            return array_merge($defaults, $command);
        }
    }
}
