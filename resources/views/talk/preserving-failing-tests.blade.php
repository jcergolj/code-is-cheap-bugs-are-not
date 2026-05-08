@extends('layouts.talk-app')

@section('content')
    <x-title>Preserving Failing Tests</x-title>

    <x-small-title>
        Capture output so you can fix tests one by one
    </x-small-title>

    <x-body>
        <x-p>
            Navigating inside terminal is hard. Unless you are using tmux :) (something for another talk)
        </x-p>

        <x-p>
            What if we save the output? Like this
        </x-p>

        <x-code language="bash">
ParaTest v7.22.3 upon PHPUnit 13.1.8 by Sebastian Bergmann and contributors.

Processes:     6
Runtime:       PHP 8.4.20
Configuration: /home/jcergolj/work/projects/enneagramprofiling/phpunit.xml

.F...........................................................  50 / 100 ( 50%)
...........................................................F. 100 / 100 ( 100%)

Time: 00:19.357, Memory: 68.50 MB

FAILURES!
Tests: 100, Assertions: 200, Failures: 2

--

There were 2 failures:

1) Tests\Feature\UserCanLoginTest::user_can_login_with_valid_credentials
Expected status code 200 but received 302.
Failed asserting that 200 is identical to 302.

/home/jcergolj/work/projects/enneagramprofiling/tests/Feature/UserCanLoginTest.php:45

2) Tests\Unit\Services\PaymentServiceTest::it_calculates_tax_correctly
Failed asserting that 10.5 matches expected 10.0.

/home/jcergolj/work/projects/enneagramprofiling/tests/Unit/Services/PaymentServiceTest.php:32

FAILURES!
Tests: 100, Assertions: 200, Failures: 2
        </x-code>

        <x-section-label>The solution — save output to a file</x-section-label>

        <x-code language="bash">
# Clear old output and run tests
rm -f .phpunit-output.txt && php artisan test --parallel > .phpunit-output.txt
        </x-code>

        <x-section-label>Now read the file at your own pace</x-section-label>

        <x-code language="bash">
# View all failures
cat .phpunit-output.txt

# Or open in your editor
code .phpunit-output.txt
        </x-code>

        <x-section-label>Workflow</x-section-label>

        <x-ul>
            <x-li>Run tests once, save output</x-li>
            <x-li>Open .phpunit-output.txt</x-li>
            <x-li>Fix one test at a time. Run single test inside terminal</x-li>
            <x-li>No need to re-run until you're done</x-li>
        </x-ul>

        <x-section-label>"Pro" tip</x-section-label>

        <x-code language="bash">
# Add to your .bashrc or .zshrc
alias tsave='rm -f .phpunit-output.txt && php artisan test --parallel > .phpunit-output.txt'

# Now just type
tsave
        </x-code>
    </x-body>
@endsection
