<?php
declare(strict_types=1);
abstract class CarDetail
{
    /**
     * @param bool $isBroken
     */
    public function __construct(
        protected bool $isBroken
    ) {
    }

    /**
     * @return bool
     */
    public function isBroken(): bool
    {
        return $this->isBroken;
    }
}

interface Paintable
{
    /**
     * @return bool
     */
    public function isPaintDamaged(): bool;
}

class Door extends CarDetail implements Paintable
{
    /**
     * @param bool $isBroken
     * @param bool $isPaintDamaged
     */
    public function __construct(
        bool $isBroken,
        private bool $isPaintDamaged
    ) {
        parent::__construct($isBroken);
    }

    /**
     * @return bool
     */
    public function isPaintDamaged(): bool
    {
        return $this->isPaintDamaged;
    }
}

class Tyre extends CarDetail
{
}

class Car
{
    /**
     * @param CarDetail[] $details
     */
    public function __construct(
        private array $details
    ) {
    }

    /**
     * @return bool
     */
    public function isBroken(): bool
    {
        foreach ($this->details as $detail) {
            if ($detail->isBroken()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isPaintingDamaged(): bool
    {
        foreach ($this->details as $detail) {
            if ($detail instanceof Paintable && $detail->isPaintDamaged()) {
                return true;
            }
        }
        return false;
    }
}

// Example usage
$car = new Car([
    new Door(isBroken: true,  isPaintDamaged: true),
    new Tyre(isBroken: false),
]);

var_dump($car->isBroken());         // bool(true)
var_dump($car->isPaintingDamaged()); // bool(true) because the door is paint-damaged
