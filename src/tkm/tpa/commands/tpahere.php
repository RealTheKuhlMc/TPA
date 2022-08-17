<?php

namespace tkm\tpa\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use tkm\tpa\Main;

class tpahere extends Command{

    public Main $pl;

    public function __construct(Main $pl)
    {$this->pl = $pl;
        parent::__construct("tpahere",$this->pl->config->get("tpaherecommand.description") , null, $this->pl->config->get("tpaherecommand.aliases"));
        $this->setPermission("tpa.tpahere.use");

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("no.player"));
            return true;
        }
        if(!isset($args[0])){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("tpahere.usage"));
            return true;
        }else{
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if(!$player){
                $sender->sendMessage($this->pl->prefix . $this->pl->config->get("target.notonline"));
                return true;
            }
            $this->pl->tpa[$player->getName()][$sender->getName()] = array("sender" => $sender->getName(), "time" => time()+ (int)$this->pl->config->get("expiration.time"), "type" => "tpahere");
            $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $player->getName(), $this->pl->config->get("tpahere.succes")));
            $player->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpahere.receive")));
        }
    }
}