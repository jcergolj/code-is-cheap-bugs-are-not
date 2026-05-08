@extends('layouts.talk-app')

@section('content')
    <x-title>Middleware</x-title>

    <x-small-title>
        The gatekeepers of your application
    </x-small-title>

    <x-body>
        <x-p>
            Middleware runs before your controller. It deserves its own tests.
        </x-p>

        <x-section-label>Middleware</x-section-label>

        <x-code language="php">
// app/Http/Middleware/RequireApiKey.php
class RequireApiKey
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->hasHeader('X-Api-Key')) {
            abort(Response::HTTP_UNAUTHORIZED, 'API key required.');
        }

        return $next($request);
    }
}
        </x-code>

        <x-section-label>Feature Test — assert middleware is applied</x-section-label>

        <x-code language="php" dataLine="4">
// tests/Feature/Http/Controllers/ApiControllerTest.php
$response = $this->get(route('api.data'));

$response->assertMiddlewareIsApplied('require-api-key');
        </x-code>

        <x-section-label>Unit Test — assert middleware logic</x-section-label>

        <x-code language="php">
// tests/Unit/Http/Middleware/RequireApiKeyTest.php
public function missing_header_aborts_request(): void
{
    $this->expectException(HttpException::class);

    $middleware = new RequireApiKey;
    $request = new Request;

    $middleware->handle($request, function () {
        $this->fail('Next middleware was called.');
    });
}

public function valid_header_continues_request(): void
{
    $request = Request::create('/api/data', 'GET');
    $request->headers->set('X-Api-Key', 'secret');

    $expectedResponse = new Response('allowed', Response::HTTP_OK);
    $next = fn() => $expectedResponse;

    $actualResponse = (new RequireApiKey)->handle($request, $next);

    $this->assertSame($expectedResponse, $actualResponse);
}
        </x-code>

        <x-powered-by href="https://github.com/jcergolj/additional-test-assertions-for-laravel">additional-test-assertions-for-laravel</x-powered-by>
    </x-body>
@endsection
