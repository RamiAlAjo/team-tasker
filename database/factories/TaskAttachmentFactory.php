<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskAttachment>
 */
class TaskAttachmentFactory extends Factory
{
    protected $fileTypes = [
        'image/jpeg' => ['jpg', 'jpeg'],
        'image/png' => ['png'],
        'image/gif' => ['gif'],
        'application/pdf' => ['pdf'],
        'application/msword' => ['doc'],
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
        'text/plain' => ['txt'],
        'application/zip' => ['zip'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileType = fake()->randomElement(array_keys($this->fileTypes));
        $extension = fake()->randomElement($this->fileTypes[$fileType]);
        $fileName = fake()->slug(3) . '.' . $extension;

        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'file_name' => $fileName,
            'file_path' => 'attachments/' . $fileName,
            'file_type' => $fileType,
            'file_size' => fake()->numberBetween(1024, 10485760), // 1KB to 10MB
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Create an attachment for a specific task.
     */
    public function forTask(Task $task): static
    {
        return $this->state(fn (array $attributes) => [
            'task_id' => $task->id,
        ]);
    }

    /**
     * Create an attachment by a specific user.
     */
    public function byUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create an image attachment.
     */
    public function image(): static
    {
        $imageTypes = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
        ];

        $fileType = fake()->randomElement(array_keys($imageTypes));
        $extension = fake()->randomElement($imageTypes[$fileType]);
        $fileName = fake()->slug(3) . '.' . $extension;

        return $this->state(fn (array $attributes) => [
            'file_name' => $fileName,
            'file_path' => 'attachments/' . $fileName,
            'file_type' => $fileType,
            'file_size' => fake()->numberBetween(51200, 5242880), // 50KB to 5MB
        ]);
    }

    /**
     * Create a document attachment.
     */
    public function document(): static
    {
        $docTypes = [
            'application/pdf' => ['pdf'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx'],
            'text/plain' => ['txt'],
        ];

        $fileType = fake()->randomElement(array_keys($docTypes));
        $extension = fake()->randomElement($docTypes[$fileType]);
        $fileName = fake()->slug(3) . '.' . $extension;

        return $this->state(fn (array $attributes) => [
            'file_name' => $fileName,
            'file_path' => 'attachments/' . $fileName,
            'file_type' => $fileType,
            'file_size' => fake()->numberBetween(1024, 2097152), // 1KB to 2MB
        ]);
    }

    /**
     * Create a large file attachment.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_size' => fake()->numberBetween(5242880, 10485760), // 5MB to 10MB
        ]);
    }
}