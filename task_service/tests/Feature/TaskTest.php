<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\State\Task\Contracts\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
Use Illuminate\Support\Str;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_tasks(): void
    {
        $this->withExceptionHandling();

        $user = User::factory()->create();

        $tasks = Task::factory()
            ->count(3)
            ->for($user)
            ->create();

        $response = $this->get(route('users.tasks.index', $user->id));

        $response
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'title',
                    'content',
                    'status',
                    'tags',
                    'started_at',
                    'finished_at',
                    'created_at',
                ],
            ]);
    }

    public function test_getting_task(): void
    {
        $this->withExceptionHandling();

        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $task = $task->refresh();

        $response = $this->get(route('users.tasks.show', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('id', $task->id)
            ->assertJsonPath('title', $task->title)
            ->assertJsonPath('content', $task->content)
            ->assertJsonPath('status', $task->status)
            ->assertJsonPath('tags', $task->tags)
            ->assertJsonPath('started_at', $task->started_at ? $task->started_at->toISOString(): $task->started_at)
            ->assertJsonPath('finished_at', $task->finished_at ? $task->finished_at->toISOString(): $task->finished_at)
            ->assertJsonPath('created_at', $task->created_at ? $task->created_at->toISOString(): $task->created_at)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'status',
                'tags',
                'started_at',
                'finished_at',
                'created_at',
            ]);
    }

    public function test_title_attribute_is_required_for_storing_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make();

        $response = $this->post(route('users.tasks.store', $user->id), [
            'content' => $task->content,
        ]);

        $response->assertStatus(302);
    }

    public function test_title_attribute_is_string_for_storing_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make();

        $response = $this->post(route('users.tasks.store', $user->id), [
            'title' => 123,
            'content' => $task->content,
        ]);

        $response->assertStatus(302);
    }

    public function test_title_string_is_max_255_symbols_for_storing_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make();

        $response = $this->post(route('users.tasks.store', $user->id), [
            'title' => Str::random(256),
            'content' => $task->content,
        ]);

        $response->assertStatus(302);
    }

    public function test_content_attribute_is_required_for_storing_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make();

        $response = $this->post(route('users.tasks.store', $user->id), [
            'title' => $task->title,
        ]);

        $response->assertStatus(302);
    }

    public function test_content_attribute_is_string_for_storing_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make();

        $response = $this->post(route('users.tasks.store', $user->id), [
            'title' => $task->title,
            'content' => 11111,
        ]);

        $response->assertStatus(302);
    }

    public function test_tags_attribute_is_list_for_storing_a_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make();

        $response = $this->post(route('users.tasks.store', $user->id), [
            'title' => $task->title,
            'content' => $task->content,
            'tags' => [
                'test' => 'test',
            ],
        ]);

        $response->assertStatus(302);
    }

    public function test_storing_a_task_without_tags(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make([
            'status' => TaskStatusEnum::CREATED->value,
        ]);

        $task->tags = null;

        $response = $this->post(
            route('users.tasks.store', $user->id),
            $task->toArray()
        );

        $response->assertOk()
            ->assertJsonPath('title', $task->title)
            ->assertJsonPath('content', $task->content)
            ->assertJsonPath('status', $task->status)
            ->assertJsonPath('tags', $task->tags)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'status',
                'tags',
                'started_at',
                'finished_at',
                'created_at',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'content' => $task->content,
            'status' => $task->status,
            'tags' => null,
        ]);
    }

    public function test_storing_a_task_with_tags(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->make([
            'status' => TaskStatusEnum::CREATED->value,
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->post(
            route('users.tasks.store', $user->id),
            $task->toArray()
        );

        $response->assertOk()
            ->assertJsonPath('title', $task->title)
            ->assertJsonPath('content', $task->content)
            ->assertJsonPath('status', $task->status)
            ->assertJsonPath('tags', $task->tags)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'status',
                'tags',
                'started_at',
                'finished_at',
                'created_at',
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'content' => $task->content,
            'status' => $task->status,
            'tags' => $task->tags ? json_encode($task->tags) : null,
        ]);
    }

    public function test_updating_a_task(): void
    {
        $this->withExceptionHandling();

        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertOk()
            ->assertJson([
                'updated' => true,
            ]);

        $this->assertDatabaseMissing('tasks', [
            'title' => $task->tiltle,
            'content' => $task->content,
            'status' => $task->status,
            'tags' => json_encode($task->tags),
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => json_encode($updatedTask->tags),
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);
    }

    public function test_title_attribute_is_required_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_title_attribute_is_string_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => 123,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_title_attribute_is_max_255_symbols_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => Str::random(256),
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_content_attribute_is_required_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_content_attribute_is_string_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => 11111,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_started_at_attribute_is_date_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => 11111,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_finished_at_attribute_is_date_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => 11111,
        ]);

        $response->assertStatus(302);
    }

    public function test_finished_at_attribute_is_more_than_started_at_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'finished_at' => now()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_status_attribute_is_required_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_status_attribute_is_enum_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => $updatedTask->tags,
            'status' => 1111,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_tags_attribute_is_list_for_updating_a_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create()
            ->first();

        $updatedTask = Task::factory()->make([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => now()->addDay()->format('Y-m-d H:i:s'),
            'tags' => [
                'tag1',
                'tag2',
            ],
        ]);

        $response = $this->patch(route('users.tasks.update', [
            'user' => $user->id,
            'task' => $task->id,
        ]), [
            'title' => $updatedTask->title,
            'content' => $updatedTask->content,
            'tags' => [
                'tag' => 'tag'
            ],
            'status' => $updatedTask->status,
            'started_at' => $updatedTask->started_at,
            'finished_at' => $updatedTask->finished_at,
        ]);

        $response->assertStatus(302);
    }

    public function test_update_a_task_to_next_status_from_created_status_to_processing_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::CREATED->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.next', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::PROCESSING->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::PROCESSING->value,
        ]);
    }

    public function test_update_a_task_to_next_status_from_processing_status_to_finished_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::PROCESSING->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.next', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::FINISHED->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::FINISHED->value,
        ]);
    }

    public function test_update_a_task_to_next_status_from_finished_status_to_finished_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::FINISHED->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.next', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::FINISHED->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::FINISHED->value,
        ]);
    }

    public function test_update_a_task_to_next_status_from_canceled_status_to_canceled_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::CANCELED->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.next', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::CANCELED->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::CANCELED->value,
        ]);
    }

    public function test_update_a_task_to_back_status_from_created_status_to_created_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::CREATED->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.back', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::CREATED->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::CREATED->value,
        ]);
    }

    public function test_update_a_task_to_back_status_from_processing_status_to_created_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::PROCESSING->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.back', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::CREATED->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::CREATED->value,
        ]);
    }

    public function test_update_a_task_to_back_status_from_finished_status_to_processing_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::FINISHED->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.back', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::PROCESSING->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::PROCESSING->value,
        ]);
    }

    public function test_update_a_task_to_back_status_from_canceled_status_to_created_status(): void
    {
        $user = User::factory()->create();

        $task = Task::factory()
            ->count(1)
            ->for($user)
            ->create([
                'status' => TaskStatusEnum::CANCELED->value,
            ])
            ->first();

        $response = $this->patch(route('users.tasks.update.status.back', [
            'user' => $user->id,
            'task' => $task->id,
        ]));

        $response->assertOk()
            ->assertJsonPath('updated', true)
            ->assertJsonPath('new_status',TaskStatusEnum::CREATED->value)
            ->assertJsonStructure([
                'updated',
                'new_status',
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatusEnum::CREATED->value,
        ]);
    }
}
