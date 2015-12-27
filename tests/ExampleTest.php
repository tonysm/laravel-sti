<?php

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
}
