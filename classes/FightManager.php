<?php

session_start();

require_once 'HeroesManager.php';
require_once 'Hero.php';
require_once 'Monster.php';

class FightManager
{
    private array $monstersType = ['Ogre', 'Wizard', 'Infantryman'];

    public function createMonster()
    {
        return new $this->monstersType[array_rand($this->monstersType)]();
    }

    public function monsterAttack($hero, $monster)
    {
        $monsterDammage = $monster->hit($hero);

        $textWin = '';

        if ($monsterDammage->criticalAttack)
        {
            $textDamage = $monster->getType() . " a infligé " . $monsterDammage->dammage . " points ( attaque critique ) de dégats";
        }
        else
        {
            $textDamage = $monster->getType() . " a infligé " . $monsterDammage->dammage . " points  de dégats";
        }

        $heroesManager = new HeroesManager(new PDO('mysql:host=localhost;dbname=poo_combat', 'root', ''));
        $heroesManager->update( $hero->getId(), $hero->getHealthPoint(), $hero->getSpecialAttack());

        if ($hero->getHealthPoint() <= 0)
        {
            $textWin = $monster->getType() . " a gagné la bataille !";
        }

        return [
            'textDamage' => $textDamage,
            'textWin' => $textWin
        ];
    }

    public function heroAttack($hero, $monster)
    {
        $heroDammage = $hero->hit($monster);

        $textWin = '';

        if ($heroDammage->criticalAttack)
        {
            $textDamage = $hero->getName() . " a infligé " . $heroDammage->dammage . " points ( attaque spéciale ) de dégats";
        }
        else
        {
            $textDamage = $hero->getName() . " a infligé " . $heroDammage->dammage . " points  de dégats";
        }

        $heroesManager = new HeroesManager(new PDO('mysql:host=localhost;dbname=poo_combat', 'root', ''));
        $heroesManager->update( $hero->getId(), $hero->getHealthPoint(), $hero->getSpecialAttack());

        if ($monster->getHealthPoint() <= 0)
        {
            $textWin = $hero->getName() . " a gagné la bataille !";
        }

        return [
            "textDamage" => $textDamage,
            "textWin" => $textWin,
        ];
    }

    public function specialAttack($monster, $hero)
    {
        $dammage = $hero->specialAttack($monster);
        $monster->setHealthPoint($monster->getHealthPoint() - $dammage);

        return [
            'specialAttack' => $hero->getSpecialAttack(),
            'monsterHP' => $monster->getHealthPoint(),
        ];
    }
}

class DammageResult
{
    public $dammage;
    public $criticalAttack;

    public function __construct($dammage, $criticalAttack)
    {
        $this->dammage = $dammage;
        $this->criticalAttack = $criticalAttack;
    }
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['action']))
{
    $heroesManager = new HeroesManager(new PDO('mysql:host=localhost;dbname=poo_combat', 'root', ''));

    $hero = $heroesManager->find($data['heroId']);

    $fightManager = new FightManager();

    if ($data['action'] != 'cure')
    {
        $monster = new $data['monsterType']();
        $monster->setHealthPoint($data['monsterHealthPoint']);
    }

    if ($data['action'] == 'attack')
    {
        $result = $fightManager->heroAttack($hero, $monster);
        echo json_encode([
            'textDamage' => $result['textDamage'],
            'monsterHP' => $monster->getHealthPoint(),
            'textWin' => $result['textWin'],
            'specialAttack' => $hero->getSpecialAttack()
        ]);
    }
    else if ($data['action'] == 'monsterAttack')
    {
        $result = $fightManager->monsterAttack($hero, $monster);
        echo json_encode([
            'textDamage' => $result['textDamage'],
            'heroHP' => $hero->getHealthPoint(),
            'textWin' => $result['textWin']
        ]);
    }
    else if ($data['action'] == 'cure')
    {
        $hero->heroCure($data['heroHP']);
        $heroesManager->update( $hero->getId(), $hero->getHealthPoint(), $hero->getSpecialAttack());
        echo json_encode([
            'heroHP' => $hero->getHealthPoint(),
            'specialAttack' => $hero->getSpecialAttack()
        ]);
    }
    else if ($data['action'] == 'specialAttack')
    {
        $monster->setHealthPoint($data['monsterHealthPoint']);
        $result = $fightManager->specialAttack($monster, $hero);
        $heroesManager->update( $hero->getId(), $hero->getHealthPoint(), $hero->getSpecialAttack());
        echo json_encode([
            'monsterHP' => $result['monsterHP'],
            'specialAttack' => $result['specialAttack']
        ]);
    }
}

