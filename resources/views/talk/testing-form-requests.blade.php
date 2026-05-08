@extends('layouts.talk-app')

@section('content')
    <x-title>Form Requests</x-title>

    <x-small-title>
        Validate once, test twice
    </x-small-title>

    <x-body>
        <x-p>
            Form requests keep validation out of your controllers.
            Test them in isolation.
        </x-p>

        <x-section-label>Form Request</x-section-label>

        <x-code language="php">
// app/Http/Requests/StoreUserRequest.php
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }
}
        </x-code>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/UserController.php
public function store(StoreUserRequest $request)
{
    User::create($request->validated());

    return redirect()->route('users.index');
}
        </x-code>

        <x-section-label>Feature Test — assert form request is used</x-section-label>

        <x-code language="php" dataLine="4">
// tests/Feature/Http/Controllers/UserController/StoreTest.php
$this->post(route('users.store'));

$this->assertContainsFormRequest(StoreUserRequest::class);
        </x-code>

        <x-section-label>Unit Test — assert validation rules</x-section-label>

        <x-code language="php">
// tests/Unit/Http/Requests/StoreUserRequestTest.php
class StoreUserRequestTest extends TestCase
{
    use TestableFormRequest;

    #[Test]
    public function name_is_required(): void
    {
        $this->createFormRequest(StoreUserRequest::class)
            ->validate(['name' => ''])
            ->assertFails(['name' => 'required']);
    }

    #[Test]
    public function email_must_be_valid(): void
    {
        $this->createFormRequest(StoreUserRequest::class)
            ->validate(['email' => 'not-an-email'])
            ->assertFails(['email' => 'email']);
    }
}
        </x-code>

        <x-powered-by href="https://github.com/jcergolj/laravel-form-request-assertions">
            laravel-form-request-assertions
        </x-powered-by>
    </x-body>
@endsection
