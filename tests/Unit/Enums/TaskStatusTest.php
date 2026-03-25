<?php

namespace Tests\Unit\Enums;

use App\Enums\TaskStatus;
use PHPUnit\Framework\TestCase;

class TaskStatusTest extends TestCase
{
    public function test_allowed_transitions_are_correct(): void
    {
        $this->assertEquals([TaskStatus::InProgress], TaskStatus::Backlog->allowedTransitions());

        $this->assertContains(TaskStatus::Backlog, TaskStatus::InProgress->allowedTransitions());
        $this->assertContains(TaskStatus::Review, TaskStatus::InProgress->allowedTransitions());
        $this->assertContains(TaskStatus::Testing, TaskStatus::InProgress->allowedTransitions());

        $this->assertContains(TaskStatus::Done, TaskStatus::Review->allowedTransitions());
        $this->assertContains(TaskStatus::Done, TaskStatus::Testing->allowedTransitions());
    }

    public function test_done_is_terminal(): void
    {
        $this->assertTrue(TaskStatus::Done->isTerminal());
        $this->assertFalse(TaskStatus::Backlog->isTerminal());
        $this->assertFalse(TaskStatus::InProgress->isTerminal());
        $this->assertFalse(TaskStatus::Review->isTerminal());
        $this->assertFalse(TaskStatus::Testing->isTerminal());
    }

    public function test_all_statuses_have_label_and_color(): void
    {
        foreach (TaskStatus::cases() as $status) {
            $this->assertNotEmpty($status->label());
            $this->assertStringStartsWith('#', $status->color());
        }
    }
}
