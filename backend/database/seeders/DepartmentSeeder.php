<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Engineering',
                'description' => 'Software development team',
            ],
            [
                'name' => 'Human Resources',
                'description' => 'HR and recruitment team',
            ],
            [
                'name' => 'Finance',
                'description' => 'Finance and accounting',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Marketing department',
            ],
            [
                'name' => 'Operations',
                'description' => 'Operations management',
            ],
            [
                'name' => 'Information Technology',
                'description' => 'IT infrastructure and support',
            ],
            [
                'name' => 'Customer Support',
                'description' => 'Customer service and support',
            ],
            [
                'name' => 'Sales',
                'description' => 'Sales and business growth',
            ],
            [
                'name' => 'Business Development',
                'description' => 'Partnerships and new opportunities',
            ],
            [
                'name' => 'Research and Development',
                'description' => 'Innovation and product research',
            ],
            [
                'name' => 'Administration',
                'description' => 'Administrative operations',
            ],
            [
                'name' => 'Legal',
                'description' => 'Legal and compliance matters',
            ],
            [
                'name' => 'Compliance',
                'description' => 'Regulatory and policy compliance',
            ],
            [
                'name' => 'Procurement',
                'description' => 'Purchasing and vendor management',
            ],
            [
                'name' => 'Quality Assurance',
                'description' => 'Testing and quality management',
            ],
            [
                'name' => 'Product Management',
                'description' => 'Product planning and execution',
            ],
            [
                'name' => 'Design',
                'description' => 'UI, UX, and graphic design',
            ],
            [
                'name' => 'Security',
                'description' => 'Cybersecurity and risk management',
            ],
            [
                'name' => 'DevOps',
                'description' => 'Deployment and infrastructure automation',
            ],
            [
                'name' => 'Data Analytics',
                'description' => 'Data analysis and reporting',
            ],
            [
                'name' => 'Public Relations',
                'description' => 'Media and public communications',
            ],
            [
                'name' => 'Training',
                'description' => 'Employee learning and development',
            ],
            [
                'name' => 'Customer Success',
                'description' => 'Client relationship management',
            ],
            [
                'name' => 'Supply Chain',
                'description' => 'Supply chain and logistics',
            ],
            [
                'name' => 'Internal Audit',
                'description' => 'Internal auditing and controls',
            ],
        ];

        foreach ($departments as &$department) {

            $department['uuid'] = Str::uuid();

            $department['created_at'] = now();

            $department['updated_at'] = now();
        }

        Department::insert($departments);
    }
}