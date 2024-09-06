<?php

namespace App\State\Task\Traits;

use App\Enums\TaskStatusEnum;
use App\State\Task\CanceledState;
use App\State\Task\Contracts\TaskStatus;
use App\State\Task\CreatedState;
use App\State\Task\FinishedState;
use App\State\Task\ProcessingState;

trait HasState
{
    protected ?TaskStatus $state;

    protected function getCurrentState(): ?TaskStatus
    {
        $statusState = [
            TaskStatusEnum::CREATED->value => CreatedState::class,
            TaskStatusEnum::PROCESSING->value => ProcessingState::class,
            TaskStatusEnum::FINISHED->value => FinishedState::class,
            TaskStatusEnum::CANCELED->value => CanceledState::class,
        ];

        if (isset($statusState[$this->status])) {
            return new $statusState[$this->status]();
        }

        return null;
    }


    public function setState(TaskStatus $state): void
    {
        $this->state = $state;
    }

    public function getState(): ?TaskStatus
    {
        return $this->state;
    }

    public function handleState(): void
    {
        if ($this->state) $this->state->handle($this);
    }

    public function toNextStatus(): void
    {
        if ($this->state) $this->state->toNext($this);
    }

    public function toBackStatus(): void
    {
        if ($this->state) $this->state->toBack($this);
    }
}
