<?php

namespace Tests\Feature;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_a_profile(): void
    {
        $this->withExceptionHandling();

        $profile = Profile::factory()->create([
            'image' => 'images/profiles/test.png',
        ]);

        $response = $this->get(route('users.profile.show', $profile->user->id));

        $response->assertOk()
            ->assertJsonPath('name', $profile->name)
            ->assertJsonPath('surname', $profile->surname)
            ->assertJsonPath('patronymic', $profile->patronymic)
            ->assertJsonPath('age', $profile->age)
            ->assertJsonPath('image', url('storage/'.$profile->image))
            ->assertJsonPath('user.id', $profile->user->id)
            ->assertJsonPath('user.email', $profile->user->email)
            ->assertJsonStructure([
                'name',
                'surname',
                'patronymic',
                'age',
                'image',
                'user' => [
                    'id',
                    'email',
                ],
            ]);
    }

    public function test_getting_a_profile_if_profile_does_not_exist(): void
    {
        $this->withExceptionHandling();

        $profile = Profile::factory()->make();

        $response = $this->get(route('users.profile.show', $profile->user->id));

        $response->assertStatus(404);
    }

    public function test_destroy_a_profile(): void
    {
        $this->withExceptionHandling();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile.jpg');
        $image = Storage::put('images/profiles', $file);

        $profile = Profile::factory()->create([
            'image' => $image,
        ]);

        $response = $this->delete(route('users.profile.destroy', $profile->user->id));

        $response->assertOk()
            ->assertJson([
                'deleted' => true,
            ]);

        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
        ]);

        $this->assertEquals(Storage::fileMissing($profile->image), true);
    }

    public function test_the_name_attribute_is_required_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_name_attribute_is_string_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => 1111,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_surname_attribute_is_required_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_surname_attribute_is_string_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => 111,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_patronymic_attribute_is_required_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_patronymic_attribute_is_string_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => 111,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_age_attribute_is_required_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
        ]);

        $response->assertRedirect();
    }

    public function test_the_age_attribute_is_integer_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => 'test',
        ]);

        $response->assertRedirect();
    }

    public function test_the_image_attribute_is_file_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => 111,
        ]);

        $response->assertRedirect();
    }

    public function test_the_image_attribute_is_image_for_storing_a_profile(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => UploadedFile::fake()->create('text.txt', 1),
        ]);

        $response->assertRedirect();
    }

    public function test_successfully_storing_a_profile_without_an_image(): void
    {
        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'surname',
                    'patronymic',
                    'image',
                    'age',
                    'user' => [
                        'id',
                        'email',
                    ],
                ],
            ])
            ->assertJsonPath('data.name', $profile->name)
            ->assertJsonPath('data.surname', $profile->surname)
            ->assertJsonPath('data.patronymic', $profile->patronymic)
            ->assertJsonPath('data.age', $profile->age)
            ->assertJsonPath('data.image', null)
            ->assertJsonPath('data.user.id', $profile->user_id)
            ->assertJsonPath('data.user.email', $profile->user->email);

        $this->assertDatabaseHas('profiles', [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => null,
            'user_id' => $profile->user_id,
        ]);
    }

    public function test_successfully_storing_a_profile_with_an_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('profile.jpg');

        $profile = Profile::factory()->make();

        $response = $this->post(route('users.profile.store', $profile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => $file,
        ]);

        $createdProfile = Profile::query()
            ->latest()
            ->first();

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'surname',
                    'patronymic',
                    'image',
                    'age',
                    'user' => [
                        'id',
                        'email',
                    ],
                ],
            ])
            ->assertJsonPath('data.name', $profile->name)
            ->assertJsonPath('data.surname', $profile->surname)
            ->assertJsonPath('data.patronymic', $profile->patronymic)
            ->assertJsonPath('data.age', $profile->age)
            ->assertJsonPath('data.image', url('storage/'.$createdProfile->image))
            ->assertJsonPath('data.user.id', $profile->user_id)
            ->assertJsonPath('data.user.email', $profile->user->email);

        $this->assertDatabaseHas('profiles', [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'user_id' => $profile->user_id,
            'image' => $createdProfile->image,
        ]);

        Storage::assertExists($createdProfile->image);
    }

    public function test_the_name_attribute_is_required_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_name_attribute_is_string_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => 1111,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_surname_attribute_is_required_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_surname_attribute_is_string_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => 111,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_patronymic_attribute_is_required_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_patronymic_attribute_is_string_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => 111,
            'age' => $profile->age,
        ]);

        $response->assertRedirect();
    }

    public function test_the_age_attribute_is_required_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
        ]);

        $response->assertRedirect();
    }

    public function test_the_age_attribute_is_integer_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => 'test',
        ]);

        $response->assertRedirect();
    }

    public function test_the_image_attribute_is_file_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => 111,
        ]);

        $response->assertRedirect();
    }

    public function test_the_image_attribute_is_image_for_updating_a_profile(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => UploadedFile::fake()->create('text.txt', 1),
        ]);

        $response->assertRedirect();
    }

    public function test_successfully_updating_a_profile_without_an_image(): void
    {
        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $response->assertOk()
            ->assertJson([
                'updated' => true,
            ]);

        $this->assertDatabaseHas('profiles', [
            'id' => $oldProfile->id,
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $this->assertDatabaseMissing('profiles', [
            'name' => $oldProfile->name,
            'surname' => $oldProfile->surname,
            'patronymic' => $oldProfile->patronymic,
            'age' => $oldProfile->age,
        ]);
    }

    public function test_successfully_updating_a_profile_with_an_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.png');

        $profile = Profile::factory()->make();
        $oldProfile = Profile::factory()->create();

        $response = $this->patch(route('users.profile.update', $oldProfile->user->id), [
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
            'image' => $file,
        ]);

        $response->assertOk()
            ->assertJson([
                'updated' => true,
            ]);

        $this->assertDatabaseHas('profiles', [
            'id' => $oldProfile->id,
            'name' => $profile->name,
            'surname' => $profile->surname,
            'patronymic' => $profile->patronymic,
            'age' => $profile->age,
        ]);

        $this->assertDatabaseMissing('profiles', [
            'name' => $oldProfile->name,
            'surname' => $oldProfile->surname,
            'patronymic' => $oldProfile->patronymic,
            'age' => $oldProfile->age,
        ]);

        $updatedProfile = $oldProfile->refresh();

        Storage::assertExists($updatedProfile->image);
    }
}
