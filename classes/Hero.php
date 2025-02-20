<?php

require_once __DIR__ . '/../config/db.php';

class Hero 
{
    private int $id;
    private string $name;
    private int $health_point;
    private string $special_attack_type;
    private string $special_attack;
    private string $class;
    private PDO $db;

    public function __construct($name, $special_attack_type, $class)
    {
        $this->name = $name;
        $this->health_point = 100;
        $this->special_attack_type = $special_attack_type;
        $this->special_attack = 0;
        $this->class = $class;
        global $db;
        $this->db = $db;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSpecialAttack()
    {
        return $this->special_attack;
    }
    
    public function getClass()
    {
        return $this->class;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHealthPoint()
    {
        return $this->health_point;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setHealthPoint($health_point)
    {
        $this->health_point = $health_point;
        if ($this->health_point < 0) {
            $this->health_point = 0;
        }
    }

    public function setSpecialAttack($special_attack)
    {
        $this->special_attack = $special_attack;
    }

    public function heroCure()
    {
        $heroHealthPoint = $this->getHealthPoint();
        if ($heroHealthPoint < 50) {
            $this->setHealthPoint($heroHealthPoint + 50);
        }
        else {
            $this->setHealthPoint(100);
        }

        $this->setSpecialAttack(0);
    }

    public function hit(Monster $monster)
    {
        $dammage = rand(0, 25);
        $specialAttack = false;
        $dammageResult = new DammageResult($dammage, $specialAttack);

        $monsterHealthPoint = $monster->getHealthPoint();
        $monster->setHealthPoint($monsterHealthPoint - $dammage);

        $addspecialAttack = rand(25, 35);
        $special_Attack = $this->getSpecialAttack() + $addspecialAttack;
        
        if ($special_Attack > 100) {
            $special_Attack = 100;
        }

        $this->setSpecialAttack($special_Attack);

        $heroesManager = new HeroesManager($this->db);
        $heroesManager->update($this->getId(), $this->getHealthPoint(), $this->getSpecialAttack());

        return $dammageResult;
    }

    public function specialAttack($monster)
    {
        $attack = rand(25, 50);
        $criticalAttack = true;
        $criticalAttackResult = new DammageResult($attack, $criticalAttack);

        $monsterHealthPoint = $monster->getHealthPoint();
        $monster->setHealthPoint($monsterHealthPoint - $attack);

        $this->setSpecialAttack(0);

        return $criticalAttackResult;
    }
}

class Warrior extends Hero
{
    public function __construct($name)
    {
        parent::__construct($name, "Weakening", get_class($this));
    }
}

class Archer extends Hero
{
    public function __construct($name)
    {
        parent::__construct($name, "Invisibility", get_class($this));
    }
}

class Mage extends Hero
{
    public function __construct($name)
    {
        parent::__construct($name, "Poison", get_class($this));
    }
}