<?php
declare(strict_types=1);
#=========================================#
# Plugin Custom NPC Made By HighestDreams #
#=========================================#
namespace HighestDreams\CustomNPC\Entity;

use HighestDreams\CustomNPC\NPC;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

class CustomNPC extends Human
{

    public $commands = [];

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
        $this->setGenericFlag(Entity::DATA_FLAG_IMMOBILE, true);
        if (!$this->namedtag->hasTag("Scale", FloatTag::class)) {
            $this->namedtag->setFloat("Scale", 1.0, true);
        }
        $this->getDataPropertyManager()->setFloat(Entity::DATA_SCALE, $this->namedtag->getFloat("Scale"));
    }

    public function updateSkin()
    {
        $this->setSkin(new Skin($this->skin->getSkinId(), $this->getSkinBytes(), "", $this->skin->getGeometryName(), $this->skin->getGeometryData()));
        $this->sendSkin();
    }

    /**
     * @return string
     */
    public function getSkinBytes(): string
    {
        $path = NPC::getInstance()->getDataFolder() . 'steve.png';
        $img = @imagecreatefrompng($path);
        $skinbytes = "";
        $s = (int)@getimagesize($path)[1];
        for ($y = 0; $y < $s; $y++) {
            for ($x = 0; $x < 64; $x++) {
                $colorat = @imagecolorat($img, $x, $y);
                $a = ((~((int)($colorat >> 24))) << 1) & 0xff;
                $r = ($colorat >> 16) & 0xff;
                $g = ($colorat >> 8) & 0xff;
                $b = $colorat & 0xff;
                $skinbytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        return $skinbytes;
    }

    /**
     * @return bool
     */
    public function canBeMovedByCurrents(): bool
    {
        return false;
    }

    public function saveNBT(): void
    {
        parent::saveNBT(); // TODO: Change the autogenerated stub
        $scale = $this->getDataPropertyManager()->getFloat(Entity::DATA_SCALE);
        $this->namedtag->setFloat("Scale", $scale, true);
    }

    /**
     * @return bool
     */
    public function hasMovementUpdate(): bool
    {
        return false;
    }
}