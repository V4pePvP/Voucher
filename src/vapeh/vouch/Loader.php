<?php

namespace vapeh\vouch;

use vapeh\vouch\command\SetCmdCommand;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Loader extends PluginBase implements Listener
{

    protected function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register('setcmd', new SetCmdCommand());
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function handlerItemCommandUse(PlayerItemUseEvent $ev): void
    {
        $item = $ev->getItem();
        $player = $ev->getPlayer();

        if ($item->getNamedTag()->getTag('commands') !== null) {
            $commands = explode('\n', $item->getNamedTag()->getString('commands'));
            foreach ($commands as $command) {
                $this->getServer()->dispatchCommand(new ConsoleCommandSender($server = Server::getInstance(), $server->getLanguage()), str_replace('{player}',  "\"{$player->getName()}\"", $command));
            }
            $item->pop();
            $player->getInventory()->setItemInHand($item);
        }
    }

}
