<?php

require_once __DIR__ . '/config/db.php';

if (isset($_POST['id']))
{

require_once 'classes/HeroesManager.php';
require_once 'classes/Hero.php';
require_once 'classes/Monster.php';
require_once 'classes/FightManager.php';

$heroesManager = new HeroesManager($db);

$hero = $heroesManager->find($_POST['id']);

$fightManager = new FightManager();

$monster = $fightManager->createMonster();

$textDamage = $fightManager->monsterAttack($hero, $monster)['textDamage']; $heroesManager->update( $hero->getId(), $hero->getHealthPoint(), $hero->getSpecialAttack());

$heroHealthPoint = $hero->getHealthPoint();

if ($heroHealthPoint <= 0)
{
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
</head>
<body class="w-full h-screen">
    <img src="img/fight.png" class="background">
    <div class="w-full h-2/3 flex flex-row">
        <div class="w-1/2 h-full flex flex-col items-center justify-around">
            <span class="font-semibold text-white"><?php echo $hero->getName(); ?></span>

            <div class="flex flex-col justify-between h-9 w-2/3">
                <div id="health-bar" class="w-full h-3 border border-black rounded-md bg-white">
                    <div id="hero-health" class="h-full bg-green-500 rounded-md" style="width: <?php echo $heroHealthPoint; ?>%"></div>
                </div>

                <div id="special-attack-bar" class="w-full h-3 border border-black rounded-md bg-white">
                    <div id="special-attack" class="h-full bg-blue-500 rounded-md" style="width: <?php echo $hero->getSpecialAttack(); ?>%"></div>
                    <input type="hidden" id="special-attack-value" value="<?php echo $hero->getSpecialAttack(); ?>">
                </div>
            </div>

            <img src="img/<?php echo $hero->getClass(); ?>.png" class="w-2/3 h-2/3 border border-black">
        </div>

        <div class="w-1/2 h-full flex flex-col items-center justify-around">
            <span class="font-semibold text-white"><?php echo $monster->getType(); ?></span>
            
            <div class="flex flex-col justify-center h-9 w-2/3">
                <div id="monster-health-bar" class="w-full h-3 border border-black rounded-md bg-white">
                    <div id="monster-health" class="h-full bg-green-500 rounded-md" style="width: <?php echo $monster->getHealthPoint(); ?>%"></div>
                    <input type="hidden" id="monster-health-value" value="<?php echo $monster->getHealthPoint(); ?>">
                </div>
            </div>

            <img src="img/<?php echo $monster->getType(); ?>.png" class="w-2/3 h-2/3 border border-black">
        </div>
    </div>

    <div class="w-full h-1/3 flex flex-col justify-around items-center">
        <div id="fight" class="border border-black w-1/2 h-2/3 rounded-md flex flex-col items-center overflow-y-auto bg-white">
            <div id="current-div" class="bg-red-400 border border-black rounded-md w-4/5 my-4 text-center">
                <span class="text-white"><?php echo $textDamage; ?></span>
                <input id="hero-health-point" type="hidden" value="<?php echo $hero->getHealthPoint(); ?>">
            </div>
        </div>

        <input type="hidden" id="hero-id" value="<?php echo $hero->getId(); ?>">
        <input type="hidden" id="monster-type" value="<?php echo $monster->getType(); ?>">
        <input type="hidden" id="monster-health-point" value="<?php echo $monster->getHealthPoint(); ?>">

        <div class="flex flex-row w-1/2 justify-around">
            <button id="btn-attack" class="btn text-white" style="background-color: red;" onclick="heroAttack()">Attack</button>
            <button id="btn-special-attack" class="btn text-white" style="background-color: gray; cursor: default;" onclick="heroSpecialAttack()">Special Attack</button>
            <button id="btn-cure" class="btn text-white" style="background-color: gray; cursor: default;" onclick="heroCure()">Cure</button>
        </div>
    </div>
<script src="js/fight.js"></script>
</body>
</html>        

<?php
}
else
{
    header('Location: index.php');
}