@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Views</x-title>

    <x-small-title>
        Show your views some love
    </x-small-title>

    <x-body>
        <x-p>
            Feature tests check the response. But what about the form itself?
        </x-p>

        <x-code-label>The view:</x-code-label>

        <x-code language="html">
&lt;form method="post" action="/users"&gt;
    &#64;csrf
    &lt;input type="text" name="name" /&gt;
    &lt;input type="email" name="email" /&gt;
    &lt;input type="submit" value="Create" /&gt;
&lt;/form&gt;
        </x-code>

        <x-code-label>The test:</x-code-label>

        <x-code>
&lt;?php

namespace Tests\Feature\Http\Controllers\UserController;

use App\Models\User;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(ShortReportController::class)]
#[CoversMethod(ShortReportController::class, 'Create')]
class CreateTest extends TestCase
{
    use TestableFormRequest;

    #[Test]
    public function create_view_has_form(): void
    {
        $response = $this->get('/users/create');

        $response->assertViewIs('users.create')
            ->assertViewHas('team', function (Team $team) {
                return $team->name === 'Ac Milan';
            })
            ->assertOK()
            ->assertViewHasForm()
            ->assertFormHasCSRF()
            ->assertFormHasField('text', 'name')
            ->assertFormHasField('email', 'email')
            ->assertFormHasSubmitButton();
    }
}
        </x-code>

        <x-powered-by href="https://github.com/jcergolj/laravel-view-test-assertions">laravel-view-test-assertions</x-powered-by>
    </x-body>
@endsection
