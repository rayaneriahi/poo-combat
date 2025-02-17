<?php

class HeroesManager
{
    private PDO $db;
    private array $heroes = [];

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add($hero)
    {
        $query = $this->db->prepare('INSERT INTO heroes(name, health_point, class, special_attack) VALUES(:name, :health_point, :class, :special_attack)');
        $query->execute([
            'name' => $hero->getName(),
            'health_point' => $hero->getHealthPoint(),
            'class' => $hero->getClass(),
            'special_attack' => $hero->getSpecialAttack()
        ]);
        $id = $this->db->lastInsertId();
        $hero->setId($id);
    }

    public function findAllAlive()
    {
        $query = $this->db->prepare('SELECT * FROM heroes WHERE health_point > 0');
        $query->execute();
        $heroes = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($heroes as $hero)
        {
            $instance = new $hero['class']($hero['name']);
            $instance->setHealthPoint($hero['health_point']);
            $instance->setId($hero['id']);
            $instance->setSpecialAttack($hero['special_attack']);
            $this->heroes[] = $instance;
        }
        return $this->heroes;
    }

    public function find($id)
    {
        $query = $this->db->prepare('SELECT * FROM heroes WHERE id = :id');
        $query->execute([
            'id' => $id
        ]);
        $hero = $query->fetch(PDO::FETCH_ASSOC);
        $instance = new $hero['class']($hero['name'], $hero['class']);
        $instance->setHealthPoint($hero['health_point']);
        $instance->setSpecialAttack($hero['special_attack']);
        $instance->setId($hero['id']);
        return $instance;
    }

    public function update($id, $health_point, $special_attack)
    {
        $query = $this->db->prepare('UPDATE heroes SET health_point = :health_point, special_attack = :special_attack WHERE id = :id');
        $query->execute([
            'health_point' => $health_point,
            'special_attack' => $special_attack,
            'id' => $id
        ]);
    }
}