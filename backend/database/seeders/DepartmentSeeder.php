<?php

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
        $employmentTypes = [
            'full_time',
            'part_time',
            'contract',
            'intern',
        ];

        /*
        |--------------------------------------------------------------------------
        | Load Names From Files
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
        | Chunk Insert
        |--------------------------------------------------------------------------
        */

        $chunkSize = 1000;

        $totalEmployees = 10000;

        for ($i = 0; $i < $totalEmployees; $i += $chunkSize) {

            $users = [];

            $employees = [];

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

                $jobTitle = $jobTitles[array_rand($jobTitles)];

                $salary = $this->generateSalary($jobTitle);

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

            foreach ($insertedUsers as $user) {

                $jobTitle = $jobTitles[array_rand($jobTitles)];

                $employees[] = [
                    'uuid' => Str::uuid(),

                    'user_id' => $user->id,

                    'employee_code' => 'EMP' . str_pad(
                        $user->id,
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
    }

    /**
     * Generate realistic salary ranges.
     */
    private function generateSalary(string $jobTitle): int
    {
        return match ($jobTitle) {

            'Software Engineer' =>
            rand(70000, 120000),

            'Senior Software Engineer' =>
            rand(120000, 200000),

            'HR Manager' =>
            rand(60000, 100000),

            'Finance Analyst' =>
            rand(65000, 110000),

            'Product Manager' =>
            rand(100000, 180000),

            'Marketing Executive' =>
            rand(50000, 90000),

            'QA Engineer' =>
            rand(60000, 100000),

            'DevOps Engineer' =>
            rand(90000, 170000),

            'UI UX Designer' =>
            rand(70000, 130000),

            'Business Analyst' =>
            rand(75000, 140000),

            default =>
            rand(50000, 100000),
        };
    }
}
