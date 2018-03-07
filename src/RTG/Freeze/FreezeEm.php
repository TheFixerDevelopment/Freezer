<?php

/**
	* All rights reserved RTGNetworkkk
	* GitHub: https://github.com/RTGNetworkkk
	* Author: InspectorGadget
*/

namespace RTG\Freeze;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\utils\Config;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Cancellable;

class FreezeEm extends PluginBase implements Listener {
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("freeze.txt");
		$this->f = new Config($this->getDataFolder() . "freeze.txt");
		$this->getLogger()->warning("
		* Checking Freezer!
		* Version: 1.0.0, checking for update...
		* All set! I'm ready to freeze...
		");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $param) : bool {
		switch(strtolower($cmd->getName())) {
			
			case "freeze":
				if($sender->hasPermission("freeze.player")) {
					if(isset($param[0])) {
						
						$v = $param[0];
						
						$p = $this->getServer()->getPlayer($v);
						
						if($p === null) {
							$sender->sendMessage("§7[§2Freezer§7] §c$v §2isn't a Player!");
						}
						else {
							$n = $p->getName();
							if($this->f->get($n) === false) {
								$this->f->set($n);
								$this->f->save();
								$sender->sendMessage("§7[§cFreezer§7] §3$v §bhas been Permanently Frozen!");
							}
							else {
								$sender->sendMessage("§7[§cFreezer§7] §c$v §2is already Frozen!");
							}
						}
					}
					else {
						$sender->sendMessage("§7Please use: §e/freeze <name>");
					}
				}
				else {
					$sender->sendMessage("§cYou have no permission to use this command!");
				}
				return true;
			break;
			
			case "unfreeze":
				if($sender->hasPermission("unfreeze.player")) {
					if(isset($param[0])) {
						
						$v = $param[0];
						
						if($this->f->get($v) === true) {
							$this->f->remove($v);
							$this->f->save();
							$sender->sendMessage("§7[§cFreezer§7] §3$v §bhas been UnFrozen!");
						}
						else {
							$sender->sendMessage("§7[§cFreezer] §c$v §2isn't Frozen!");
						}
					}
					else {
						$sender->sendMessage("§7Please use: §e/unfreeze <name>");
					}
				}
				else {
					$sender->sendMessage("§cYou have no permission to use this command!");
				}
				return true;
			break;
		}
	}
	
	public function onMove(PlayerMoveEvent $e) {
		$n = $e->getPlayer()->getName();
		if($this->f->get($n) === true) {
			$e->getPlayer()->sendPopup("§bYou are frozen! \n§aPlease screen share with a staff member on discord (If you have discord.)");
			$e->setCancelled();
		}
	}
	
}
