<?php

class Monster
{
    private string $type;
    private int $healthPoint;

    public function __construct($type)
    {
        $this->type = $type;
        $this->healthPoint = 100;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHealthPoint()
    {
        return $this->healthPoint;
    }

    public function setHealthPoint($healthPoint)
    {
        $this->healthPoint = $healthPoint;
        if ($this->healthPoint < 0) {
            $this->healthPoint = 0;
        }
    }
}

class Ogre extends Monster
{
    public function __construct()
    {
        parent::__construct(get_class($this));
    }

    public function hit(Hero $hero)
    {
        $criticalAttack = false;
        $dammage = rand(0, 25);
        if ($hero->getClass() == "Archer") {
            $dammage *= 2;
            $criticalAttack = true;
        }
        $heroHealthPoint = $hero->getHealthPoint();
        $hero->setHealthPoint($heroHealthPoint - $dammage);
        $dammageResult = new DammageResult($dammage, $criticalAttack);
        return $dammageResult;
    }
}

class Wizard extends Monster
{
    public function __construct()
    {
        parent::__construct(get_class($this));
    }

    public function hit(Hero $hero)
    {
        $criticalAttack = false;
        $dammage = rand(0, 25);
        if ($hero->getClass() == "Warrior") {
            $dammage *= 2;
            $criticalAttack = true;
        }
        $heroHealthPoint = $hero->getHealthPoint();
        $hero->setHealthPoint($heroHealthPoint - $dammage);
        $dammageResult = new DammageResult($dammage, $criticalAttack);
        return $dammageResult;
    }
}

class Infantryman extends Monster
{
    public function __construct()
    {
        parent::__construct(get_class($this));
    }

    public function hit(Hero $hero)
    {
        $criticalAttack = false;
        $dammage = rand(0, 25);
        if ($hero->getClass() == "Mage") {
            $dammage *= 2;
            $criticalAttack = true;
        }
        $heroHealthPoint = $hero->getHealthPoint();
        $hero->setHealthPoint($heroHealthPoint - $dammage);
        $dammageResult = new DammageResult($dammage, $criticalAttack);
        return $dammageResult;
    }
}