<?php
declare(strict_types=1);
#=========================================#
# Plugin Custom NPC Made By HighestDreams #
#=========================================#
namespace HighestDreams\CustomNPC\Form;

use HighestDreams\CustomNPC\{Entity\CustomNPC, Form\formapi\FormAPI, lang, NPC, Session};
use pocketmine\Player;
use pocketmine\utils\TextFormat as COLOR;

class CustomizeMain
{

    public $npc;
    public $lang;

    public function __construct(NPC $npc)
    {
        $this->npc = $npc;
        $this->lang = new lang(NPC::getInstance());
    }

    /**
     * @param Player $player
     * @param CustomNPC $npc
     */
    public function send(Player $player, CustomNPC $npc)
    {
        $form = (new FormAPI())->createSimpleForm(function (Player $player, $data = null) use ($npc) {
            if (is_null($data)) return;

            switch ($data) {
                case 0:
                    (new SettingsManager(NPC::getInstance()))->send($player, $npc);
                    break;
                case 1:
                    (new CommandsManager(NPC::getInstance()))->send($player, $npc);
                    break;
                case 2:
                    (new Session(NPC::getInstance()))::setTeleporting($player, true, $npc->getId());
                    $player->sendMessage(NPC::PREFIX . COLOR::GREEN . $this->lang::get($this->lang::NPC_TELEPORT_TUTORIAL));
                    break;
                case 3:
                    $this->delete($player, $npc);
                    break;
            }
        });
        $form->setTitle("§4NPC §3Customize §5Menu");
        $form->setContent("§3+ §6NPC ID §b: §a" . $npc->getId());
        $form->addButton('§8§lManage settings');
        $form->addButton('§8§lManage commands');
        $form->addButton('§8§lTeleport NPC');
        $form->addButton('§cDelete NPC');
        $form->sendToPlayer($player);
    }

    /**
     * @param Player $player
     * @param CustomNPC $npc
     */
    public function delete(Player $player, CustomNPC $npc)
    {
        $form = (new FormAPI())->createModalForm(function (Player $player, $data = null) use ($npc) {
            if (is_null($data)) return;

            switch ($data) {
                case true:
                    $npc->flagForDespawn();
                    $player->sendMessage(NPC::PREFIX . COLOR::GREEN . $this->lang::get($this->lang::NPC_DELETATION));
                    break;
                case false:
                    $this->send($player, $npc);
                    break;
            }
        });
        $form->setTitle("§4DELETE NPC");
        $form->setContent("§3+ §6NPC ID §b: §a" . $npc->getId() . " §6" . $this->lang::get($this->lang::NPC_DELETE_SURE));
        $form->setButton1('§l§aYes, Delete NPC');
        $form->setButton2('§l§cNo, Do not Delete NPC');
        $form->sendToPlayer($player);
    }
}