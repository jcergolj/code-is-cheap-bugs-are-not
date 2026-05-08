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

        <x-section-label>Middleware Testing</x-section-label>

        <x-code language="php">
// tests/Unit/Http/Middleware/AuthorizationTest.php
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

        <x-read-more href="https://jcergolj.me.uk/blog/data-providers-use-cases">
            Data Providers Use Cases
        </x-read-more>
    </x-body>
@endsection
