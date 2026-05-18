<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = Country::pluck('id')->toArray();

        $departments = Department::pluck('id')->toArray();

        /*
        |--------------------------------------------------------------------------
        | Job Titles
        |--------------------------------------------------------------------------
        */

        $jobTitles = [

            'Software Engineer',
            'Senior Software Engineer',
            'Lead Software Engineer',
            'Principal Software Engineer',

            'Full Stack Developer',
            'Backend Developer',
            'Frontend Developer',

            'PHP Developer',
            'Laravel Developer',
            'Symfony Developer',
            'Node.js Developer',

            'React Developer',
            'Vue.js Developer',
            'Angular Developer',

            'Mobile App Developer',
            'Android Developer',
            'iOS Developer',
            'Flutter Developer',
            'React Native Developer',

            'DevOps Engineer',
            'Site Reliability Engineer',
            'Cloud Engineer',
            'AWS Engineer',
            'Azure Engineer',

            'System Administrator',
            'Network Engineer',
            'Database Administrator',

            'Data Engineer',
            'Data Analyst',
            'Data Scientist',
            'AI Engineer',
            'Machine Learning Engineer',

            'Cyber Security Analyst',
            'Security Engineer',

            'QA Engineer',
            'Automation Test Engineer',
            'Manual Test Engineer',
            'Performance Test Engineer',

            'UI UX Designer',
            'Graphic Designer',
            'Product Designer',

            'Business Analyst',
            'System Analyst',

            'Technical Support Engineer',
            'IT Support Specialist',

            'Technical Lead',
            'Engineering Manager',

            'Project Manager',
            'Product Manager',
            'Program Manager',
            'Scrum Master',

            'Solution Architect',
            'Enterprise Architect',

            'HR Executive',
            'HR Manager',
            'Talent Acquisition Specialist',
            'Recruiter',
            'Payroll Executive',

            'Finance Analyst',
            'Accountant',
            'Senior Accountant',
            'Finance Manager',
            'Investment Analyst',

            'Sales Executive',
            'Sales Manager',
            'Business Development Executive',
            'Business Development Manager',

            'Marketing Executive',
            'Digital Marketing Specialist',
            'SEO Specialist',
            'Content Writer',
            'Social Media Manager',

            'Customer Success Manager',

            'Operations Executive',
            'Operations Manager',

            'Procurement Officer',
            'Supply Chain Manager',

            'Legal Advisor',
            'Compliance Officer',

            'Admin Executive',
            'Office Manager',
        ];

        /*
        |--------------------------------------------------------------------------
        | Employment Types
        |--------------------------------------------------------------------------
        */

        $employmentTypes = [
            'full_time',
            'part_time',
            'contract',
            'intern',
        ];

        /*
        |--------------------------------------------------------------------------
        | Load Name Files
        |--------------------------------------------------------------------------
        */

        $firstNames = file(
            database_path('seeders/data/first_names.txt'),
            FILE_IGNORE_NEW_LINES
        );

        $lastNames = file(
            database_path('seeders/data/last_names.txt'),
            FILE_IGNORE_NEW_LINES
        );

        /*
        |--------------------------------------------------------------------------
        | Seeder Configuration
        |--------------------------------------------------------------------------
        */

        $chunkSize = 1000;

        $totalEmployees = 10000;

        /*
        |--------------------------------------------------------------------------
        | Start Seeding
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $totalEmployees; $i += $chunkSize) {

            $users = [];

            /*
            |--------------------------------------------------------------------------
            | Prepare Users
            |--------------------------------------------------------------------------
            */

            for ($j = 0; $j < $chunkSize; $j++) {

                $firstName = $firstNames[array_rand($firstNames)];

                $lastName = $lastNames[array_rand($lastNames)];

                $fullName = $firstName . ' ' . $lastName;

                $email = strtolower(
                    $firstName .
                    '.' .
                    $lastName .
                    rand(1000, 9999) .
                    '@example.com'
                );

                $users[] = [
                    'uuid' => Str::uuid(),

                    'name' => $fullName,

                    'email' => $email,

                    'password' => Hash::make('password'),

                    'role' => 'employee',

                    'is_active' => true,

                    'created_at' => now(),

                    'updated_at' => now(),
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Users
            |--------------------------------------------------------------------------
            */

            DB::table('users')->insert($users);

            /*
            |--------------------------------------------------------------------------
            | Fetch Inserted Users
            |--------------------------------------------------------------------------
            */

            $insertedUsers = DB::table('users')
                ->latest('id')
                ->limit($chunkSize)
                ->get()
                ->reverse()
                ->values();

            $employees = [];

            /*
            |--------------------------------------------------------------------------
            | Prepare Employees
            |--------------------------------------------------------------------------
            */

            foreach ($insertedUsers as $user) {

                $jobTitle = $jobTitles[array_rand($jobTitles)];

                $employees[] = [

                    'uuid' => Str::uuid(),

                    'user_id' => $user->id,

                    'employee_code' => 'EMP' . str_pad(
                        (string) $user->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),

                    'department_id' => $departments[array_rand($departments)],

                    'country_id' => $countries[array_rand($countries)],

                    'job_title' => $jobTitle,

                    'salary' => $this->generateSalary($jobTitle),

                    'employment_type' => $employmentTypes[array_rand($employmentTypes)],

                    'status' => 'active',

                    'joining_date' => now()
                        ->subDays(rand(1, 2000))
                        ->format('Y-m-d'),

                    'created_at' => now(),

                    'updated_at' => now(),
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Insert Employees
            |--------------------------------------------------------------------------
            */

            DB::table('employees')->insert($employees);

            $this->command->info(
                'Inserted ' . ($i + $chunkSize) . ' employees'
            );
        }

        $this->command->info('Employee seeding completed successfully.');
    }

    /**
     * Generate realistic salary ranges.
     */
    private function generateSalary(string $jobTitle): int
    {
        return match ($jobTitle) {

            'Software Engineer',
            'Backend Developer',
            'Frontend Developer',
            'PHP Developer',
            'Laravel Developer',
            'Symfony Developer',
            'Node.js Developer',
            'React Developer',
            'Vue.js Developer',
            'Angular Developer' =>
                rand(70000, 140000),

            'Senior Software Engineer',
            'Lead Software Engineer',
            'Technical Lead' =>
                rand(140000, 220000),

            'Principal Software Engineer',
            'Engineering Manager',
            'Solution Architect',
            'Enterprise Architect' =>
                rand(180000, 300000),

            'Full Stack Developer',
            'Mobile App Developer',
            'Android Developer',
            'iOS Developer',
            'Flutter Developer',
            'React Native Developer' =>
                rand(80000, 160000),

            'DevOps Engineer',
            'Site Reliability Engineer',
            'Cloud Engineer',
            'AWS Engineer',
            'Azure Engineer' =>
                rand(100000, 190000),

            'System Administrator',
            'Network Engineer',
            'Database Administrator',
            'Technical Support Engineer',
            'IT Support Specialist' =>
                rand(60000, 120000),

            'Data Engineer',
            'Data Analyst',
            'Data Scientist',
            'AI Engineer',
            'Machine Learning Engineer' =>
                rand(120000, 250000),

            'Cyber Security Analyst',
            'Security Engineer' =>
                rand(90000, 180000),

            'QA Engineer',
            'Automation Test Engineer',
            'Manual Test Engineer',
            'Performance Test Engineer' =>
                rand(60000, 130000),

            'UI UX Designer',
            'Graphic Designer',
            'Product Designer' =>
                rand(65000, 140000),

            'Business Analyst',
            'System Analyst',
            'Project Manager',
            'Product Manager',
            'Program Manager',
            'Scrum Master' =>
                rand(90000, 180000),

            'HR Executive',
            'Recruiter',
            'Talent Acquisition Specialist',
            'Payroll Executive' =>
                rand(50000, 100000),

            'HR Manager' =>
                rand(90000, 160000),

            'Finance Analyst',
            'Accountant',
            'Senior Accountant',
            'Finance Manager',
            'Investment Analyst' =>
                rand(70000, 170000),

            'Sales Executive',
            'Business Development Executive',
            'Marketing Executive',
            'Digital Marketing Specialist',
            'SEO Specialist',
            'Content Writer',
            'Social Media Manager' =>
                rand(50000, 120000),

            'Sales Manager',
            'Business Development Manager',
            'Customer Success Manager',
            'Operations Manager',
            'Procurement Officer',
            'Supply Chain Manager' =>
                rand(90000, 180000),

            'Legal Advisor',
            'Compliance Officer' =>
                rand(100000, 200000),

            'Admin Executive',
            'Office Manager',
            'Operations Executive' =>
                rand(50000, 110000),

            default =>
                rand(50000, 100000),
        };
    }
}