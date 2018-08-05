<?php

namespace FcPhp\Command\Factories
{
    use FcPhp\Command\CEntity;
    use FcPhp\Di\Interfaces\IDi;
    use FcPhp\Command\Interfaces\ICEntity;
    use FcPhp\Command\Interfaces\ICommandFactory;

    class CommandFactory implements ICommandFactory
    {
        private $di;

        /**
         * Method to construct instance
         *
         * @param FcPhp\Di\Interfaces\IDi $di Instance of Di 
         * @return void
         */
        public function __construct(IDi $di = null)
        {
            $this->di = $di;
        }
        
        /**
         * Method to construct instance of Entity
         *
         * @param array $params Params to CEntity
         * @return FcPhp\Command\Interfaces\ICEntity
         */
        public function getEntity(array $params = []) :ICEntity
        {
            if($this->di instanceof IDi) {
                if(!$this->di->has('FcPhp/Command/CEntity')) {
                    $this->di->setNonSingleton('FcPhp/Command/CEntity', 'FcPhp\Command\CEntity');
                }
                return $this->di->make('FcPhp/Command/CEntity', ['params' => $params]);
            }
            return new CEntity($params);
        }
    }
}
