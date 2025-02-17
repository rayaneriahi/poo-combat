<?php

require_once "classes/HeroesManager.php";
require_once "classes/Hero.php";

$db = new PDO('mysql:host=localhost;dbname=poo_combat', 'root', '');

if (isset($_POST['name']))
{
    $heroesManager = new HeroesManager($db);
    $heroesManager->add(new $_POST['class']($_POST['name']));
}

$heroesManager = new HeroesManager($db);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@300;400;500;600;700;800;900&family=Outfit:wght@100..900&family=Press+Start+2P&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <img src="img/background.png" class="background">
    <div class="w-full h-[50vh] flex-row">
        <div class="w-demi-h-full center">
            <span class="title text-5xl">Heroes - Combat</span>
        </div>
        <div class="w-demi-h-full center">
            <form method="post" class="flex-col border rounded-md padding-15 center bg-white">
                <span class="font-semibold">Add a hero</span>
                <div class="flex-row">
                    <input type="text" name="name" placeholder="Name" class="margin-15 input">
                    <select name="class" class="margin-15 btn">
                        <option value="Warrior">Warrior</option>
                        <option value="Mage">Mage</option>
                        <option value="Archer">Archer</option>
                    </select>
                </div>
                <input type="submit" value="Create" class="margin-15 w-min btn bg-blue-400 text-white">
            </form>
        </div>
    </div>
    <div class="w-full center flex flex-row flex-wrap">
        <?php foreach ($heroesManager->findAllAlive() as $hero) { ?>
            
            <div class='h-[50vh] w-1/3 flex flex-col justify-around items-center'>
                <div class='flex flex-row h-4/6 w-5/6 items-stretch border border-black bg-white'>
                    <img src='img/<?php echo $hero->getClass(); ?>.png' class='w-2/3'>
                    <div class='border-l border-black flex flex-col items-center w-1/3 justify-around'>
                        <span class='font-semibold'><?php echo $hero->getName(); ?></span>
                        <div class='flex flex-col'>
                            <span class='mb-5'>Class : <?php echo $hero->getClass(); ?></span>
                            <span class='mb-5'>HP : <?php echo $hero->getHealthPoint(); ?> / 100</span>
                            <span class='mb-5'>Ability : <?php echo $hero->getSpecialAttack(); ?> / 100</span>
                        </div>
                    </div>
                </div>
                <form method='post' action='fight.php'>
                    <input type='hidden' name='id' value='<?php echo $hero->getId(); ?>'>
                    <button class='btn bg-blue-400 text-white w-min'>Choose</button>
                </form>
            </div>

        <?php } ?>
                    <!-- echo "<div class='hero'>" . $hero->getName() . " - " . $hero->getClass() . " - " . $hero->getHealthPoint() . "hp - ( special attack : " . $hero->getSpecialAttack() . " / 100 )<form method='post' action='fight.php'><input type='hidden' name='id' value='" . $hero->getId() . "'><button>Choose</button></form></div>"; -->
    </div>
<script src="js/index.js"></script>
</body>
</html>