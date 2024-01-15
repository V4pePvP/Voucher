<?php

namespace vapeh\vouch\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SetCmdCommand extends Command
{
    public function __construct()
    {
        parent::__construct('setcmd', 'Usa este comando para establecer un comando al item *Uso click derecho*');
        $this->setPermission('setcmd.command');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) return;


        if (!$this->testPermission($sender)){
            return;
        }

        if (count($args) < 1){
            $sender->sendMessage(TextFormat::colorize('&cUse /setcmd [string:command]'));
            return;
        }
        if ($sender->getInventory()->getItemInHand()->getTypeId() === VanillaItems::AIR()->getTypeId()) {
            $sender->sendMessage(TextFormat::colorize('&cYou need a item in your hand!'));
            return;
        }
        $item = $sender->getInventory()->getItemInHand();
        if ($item->getNamedTag()->getTag('commands') !== null) {
            $item->getNamedTag()->setString('commands', $item->getNamedTag()->getString('commands') . '\n' . implode(' ', $args));
        } else {
            $item->getNamedTag()->setString('commands', implode(' ', $args));
        }
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage(TextFormat::colorize('&aItem added command successfully!'));
    }
}
