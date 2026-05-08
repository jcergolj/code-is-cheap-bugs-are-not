@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Middleware</x-title>

    <x-small-title>
        The gatekeepers of your application
    </x-small-title>

    <x-body>
        <x-p>
            Middleware runs before your controller. It deserves its own tests.
        </x-p>

        <x-p>
            <strong>The middleware:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

        <x-p>
            <strong>Applied in routes:</strong>
        </x-p>

        <x-code language="php">
Route::middleware(RequireApiKey::class)->group(function () {
    Route::get('api/data', [ApiController::class, 'index']);
});
        </x-code>

        <x-p>
            <strong>Feature test — assert middleware is applied:</strong>
        </x-p>

        <x-code language="php">
$response = $this->get(route('api.data'));

$response->assertMiddlewareIsApplied('require-api-key');
        </x-code>

        <x-p>
            <strong>Unit test — assert middleware logic:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\RequireApiKey;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RequireApiKeyTest extends TestCase
{
    #[Test]
    public function missing_header_aborts_request(): void
    {
        $this->expectException(HttpException::class);

        $middleware = new RequireApiKey;
        $request = new Request;

        $middleware->handle($request, function () {
            $this->fail('Next middleware was called.');
        });
    }

    #[Test]
    public function valid_header_continues_request(): void
    {
        $request = Request::create('/api/data', 'GET');
        $request->headers->set('X-Api-Key', 'secret');

        $expectedResponse = new Response('allowed', Response::HTTP_OK);
        $next = function () use ($expectedResponse) {
            return $expectedResponse;
        };

        $actualResponse = (new RequireApiKey)->handle($request, $next);

        $this->assertSame($expectedResponse, $actualResponse);
    }
}
        </x-code>

        <x-p>
            Powered by @jcergolj <x-link href="https://github.com/jcergolj/additional-test-assertions-for-laravel">additional-test-assertions-for-laravel</x-link>
        </x-p>
    </x-body>
@endsection
