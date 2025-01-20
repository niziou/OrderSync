<?php
declare(strict_types=1);

interface HeroInterface
{
    /**
     * @return int
     */
    public function getAttack(): int;

    /**
     * @return int
     */
    public function getDefence(): int;

    /**
     * @return int
     */
    public function getHealthPoints(): int;

    /**
     * @param int $healthPoints
     * @return void
     */
    public function setHealthPoints(int $healthPoints): void;
}

interface RandomNumberGeneratorInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public function rand(int $min, int $max): int;
}

class PhpRandomNumberGenerator implements RandomNumberGeneratorInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public function rand(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }
}

/**
 *
 */
class DamageCalculator
{
    public const DAMAGE_RAND_FACTOR = 0.2;

    /**
     * @param RandomNumberGeneratorInterface $rng
     */
    public function __construct(
        private RandomNumberGeneratorInterface $rng
    ) {
    }

    /**
     * @param HeroInterface $attacker
     * @param HeroInterface $defender
     * @return int
     */
    public function calculateDamage(HeroInterface $attacker, HeroInterface $defender): int
    {
        $damage = 0;
        if ($attacker->getAttack() > $defender->getDefence()) {
            $baseDamage = $attacker->getAttack() - $defender->getDefence();
            $factor = $baseDamage * self::DAMAGE_RAND_FACTOR;

            $minDamage = (int) round($baseDamage - $factor);
            $maxDamage = (int) round($baseDamage + $factor);

            $damage = $this->rng->rand($minDamage, $maxDamage);
        }
        return $damage;
    }
}

/**
 *
 */
class FightService
{
    /**
     * @param DamageCalculator $damageCalculator
     */
    public function __construct(
        private DamageCalculator $damageCalculator
    ) {
    }

    /**
     * @param HeroInterface $attacker
     * @param HeroInterface $defender
     * @return void
     */
    public function fight(HeroInterface $attacker, HeroInterface $defender)
    {
        $damage = $this->damageCalculator->calculateDamage($attacker, $defender);
        $defender->setHealthPoints($defender->getHealthPoints() - $damage);
    }
}