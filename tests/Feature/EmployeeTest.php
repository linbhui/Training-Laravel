<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Team;
use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    public function test_render_employee_management_screen(): void
    {
        $user = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('employee.index'));

        $response->assertOk();
        $response->assertViewHas('employees');
    }

    public function test_sorted_employee_table(): void
    {
        $user = Employee::factory()->create();

        foreach (['name', 'email'] as $by) {
            foreach (['asc', 'desc'] as $sort) {
                $response = $this->actingAs($user, 'employees')
                    ->get(route('employee.index', [
                        'sortBy' => $by,
                        'sort' => $sort
                    ]));

                $response->assertOk();
                $response->assertViewHas('employees', function ($employees) use ($sort, $by) {
                    $list = $employees->pluck($by)->toArray();

                    $sorted = $list;
                    $sort === 'asc' ? sort($sorted) : rsort($sorted);

                    return $list === $sorted;
                });
            }
        }
    }

    public function test_render_add_employee_screen() : void
    {
        $user = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('employee.add'));

        $response->assertOk();
    }

    public function test_add_new_employee_success(): void
    {
        Storage::fake('public');

        $user = Employee::factory()->create();
        $team = Team::factory()->create();
        $faker = Factory::create();

        $email = $faker->email;
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $password = $faker->password;
        $gender = rand(1, 2);
        $birthday = $faker->date('Y-m-d', '2000-01-01');
        $address = $faker->address;
        $salary = rand(1000000, 5000000);
        $position = rand(1, 5);
        $status = rand(1, 2);
        $typeOfWork = rand(1, 4);
        $fakeImage = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('employee.add_confirm'), [
                'team_id' => $team->id,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => $password,
                'gender' => $gender,
                'birthday' => $birthday,
                'address' => $address,
                'avatar' => $fakeImage,
                'salary' => $salary,
                'position' => $position,
                'status' => $status,
                'type_of_work' => $typeOfWork
            ]);

        Storage::disk('public')->assertExists('avatars/' . $fakeImage->hashName());

        $this->assertDatabaseHas('employees', [
            'team_id' => $team->id,
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender,
            'birthday' => $birthday,
            'address' => $address,
            'salary' => $salary,
            'position' => $position,
            'status' => $status,
            'type_of_work' => $typeOfWork,
            'ins_id' => $user->id
        ]);

        $employee = Employee::where('email', $email)->firstOrFail();
        $this->assertTrue(Hash::check($password, $employee->password));

        $this->assertStringStartsWith('avatars/', $employee->avatar);
        Storage::disk('public')->assertExists($employee->avatar);

        $response->assertRedirectToRoute('employee.index');
        $response->assertSessionHas('notification', 'New employee added successfully!');
    }

    public function test_add_new_employee_fail(): void
    {
        Storage::fake('public');

        $user = Employee::factory()->create();
        $team = Team::factory()->create();

        $fakeFile = UploadedFile::fake()->create('not-an-image.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('employee.add_confirm'), [
                'team_id' => $team->id,
                'first_name' => '',
                'last_name' => 'Tester',
                'password' => 'secret123',
                'gender' => 3,
                'birthday' => '3025-01-01',
                'address' => '123 Fake Street',
                'avatar' => $fakeFile,
                'salary' => -1000,
                'position' => 10,
                'status' => 5,
                'type_of_work' => 99,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'email',
            'first_name',
            'gender',
            'birthday',
            'avatar',
            'salary',
            'position',
            'status',
            'type_of_work',
        ]);

        $this->assertDatabaseMissing('employees', [
            'last_name' => 'Tester',
        ]);
    }

    public function test_render_edit_employee_screen() : void
    {
        $user = Employee::factory()->create();
        $employee = Team::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('employee.edit', [$employee->id]));

        $response->assertOk();
        $response->assertSee($employee->first_name);
    }

    public function test_edit_employee_success(): void
    {
        Storage::fake('public');

        $user = Employee::factory()->create();
        $employee = Employee::factory()->create();
        $team = Team::factory()->create();
        $new = Factory::create();

        $email = $new->email;
        $firstName = $new->firstName;
        $lastName = $new->lastName;
        $password = $new->password;
        $gender = rand(1, 2);
        $birthday = $new->date('Y-m-d', '2000-01-01');
        $address = $new->address;
        $salary = rand(1000000, 5000000);
        $position = rand(1, 5);
        $status = rand(1, 2);
        $typeOfWork = rand(1, 4);
        $fakeImage = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('employee.edit_confirm'), [
                'id' => $employee->id,
                'team_id' => $team->id,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => $password,
                'gender' => $gender,
                'birthday' => $birthday,
                'address' => $address,
                'avatar' => $fakeImage,
                'salary' => $salary,
                'position' => $position,
                'status' => $status,
                'type_of_work' => $typeOfWork
            ]);

        $employee->refresh();

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'team_id' => $team->id,
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender,
            'birthday' => $birthday,
            'address' => $address,
            'salary' => $salary,
            'position' => $position,
            'status' => $status,
            'type_of_work' => $typeOfWork,
            'upd_id' => $user->id
        ]);

        $this->assertTrue(Hash::check($password, $employee->password));
        $this->assertStringStartsWith('avatars/', $employee->avatar);
        Storage::disk('public')->assertExists($employee->avatar);

        $response->assertRedirectToRoute('employee.index');
        $response->assertSessionHas('notification', 'Employee updated successfully!');

    }

    public function test_edit_employee_fail(): void
    {
        $user = Employee::factory()->create();
        $employee = Employee::factory()->create();
        $existingEmailOwner = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->from(route('employee.edit', ['id' => $employee->id]))
            ->post(route('employee.edit_confirm'), [
                'id' => $employee->id,
                'email' => $existingEmailOwner->email
            ]);

        $response->assertRedirect(route('employee.edit', ['id' => $employee->id]));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_delete_employee(): void
    {
        $user = Employee::factory()->create();
        $employee = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('employee.delete', [$employee->id]));

        $response->assertRedirectToRoute('employee.index');
        $response->assertSessionHas('notification', 'Deleted employee successfully.');
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'del_flag' => 1
        ]);
    }

    public function test_recover_employee(): void
    {
        $user = Employee::factory()->create();
        $employee = Employee::factory()->create([
            'del_flag' => 1
        ]);

        $response = $this->actingAs($user, 'employees')
            ->get(route('employee.recover', [$employee->id]));

        $response->assertRedirectToRoute('employee.index');
        $response->assertSessionHas('notification', 'Recovered employee successfully.');
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'del_flag' => 0
        ]);
    }

    public function test_search_employee_by_name_and_email(): void
    {
        $user = Employee::factory()->create();

        foreach (['name', 'email'] as $by) {
            $response = $this->actingAs($user, 'employees')
                ->get(route('employee.search', [
                    'by' => $by,
                    'search' => 'a'
                ]));

            $response->assertOk();
            $response->assertViewHas('employees');
        }
    }

    public function test_search_employee_by_team(): void
    {
        $user = Employee::factory()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('employee.search', [
                'by' => 'team',
                'search' => $team->id
            ]));

            $response->assertOk();
            $response->assertViewHas('employees');
    }

    public function test_export_employee(): void
    {
        Carbon::setTestNow(now());
        Storage::fake('public');

        $user = Employee::factory()->create();
        $team = Team::factory()->create();
        $employee = Employee::factory()->create([
            'team_id' => $team->id,
            'email' => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'position' => 2,
            'type_of_work' => 1,
            'status' => 1,
            'del_flag' => 0,
        ]);

        foreach (['csv', 'xlsx'] as $format) {
            $filename = 'employees_' . now()->format('Ymd_His') . '.' . $format;

            foreach (['email' => $employee->email, 'name' => 'Doe', 'team' => $team->id] as $by => $search) {
                $response = $this->actingAs($user, 'employees')
                    ->get(route('employee.export', [
                        'export' => $format,
                        'by' => $by,
                        'search' => $search,
                    ]));

                $response->assertDownload($filename);

                $content = $response->streamedContent();
                $tmpPath = tempnam(sys_get_temp_dir(), 'spreadsheet');

                try {
                    file_put_contents($tmpPath, $content);

                    $reader = match ($format) {
                        'xlsx' => new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(),
                        'csv' => new \PhpOffice\PhpSpreadsheet\Reader\Csv(),
                    };

                    $spreadsheet = $reader->load($tmpPath);
                    $sheet = $spreadsheet->getActiveSheet();

                    $this->assertSame('ID', $sheet->getCell('A1')->getValue());
                    $this->assertSame('Email', $sheet->getCell('C1')->getValue());
                    $this->assertSame('First Name', $sheet->getCell('D1')->getValue());

                    $this->assertSame('John', $sheet->getCell('D2')->getValue());
                    $this->assertSame('Doe', $sheet->getCell('E2')->getValue());
                } finally {
                    @unlink($tmpPath);
                }
            }
        }

        Carbon::setTestNow();
    }

    public function test_employee_creator_and_updater_relationships(): void
    {
        $team = Team::factory()->create();

        $creator = Employee::factory()->create();
        $updater = Employee::factory()->create();

        $employee = Employee::factory()->create([
            'team_id' => $team->id,
            'ins_id' => $creator->id,
            'upd_id' => $updater->id,
            'ins_datetime' => now(),
            'upd_datetime' => now(),
        ]);

        $this->assertInstanceOf(Team::class, $employee->team);
        $this->assertTrue($employee->team->is($team));

        $this->assertInstanceOf(Employee::class, $employee->creator);
        $this->assertTrue($employee->creator->is($creator));

        $this->assertInstanceOf(Employee::class, $employee->updater);
        $this->assertTrue($employee->updater->is($updater));

        $this->assertTrue($creator->createdEmployees->contains($employee));

        $this->assertTrue($updater->updatedEmployees->contains($employee));
    }
}
// php artisan test --coverage --filter=test_employee_creator_and_updater_relationships

