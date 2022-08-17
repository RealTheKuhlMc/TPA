<?php

namespace tkm\tpa\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use tkm\tpa\Main;

class tpacommand extends Command{

    public Main $pl;

    public function __construct(Main $pl)
    {
        $this->pl = $pl;
        parent::__construct("tpa",$this->pl->config->get("tpacommand.description") , null, $this->pl->config->get("tpacommand.aliases"));
        $this->setPermission("tpa.tpa.use");

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("no.player"));
            return true;
        }
        if(!isset($args[0])){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("tpa.usage"));
            return true;
        }else{
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if(!$player){
                $sender->sendMessage($this->pl->prefix . $this->pl->config->get("target.notonline"));
                return true;
            }
            $this->pl->tpa[$player->getName()][$sender->getName()] = array("sender" => $sender->getName(), "time" => time()+ (int)$this->pl->config->get("expiration.time"), "type" => "tpa");
            $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $player->getName(), $this->pl->config->get("tpa.succes")));
            $player->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpa.receive")));
        }
    }
}