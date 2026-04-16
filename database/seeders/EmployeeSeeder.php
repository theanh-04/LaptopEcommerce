<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Nguyễn Văn An',
                'email' => 'nguyenvanan@neonkinetic.vn',
                'phone' => '0901234567',
                'position' => 'Giám đốc',
                'department' => 'Ban Giám đốc',
                'hire_date' => Carbon::parse('2020-01-15'),
                'salary' => 50000000,
                'is_active' => true,
                'address' => '123 Đường Lê Lợi, Quận 1, TP.HCM'
            ],
            [
                'name' => 'Trần Thị Bình',
                'email' => 'tranthibinh@neonkinetic.vn',
                'phone' => '0902345678',
                'position' => 'Trưởng phòng Kỹ thuật',
                'department' => 'Kỹ thuật',
                'hire_date' => Carbon::parse('2020-03-20'),
                'salary' => 35000000,
                'is_active' => true,
                'address' => '456 Đường Nguyễn Huệ, Quận 1, TP.HCM'
            ],
            [
                'name' => 'Lê Minh Cường',
                'email' => 'leminhcuong@neonkinetic.vn',
                'phone' => '0903456789',
                'position' => 'Kỹ sư phần mềm',
                'department' => 'Kỹ thuật',
                'hire_date' => Carbon::parse('2021-06-10'),
                'salary' => 25000000,
                'is_active' => true,
                'address' => '789 Đường Trần Hưng Đạo, Quận 5, TP.HCM'
            ],
            [
                'name' => 'Phạm Thu Dung',
                'email' => 'phamthudung@neonkinetic.vn',
                'phone' => '0904567890',
                'position' => 'Trưởng phòng Vận hành',
                'department' => 'Vận hành',
                'hire_date' => Carbon::parse('2020-08-01'),
                'salary' => 30000000,
                'is_active' => true,
                'address' => '321 Đường Võ Văn Tần, Quận 3, TP.HCM'
            ],
            [
                'name' => 'Hoàng Văn Em',
                'email' => 'hoangvanem@neonkinetic.vn',
                'phone' => '0905678901',
                'position' => 'Nhân viên kho',
                'department' => 'Vận hành',
                'hire_date' => Carbon::parse('2022-01-15'),
                'salary' => 15000000,
                'is_active' => true,
                'address' => '654 Đường Cách Mạng Tháng 8, Quận 10, TP.HCM'
            ],
            [
                'name' => 'Vũ Thị Phương',
                'email' => 'vuthiphuong@neonkinetic.vn',
                'phone' => '0906789012',
                'position' => 'Trưởng phòng Hỗ trợ',
                'department' => 'Hỗ trợ',
                'hire_date' => Carbon::parse('2021-02-20'),
                'salary' => 28000000,
                'is_active' => true,
                'address' => '987 Đường Lý Thường Kiệt, Quận 11, TP.HCM'
            ],
            [
                'name' => 'Đỗ Minh Giang',
                'email' => 'dominh giang@neonkinetic.vn',
                'phone' => '0907890123',
                'position' => 'Nhân viên hỗ trợ',
                'department' => 'Hỗ trợ',
                'hire_date' => Carbon::parse('2022-05-10'),
                'salary' => 18000000,
                'is_active' => true,
                'address' => '147 Đường Hai Bà Trưng, Quận 1, TP.HCM'
            ],
            [
                'name' => 'Bùi Thị Hoa',
                'email' => 'buithihoa@neonkinetic.vn',
                'phone' => '0908901234',
                'position' => 'Kế toán trưởng',
                'department' => 'Kế toán',
                'hire_date' => Carbon::parse('2020-05-15'),
                'salary' => 32000000,
                'is_active' => true,
                'address' => '258 Đường Điện Biên Phủ, Quận 3, TP.HCM'
            ],
            [
                'name' => 'Ngô Văn Inh',
                'email' => 'ngovaninh@neonkinetic.vn',
                'phone' => '0909012345',
                'position' => 'Nhân viên kế toán',
                'department' => 'Kế toán',
                'hire_date' => Carbon::parse('2021-09-01'),
                'salary' => 20000000,
                'is_active' => true,
                'address' => '369 Đường Pasteur, Quận 1, TP.HCM'
            ],
            [
                'name' => 'Trương Thị Kim',
                'email' => 'truongthikim@neonkinetic.vn',
                'phone' => '0910123456',
                'position' => 'Trưởng phòng Nhân sự',
                'department' => 'Nhân sự',
                'hire_date' => Carbon::parse('2020-07-01'),
                'salary' => 33000000,
                'is_active' => true,
                'address' => '741 Đường Nam Kỳ Khởi Nghĩa, Quận 3, TP.HCM'
            ],
            [
                'name' => 'Lý Văn Long',
                'email' => 'lyvanlong@neonkinetic.vn',
                'phone' => '0911234567',
                'position' => 'Nhân viên nhân sự',
                'department' => 'Nhân sự',
                'hire_date' => Carbon::parse('2022-03-15'),
                'salary' => 17000000,
                'is_active' => true,
                'address' => '852 Đường Nguyễn Thị Minh Khai, Quận 1, TP.HCM'
            ],
            [
                'name' => 'Phan Thị Mai',
                'email' => 'phanthimai@neonkinetic.vn',
                'phone' => '0912345678',
                'position' => 'Nhân viên bán hàng',
                'department' => 'Kinh doanh',
                'hire_date' => Carbon::parse('2021-11-20'),
                'salary' => 16000000,
                'is_active' => true,
                'address' => '963 Đường Lê Văn Sỹ, Quận 3, TP.HCM'
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
