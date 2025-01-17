Please take a look at the code below. We've got a class FightService, which implements a logic of a fight between two heroes. After the fight one of the hero may lose some health points.

Please implement a test for FightService::fight() method.

Feel free to refactor any code if you think it's needed.

<?php

use PHPUnit\Framework\TestCase;

interface HeroInterface
{
    public function getAttack(): int;

    public function getDefence(): int;

    public function getHealthPoints(): int;

    public function setHealthPoints(int $healthPoints);
}

class DamageCalculator
{
    const DAMAGE_RAND_FACTOR = 0.2;

    public static function calculateDamage(HeroInterface $attacker, HeroInterface $defender): int
    {
        $damage = 0;

        if ($attacker->getAttack() > $defender->getDefence()) {
            $baseDamage = $attacker->getAttack() - $defender->getDefence();

            $factor = $baseDamage * self::DAMAGE_RAND_FACTOR;

            $minDamage = $baseDamage - $factor;
            $maxDamage = $baseDamage + $factor;

            $damage = mt_rand($minDamage, $maxDamage);
        }

        return $damage;
    }
}

class FightService
{
    public function fight(HeroInterface $attacker, HeroInterface $defender)
    {
        $damage = DamageCalculator::calculateDamage($attacker, $defender);

        $defender->setHealthPoints($defender->getHealthPoints() - $damage);
    }
}

class FightServiceTest extends TestCase {

    public function testFight()
    {
        // implement the test
    }
}