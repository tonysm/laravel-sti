<?php

use App\Admin;
use App\Employee;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    public function testCanCreateUsers()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, $user);
    }

    public function testFetchesInheritedClasses()
    {
        factory(User::class)->create(['type' => Employee::class]);
        $employee = User::first();

        $this->assertEquals(Employee::class, $employee->type);
        $this->assertInstanceOf(Employee::class, $employee);
    }

    public function testCanHaveBothClasses()
    {
        factory(User::class)->create(['type' => null]);
        factory(User::class)->create(['type' => Employee::class]);

        $users = User::orderBy('type', 'DESC')->get();

        $this->assertInstanceOf(Employee::class, $users->first());
        $this->assertInstanceOf(User::class, $users->last());
    }

    public function testChildModelKeepsScope()
    {
        factory(User::class, 2)->create(['type' => Employee::class]);
        factory(User::class)->create(['type' => null]);
        $employees = Employee::all();

        $this->assertCount(2, $employees);
        $employees->each(function ($employee) {
            $this->assertInstanceOf(Employee::class, $employee);
        });
    }

    public function testChildModelDefaultsType()
    {
        $data = factory(User::class)->make()->toArray();
        unset($data['type']);
        $employee = Employee::create($data + ['password' => 'testing']);

        $this->assertEquals(Employee::class, $employee->type);
    }

    public function testHandlesMultipleChilds()
    {
        factory(User::class)->create(['type' => null]);
        factory(User::class)->create(['type' => Employee::class]);
        factory(User::class)->create(['type' => Admin::class]);

        $users = User::orderBy('type', 'DESC')->get();

        $this->assertCount(3, $users);
        $this->assertInstanceOf(Employee::class, $users[0]);
        $this->assertInstanceOf(Admin::class, $users[1]);
        $this->assertInstanceOf(User::class, $users[2]);
    }

    public function testCanRemoveScope()
    {
        factory(User::class)->create(['type' => null]);
        factory(User::class)->create(['type' => Employee::class]);

        $users = Employee::withoutGlobalScope(\App\SingleTableInheritanceScope::class)->get();

        $this->assertCount(2, $users);
    }

    public function testCreatingUsersWithTypesReturnsChild()
    {
        $data = factory(User::class)->make(['type' => Employee::class])->toArray();
        $user = User::create($data + ['password' => 'testing']);

        $this->assertInstanceOf(Employee::class, $user);
    }
}
