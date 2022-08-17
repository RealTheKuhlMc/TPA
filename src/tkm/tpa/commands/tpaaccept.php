<?php

namespace tkm\tpa\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use tkm\tpa\Main;

class tpaaccept extends Command{

    public Main $pl;

    public function __construct(Main $pl)
    {
        $this->pl = $pl;
        parent::__construct("tpaccept",$this->pl->config->get("tpacceptcommand.description") , null, $this->pl->config->get("tpacceptcommand.aliases"));
        $this->setPermission("tpa.tpaaccept.use");

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("no.player"));
            return true;
        }
        if(!isset($args[0])){
            $sender->sendMessage($this->pl->prefix . $this->pl->config->get("tpaccept.usage"));
            return true;
        }else{
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);
            if(!$player){
                $sender->sendMessage($this->pl->prefix . $this->pl->config->get("target.notonline"));
                return true;
            }
            if(isset($this->pl->tpa[$sender->getName()][$player->getName()])){
                $tpasender = $this->pl->tpa[$sender->getName()][$player->getName()]["sender"];
                $time = $this->pl->tpa[$sender->getName()][$player->getName()]["time"];
                $type = $this->pl->tpa[$sender->getName()][$player->getName()]["type"];
                if($time > time()){
                    $tpasender1 = Server::getInstance()->getPlayerExact($tpasender);
                    if(!$tpasender1){
                        $sender->sendMessage($this->pl->prefix . $this->pl->config->get("target.notonline"));
                        unset($this->pl->tpa[$sender->getName()][$tpasender]);
                        return true;
                    }else{
                        if($type === "tpa") {
                            $tpasender1->teleport(new Position($sender->getPosition()->x, $sender->getPosition()->y, $sender->getPosition()->z, $sender->getPosition()->getWorld()));
                            $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpaccept.succes")));
                            $tpasender1->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpaccept.accepted")));
                            unset($this->pl->tpa[$sender->getName()][$tpasender]);
                        }else{
                            $sender->teleport(new Position($tpasender1->getPosition()->x, $tpasender1->getPosition()->y, $tpasender1->getPosition()->z, $tpasender1->getPosition()->getWorld()));
                            $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpaccept.succes")));
                            $tpasender1->sendMessage($this->pl->prefix . str_replace("{player_name}", $sender->getName(), $this->pl->config->get("tpaccept.accepted")));
                            unset($this->pl->tpa[$sender->getName()][$tpasender]);
                        }
                    }
                }else{
                    $sender->sendMessage($this->pl->prefix . $this->pl->config->get("tpaccept.expired"));
                    unset($this->pl->tpa[$sender->getName()][$tpasender]);
                    return true;
                }
            }else{
                $sender->sendMessage($this->pl->prefix . str_replace("{player_name}", $player->getName(), $this->pl->config->get("tpaccept.notpa")));
                return true;
            }
        }
    }
}
