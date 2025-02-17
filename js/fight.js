const heroId = document.getElementById('hero-id').value;
const monsterType = document.getElementById('monster-type').value;
let monsterHealth = document.getElementById('monster-health');
const fight = document.getElementById('fight');
// const fightContent = ["<p>" + fight.textContent + "</p>"];
const btnAttack = document.getElementById('btn-attack');
let currentDiv = document.getElementById('current-div');
let heroHealth = document.getElementById('hero-health');
const heroHealthPoint = document.getElementById('hero-health-point');
attack = false;
let specialAttack = document.getElementById('special-attack');
let specialAttackValue = document.getElementById('special-attack-value');
const btnSpecialAttack = document.getElementById('btn-special-attack');
const btnCure = document.getElementById('btn-cure');
monsterHealthValue = document.getElementById('monster-health-value');
let specialAttackReady = false;
let cureReady = false;
const audio = new Audio();
audio.src = "song/fight.mp3";

document.addEventListener("click", () => {
    audio.play();
});

audio.addEventListener("ended", () => {
    audio.currentTime = 0;
    audio.play();
});

// if (fight.textContent.trim().endsWith("a gagné la bataille !"))
// {
//     btnAttack.disabled = true;
// }

async function heroAttack()
{
    if  (!attack)
    {
    attack = true;
    btnAttack.classList.remove('bg-red-400');
    btnAttack.style.backgroundColor = 'gray';
    btnAttack.style.cursor = 'default';

    btnColorRemove();

    const response = await fetch('classes/FightManager.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'attack',
            heroId: heroId,
            monsterType: monsterType,
            monsterHealthPoint: parseInt(monsterHealth.style.width.replace('%', ''))
        })
    })

    const data = await response.json();

    addElement(data['textDamage'], 'blue');

    if (data['textWin'] != '')
    {
        addElement(data['textWin'], 'yellow');
    }

    monsterHealth.style.width = data['monsterHP'] + '%';

    setTimeout(() => {
        if (data['textWin'] == '')
        {
            monsterAttack();
            checkSpecialAttack();
        }
    }, 2000);

    console.log(data['specialAttack'])

    specialAttackValue.value = data['specialAttack'];

    specialAttack.style.width = data['specialAttack'] + '%';
    }
}

function addElement(text, color) {
    const div = document.createElement('div');
    div.classList.add('border', 'border-black', 'rounded-md', 'w-4/5', 'my-4', 'text-center');

    if (color == 'red') {
        div.classList.add('bg-red-400');
    } else if (color == 'yellow') {
        div.style.backgroundColor = 'yellow';
    } else {
        div.classList.add('bg-blue-400');
    }

    const span = document.createElement('span');
    const newContent = document.createTextNode(text);
    span.appendChild(newContent);
    span.classList.add('text-white');

    if (color == 'yellow') {
        span.classList.remove('text-white');
        span.classList.add('text-black');
    }
    div.appendChild(span);

    fight.insertBefore(div, currentDiv);

    currentDiv = div;
}

async function monsterAttack()
{
    const response = await fetch('classes/FightManager.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'monsterAttack',
            heroId: heroId,
            monsterType: monsterType,
            monsterHealthPoint: monsterHealth.style.width.replace('%', '')
        })
    })

    const data = await response.json();

    addElement(data['textDamage'], 'red');

    if (data['textWin'] == '')
    {
        attack = false;

        btnAttack.style.backgroundColor = '';
        btnAttack.style.cursor = 'pointer';
        btnAttack.style.backgroundColor = 'red';

        if (cureReady)
        {
            btnColorAdd();
        }
    }
    else
    {
        addElement(data['textWin'], 'yellow');

        attack = true;

        btnAttack.style.backgroundColor = 'gray';
        btnAttack.style.cursor = 'default';

        btnColorRemove();
        cureReady = false
    }

    const value = String(data['heroHP']) + '%'
    console.log(value)
    heroHealth.style.width = value;

    specialAttack.style.width = specialAttackValue.value + '%';

}

function checkSpecialAttack() {
    if (specialAttackValue.value == 100) {
        btnColorAdd()

        specialAttackReady = true
        cureReady = true
    } else {
        btnColorRemove()

        specialAttackReady = false
        cureReady = false
    }
}

if (specialAttackValue.value == 100) {
    btnColorAdd()

    specialAttackReady = true
    cureReady = true
}

if (heroHealthPoint.value == 0)
{
    btnColorRemove()
}

function btnColorRemove() {
    btnCure.style.backgroundColor = 'gray'
    btnCure.style.cursor = 'default'

    btnSpecialAttack.style.backgroundColor = 'gray'
    btnSpecialAttack.style.cursor = 'default'
    btnSpecialAttack.classList.remove('bg-blue-400')
}

function btnColorAdd() {
    btnCure.style.backgroundColor = 'rgb(28, 227, 28)'
    btnCure.style.cursor = 'pointer'

    btnSpecialAttack.style.backgroundColor = ''
    btnSpecialAttack.style.cursor = 'pointer'
    btnSpecialAttack.classList.add('bg-blue-500')
}


async function heroCure() {
    heroHealthNumber = parseInt(heroHealthPoint.value)
    if (cureReady) {
        if (heroHealthNumber < 50) 
        {
            heroHealthNumber += 50
        } 
        else 
        {
            heroHealthNumber = 100
        }

        cureReady = false

        btnColorRemove()

        const response = await fetch('classes/FightManager.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'cure',
                heroId: heroId,
                heroHP: parseInt(heroHealthPoint.value),
            })
        })

        const data = await response.json();

        console.log(data);

        heroHealthPoint.value = String(heroHealthNumber)
        heroHealth.style.width = data['heroHP'] + '%'

        specialAttackValue.value = 0

        specialAttack.style.width = '0%'
    }
}


async function heroSpecialAttack() {
    const response = await fetch('classes/FightManager.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'specialAttack',
            heroId: heroId,
            monsterHP: parseInt(monsterHealth.style.width.replace('%', ''))
        })
    })

    const data = await response.json();

    specialAttackValue.value = 0;

    specialAttack.style.width = '0%'

    monsterHealth.style.width = data['monsterHP'] + '%';

}
