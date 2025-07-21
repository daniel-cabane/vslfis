<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Report;
use Faker\Factory as Faker;

class StudentsAndReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $levels = ['6e', '5e', '4e', '3e'];
        $statuses = ['blue', 'black', 'red'];

        for ($i = 0; $i < 100; $i++) {
            // Generate random tagNb between 300000000 and 500000000
            $tagNb = rand(300000000, 500000000);
            // Choose a random level and section
            $level = $levels[array_rand($levels)];
            $section = chr(rand(65, 69)); // A to E
            $status = $statuses[array_rand($statuses)];

            Student::create([
                'lastName' => $faker->lastName,
                'firstName' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'tagNb' => $tagNb,
                'photo' => null,
                'level' => $level,
                'section' => $section,
                'status' => $status,
                'details' => null,
            ]);
        }

        $categories = ['phone', 'computer', 'behaviour', 'badge defect'];

        for ($i = 0; $i < 200; $i++) {
            // Reporter ID between 1 and 6
            $reporterId = rand(1, 6);
            // Randomly decide if location and comment will be null or a small sentence/paragraph
            $location = $faker->boolean(50) ? null : $faker->sentence(3); // 50% chance for null
            $comment = $faker->boolean(50) ? null : $faker->paragraph; // 50% chance for null

            $report = Report::create([
                'reporter_id' => $reporterId,
                'category' => $categories[array_rand($categories)],
                'location' => $location,
                'comment' => $comment,
                'finalized' => $faker->boolean(80), // 80% chance to be finalized
                'filed_by' => null, // Always null as per requirements
            ]);

            $studentIds = Student::inRandomOrder()->take(rand(0, 5))->pluck('id');
            $report->students()->attach($studentIds);
        }
    }
}
