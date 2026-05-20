<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderStatusTransitionTest extends TestCase
{
    public function test_pending_can_transition_to_confirmed(): void
    {
        $this->assertTrue(OrderStatus::Pending->canTransitionTo(OrderStatus::Confirmed));
    }

    public function test_pending_can_transition_to_cancelled(): void
    {
        $this->assertTrue(OrderStatus::Pending->canTransitionTo(OrderStatus::Cancelled));
    }

    public function test_confirmed_can_transition_to_processing(): void
    {
        $this->assertTrue(OrderStatus::Confirmed->canTransitionTo(OrderStatus::Processing));
    }

    public function test_confirmed_can_transition_to_cancelled(): void
    {
        $this->assertTrue(OrderStatus::Confirmed->canTransitionTo(OrderStatus::Cancelled));
    }

    public function test_processing_can_transition_to_shipped(): void
    {
        $this->assertTrue(OrderStatus::Processing->canTransitionTo(OrderStatus::Shipped));
    }

    public function test_shipped_can_transition_to_completed(): void
    {
        $this->assertTrue(OrderStatus::Shipped->canTransitionTo(OrderStatus::Completed));
    }

    public function test_completed_cannot_transition_to_any_status(): void
    {
        foreach (OrderStatus::cases() as $status) {
            $this->assertFalse(OrderStatus::Completed->canTransitionTo($status));
        }
    }

    public function test_cancelled_cannot_transition_to_any_status(): void
    {
        foreach (OrderStatus::cases() as $status) {
            $this->assertFalse(OrderStatus::Cancelled->canTransitionTo($status));
        }
    }

    public function test_pending_cannot_transition_to_processing(): void
    {
        $this->assertFalse(OrderStatus::Pending->canTransitionTo(OrderStatus::Processing));
    }

    public function test_pending_cannot_transition_to_shipped(): void
    {
        $this->assertFalse(OrderStatus::Pending->canTransitionTo(OrderStatus::Shipped));
    }

    public function test_shipped_cannot_transition_backwards(): void
    {
        $this->assertFalse(OrderStatus::Shipped->canTransitionTo(OrderStatus::Pending));
        $this->assertFalse(OrderStatus::Shipped->canTransitionTo(OrderStatus::Confirmed));
        $this->assertFalse(OrderStatus::Shipped->canTransitionTo(OrderStatus::Processing));
    }
}
