<?php

namespace tkm\tpa;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use tkm\tpa\commands\tpaaccept;
use tkm\tpa\commands\tpacommand;
use tkm\tpa\commands\tpadeny;
use tkm\tpa\commands\tpahere;

class Main extends PluginBase{

    public Config $config;

    public string $prefix;

    public array $tpa = array();

    public function onEnable(): void
    {
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", 2);
        Server::getInstance()->getCommandMap()->registerAll("tpa",[
            new tpacommand($this),
            new tpaaccept($this),
            new tpadeny($this),
            new tpahere($this)
        ]);
        $this->prefix = $this->config->get("prefix");
    }
}
