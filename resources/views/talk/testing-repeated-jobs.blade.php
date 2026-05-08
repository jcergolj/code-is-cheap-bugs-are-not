@extends('layouts.talk-app')

@section('content')
    <x-title>Repeated Jobs</x-title>

    <x-small-title>
        Better ways to test same-class jobs
    </x-small-title>

    <x-body>
        <x-p>
            When the same job is dispatched multiple times, standard assertions hide which one failed.
        </x-p>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/JobController.php
class JobController extends Controller
{
    public function __invoke()
    {
        TestJob::dispatch('John', 30);
        TestJob::dispatch('Will', 20);
    }
}
        </x-code>

        <x-section-label>Standard way (vague)</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/JobControllerTest.php
Queue::assertPushed(TestJob::class, 2);

Queue::assertPushed(fn($job) => $job->name === 'John' && $job->age === 30);
Queue::assertPushed(fn($job) => $job->name === 'Will' && $job->age === 20);
        </x-code>

        <x-section-label>Better way (pinpoint exact failures)</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/JobControllerTest.php
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

        <x-read-more href="https://jcergolj.me.uk/blog/better-ways-to-test-repeated-laravel-jobs">
            Better Ways to Test Repeated Laravel Jobs
        </x-read-more>
    </x-body>
@endsection
