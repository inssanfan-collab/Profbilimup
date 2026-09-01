<?php

namespace Database\Factories;

use App\Enums\AssignmentStatus;
use App\Enums\FinalOutcome;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourseAssignment>
 */
class CourseAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'listener_id' => User::factory(),
            'assigned_by' => User::factory()->admin(),
            'deadline' => null,
            'status' => AssignmentStatus::Assigned,
            'final_outcome' => FinalOutcome::Pending,
            'assigned_at' => now(),
        ];
    }
}
