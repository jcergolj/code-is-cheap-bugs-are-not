@extends('layouts.talk-app')

@section('content')
    <x-title>Data Providers</x-title>

    <x-small-title>
        One test, many scenarios
    </x-small-title>

    <x-body>
        <x-p>
            Data providers let you run the same test with different inputs.
        </x-p>

        <x-p>
            <strong>Validation rules testing:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    #[Test]
    #[DataProvider('validationProvider')]
    public function validation_rules(string $field, mixed $value, string $rule): void
    {
        $response = $this->from(route('users.create'))
            ->post(route('users.store'), [$field => $value]);

        $response->assertRedirect(route('users.create'))
            ->assertSessionHasErrors($field);
    }

    public static function validationProvider(): array
    {
        return [
            'email is required' => ['email', '', 'required'],
            'email must be valid' => ['email', 'not-an-email', 'email'],
            'name is required' => ['name', '', 'required'],
            'name max length' => ['name', str_repeat('a', 256), 'max'],
        ];
    }
}
        </x-code>

        <x-p>
            <strong>Middleware testing:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Http\Middleware;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    #[Test]
    #[DataProvider('protectedRoutesProvider')]
    public function auth_middleware_is_applied(string $route): void
    {
        $response = $this->get(route($route));

        $response->assertMiddlewareIsApplied('auth');
    }

    public static function protectedRoutesProvider(): array
    {
        return [
            'dashboard' => ['dashboard.index'],
            'users list' => ['users.index'],
            'users create' => ['users.create'],
            'users edit' => ['users.edit'],
        ];
    }
}
        </x-code>

        <x-read-more href="https://jcergolj.me.uk/blog/data-providers-use-cases">Data Providers Use Cases</x-read-more>
    </x-body>
@endsection
