@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Repeated Jobs</x-title>

    <x-small-title>
        Better ways to test same-class jobs
    </x-small-title>

    <x-body>
        <x-p>
            When the same job is dispatched multiple times, standard assertions hide which one failed.
        </x-p>

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
class JobController extends Controller
{
    public function __invoke()
    {
        TestJob::dispatch('John', 30);
        TestJob::dispatch('Will', 20);
    }
}
        </x-code>

        <x-p>
            <strong>The standard way (vague):</strong>
        </x-p>

        <x-code language="php">
Queue::assertPushed(TestJob::class, 2);

Queue::assertPushed(function (TestJob $job) {
    return $job->name === 'John' && $job->age === 30;
});

Queue::assertPushed(function (TestJob $job) {
    return $job->name === 'Will' && $job->age === 20;
});
        </x-code>

        <x-p>
            <strong>The better way (pinpoint exact failures):</strong>
        </x-p>

        <x-code language="php">
Queue::assertPushed(TestJob::class, 2);

$index = 0;
$assertions = [
    ['name' => 'John', 'age' => 30],
    ['name' => 'Will', 'age' => 20],
];

Queue::assertPushed(function (TestJob $job) use (&$index, $assertions) {
    $this->assertSame(
        $assertions[$index]['name'],
        $job->name,
        "Job #{$index}: name should be {$assertions[$index]['name']}"
    );

    $this->assertSame(
        $assertions[$index]['age'],
        $job->age,
        "Job #{$index}: age should be {$assertions[$index]['age']}"
    );

    $index++;

    return true;
});
        </x-code>

        <x-p>
            Now you know exactly which job and which parameter failed.
        </x-p>

        <x-p>
            Read more: <x-link href="https://jcergolj.me.uk/blog/better-ways-to-test-repeated-laravel-jobs">Better Ways to Test Repeated Laravel Jobs</x-link>
        </x-p>
    </x-body>
@endsection
