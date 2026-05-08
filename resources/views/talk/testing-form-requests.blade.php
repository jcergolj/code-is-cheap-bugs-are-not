@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Form Requests</x-title>

    <x-small-title>
        Validate once, test twice
    </x-small-title>

    <x-body>
        <x-p>
            Form requests keep validation out of your controllers.
            Test them in isolation.
        </x-p>

        <x-p>
            <strong>The request:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }
}
        </x-code>

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
public function store(StoreUserRequest $request)
{
    User::create($request->validated());

    return redirect()->route('users.index');
}
        </x-code>

        <x-p>
            <strong>Feature test — assert form request is used:</strong>
        </x-p>

        <x-code language="php">
$this->post(route('users.store'));

$this->assertContainsFormRequest(StoreUserRequest::class);
        </x-code>

        <x-p>
            <strong>Unit test — assert validation rules:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\StoreUserRequest;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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

        <x-powered-by href="https://github.com/jcergolj/laravel-form-request-assertions">laravel-form-request-assertions</x-powered-by>
    </x-body>
@endsection
