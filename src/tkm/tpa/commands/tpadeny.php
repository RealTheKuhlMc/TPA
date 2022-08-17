<?php

namespace tkm\tpa\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use tkm\tpa\Main;

class tpadeny extends Command{

    public Main $pl;

    public function __construct(Main $pl)
    {
        $this->pl = $pl;
        parent::__construct("tpadeny",$this->pl->config->get("tpadenycommand.description") , null, $this->pl->config->get("tpadenycommand.aliases"));
        $this->setPermission("tpa.tpadeny.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("no.player"));
            return true;
        }
        if(!isset($args[0])){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("tpadeny.usage"));
            return true;
        }else{
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if(!$player){
                $sender->sendMessage($this->pl->prefix . $this->pl->config->get("target.notonline"));
                return true;
            }
            if(!isset($this->pl->tpa[$sender->getName()][$player->getName()])){
                $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $player->getName(), $this->pl->config->get("tpadeny.notpa")));
                return true;
            }else{
                $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $player->getName(), $this->pl->config->get("tpadeny.succes")));
                $player->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpadeny.denied")));
                unset($this->pl->tpa[$sender->getName()][$player->getName()]);
                return true;
            }
        }
    }
}