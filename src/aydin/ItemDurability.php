<?php

namespace aydin;

use pocketmine\{
	player\Player,
	Server,
	command\Command,
	command\CommandSender,
	plugin\PluginBase,
	item\ItemFactory,
	item\Tool,
	item\Armor,
	event\Listener,
	event\block\BlockBreakEvent,
	event\player\PlayerJoinEvent,
	utils\Config,
};

class ItemDurability extends PluginBase implements Listener{
	public static $cfg;
	public function onEnable():void{
		$this->getLogger()->info("Eşya Can Aktif");
		self::$cfg = new Config($this->getDataFolder(). "esyacan.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if(!(self::$cfg->get($player->getName()))){
            self::$cfg->set($player->getName(), "Kapali");
            self::$cfg->save();
        }
    }
    public function onBreak(BlockBreakEvent $event){
         $player = $event->getPlayer();
        if(self::$cfg->get($player->getName()) == "Acik"){
        $item = $player->getInventory()->getItemInHand();
        if($item instanceof Tool || $item instanceof Armor ){
        $maxcan = $item->getMaxDurability();
        $can = $item->getDamage();
        $kalan = $maxcan - $can;
        $player->sendPopup("§e » Eşyanın kalan canı: §6$kalan");
         }
        }
    }
    public function onCommand(CommandSender $player, Command $kmt, string $label, array $args): bool{
        if($kmt->getName() == "esyacan"){
            if(self::$cfg->get($player->getName()) == "Kapali"){
            self::$cfg->set($player->getName(), "Acik");
            $player->sendMessage("§2 »§a Eşya Can istatistiği açıldı.");
              self::$cfg->save();
        }else{
            self::$cfg->set($player->getName(), "Kapali");
            $player->sendMessage("§4 » Eşya Can istatistiği kapatıldı.");
            self::$cfg->save();
        }
    }
    return true;
   }
}
