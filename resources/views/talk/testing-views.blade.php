@extends('layouts.talk-app')

@section('content')
    <x-title>Views</x-title>

    <x-small-title>
        Show your views some love
    </x-small-title>

    <x-body>
        <x-p>
            Feature tests check the response. But what about the form itself?
        </x-p>

        <x-section-label>The View</x-section-label>

        <x-code language="html">
&lt;!-- resources/views/users/create.blade.php --&gt;
&lt;form method="post" action="/users"&gt;
    &#64;csrf
    &lt;input type="text" name="name" /&gt;
    &lt;input type="email" name="email" /&gt;
    &lt;input type="submit" value="Create" /&gt;
&lt;/form&gt;

&lt;div id="user-list"&gt;
    &lt;div id="user-card"&gt;&lt;/div&gt;
&lt;/div&gt;
        </x-code>

        <x-section-label>The Test</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/UserController/CreateTest.php
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
            ->assertFormHasSubmitButton()
            ->assertElementHasChild('div#user-list', 'div#user-card');
    }
}
        </x-code>

        <x-powered-by href="https://github.com/jcergolj/laravel-view-test-assertions">
            laravel-view-test-assertions
        </x-powered-by>
    </x-body>
@endsection
