<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Confirmed => 'Confirmada',
            self::Processing => 'Em Processamento',
            self::Shipped => 'Enviada',
            self::Completed => 'Concluída',
            self::Cancelled => 'Cancelada',
        };
    }

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::Confirmed, self::Cancelled],
            self::Confirmed => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped],
            self::Shipped => [self::Completed],
            default => [],
        };
    }

    public function canTransitionTo(self $new): bool
    {
        return in_array($new, $this->allowedTransitions(), strict: true);
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'info',
            self::Processing => 'primary',
            self::Shipped => 'secondary',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
