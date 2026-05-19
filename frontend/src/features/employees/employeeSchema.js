import { z } from 'zod'

export const employeeSchema = z.object({
  name: z.string().min(2, 'Name is required').max(255),
  email: z.string().email('Enter a valid email address').max(255),
  employee_code: z.string().min(1, 'Employee code is required').max(50),
  department_id: z.coerce.number().int().positive('Department is required'),
  country_id: z.coerce.number().int().positive('Country is required'),
  job_title: z.string().min(2, 'Job title is required').max(150),
  salary: z.coerce.number().min(0, 'Salary must be zero or greater'),
  employment_type: z.enum(['full_time', 'part_time', 'contract', 'intern']),
  joining_date: z.string().min(1, 'Joining date is required'),
  status: z.enum(['active', 'inactive']),
})

export const emptyEmployeeValues = {
  name: '',
  email: '',
  employee_code: '',
  department_id: '',
  country_id: '',
  job_title: '',
  salary: '',
  employment_type: 'full_time',
  joining_date: '',
  status: 'active',
}

export function employeeToFormValues(employee) {
  if (!employee) {
    return emptyEmployeeValues
  }

  return {
    name: employee.name ?? employee.user?.name ?? '',
    email: employee.email ?? employee.user?.email ?? '',
    employee_code: employee.employee_code ?? '',
    department_id: employee.department?.id ?? '',
    country_id: employee.country?.id ?? '',
    job_title: employee.job_title ?? '',
    salary: employee.salary ?? '',
    employment_type: employee.employment_type ?? 'full_time',
    joining_date: employee.joining_date ?? '',
    status: employee.status ?? 'active',
  }
}
