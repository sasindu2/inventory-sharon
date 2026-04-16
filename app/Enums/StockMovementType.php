<?php

namespace App\Enums;

enum StockMovementType: string
{
    case Increase = 'increase';
    case Decrease = 'decrease';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function signedQuantity(int $quantity): int
    {
        return $this === self::Increase ? $quantity : -$quantity;
    }

    public function badgeClass(): string
    {
        return $this === self::Increase ? 'success' : 'danger';
    }
}
