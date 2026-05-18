<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\CountrySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EmployeeSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_seeder_creates_ten_thousand_employees_and_can_be_rerun(): void
    {
        $this->seed([
            CountrySeeder::class,
            DepartmentSeeder::class,
        ]);

        $startedAt = microtime(true);

        $this->seed(EmployeeSeeder::class);

        $firstRunSeconds = microtime(true) - $startedAt;

        $this->assertSame(10000, DB::table('employees')->count());
        $this->assertSame(10000, DB::table('users')->where('email', 'like', 'seed.employee.%@example.com')->count());
        $this->assertLessThan(20, $firstRunSeconds, 'EmployeeSeeder should remain practical for regular local reruns.');

        $this->seed(EmployeeSeeder::class);

        $this->assertSame(10000, DB::table('employees')->count());
        $this->assertSame(10000, DB::table('users')->where('email', 'like', 'seed.employee.%@example.com')->count());
        $this->assertDatabaseHas('employees', [
            'employee_code' => 'SEED-EMP000001',
        ]);
    }
}
