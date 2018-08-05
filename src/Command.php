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
        private $key;
        private $entity;
        private $autoload;
        private $cache;
        private $factory;
        private $commands = [];

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

        public function match(array $args)
        {
            return $this->find($args, $args);
        }

        private function find(array $args, array $fullArgs)
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
