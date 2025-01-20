<?php

use PHPUnit\Framework\TestCase;

class FightServiceTest extends TestCase
{
    public function testDeterministicFight()
    {
        $attacker = $this->createMock(HeroInterface::class);
        $attacker->method('getAttack')->willReturn(20);

        $defender = $this->createMock(HeroInterface::class);
        $defender->method('getDefence')->willReturn(10);
        $defender->method('getHealthPoints')->willReturn(100);

        $rngMock = $this->createMock(RandomNumberGeneratorInterface::class);
        $rngMock->method('rand')->willReturn(10);

        $damageCalculator = new DamageCalculator($rngMock);

        $fightService = new FightService($damageCalculator);

        $defender->expects($this->once())
            ->method('setHealthPoints')
            ->with($this->equalTo(90));

        $fightService->fight($attacker, $defender);
    }
}