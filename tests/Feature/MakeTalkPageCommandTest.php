<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Backup original routes
    $originalRoutes = File::exists(base_path('routes/talk.php'))
        ? File::get(base_path('routes/talk.php'))
        : "<?php\n";

    // Store in closure scope for afterEach
    $this->originalRoutes = $originalRoutes;

    // Set up clean state with two existing pages
    File::put(base_path('routes/talk.php'), "<?php\n\nRoute::get('/', function () {\n    return view('talk/intro', ['next' => route('talk.thank-you')]);\n})->name('talk.intro');\n\nRoute::get('/thank-you', function () {\n    return view('talk/thank-you', ['previous' => route('talk.intro')]);\n})->name('talk.thank-you');\n");

    File::put(storage_path('app/last-page.txt'), '');
});

afterEach(function () {
    // Restore original routes
    File::put(base_path('routes/talk.php'), $this->originalRoutes);

    // Clean up created views
    foreach (['intro-to-hotwire', 'about-me', 'first-page'] as $slug) {
        $viewPath = resource_path("views/talk/{$slug}.blade.php");
        if (File::exists($viewPath)) {
            File::delete($viewPath);
        }
    }

    $lastPage = storage_path('app/last-page.txt');
    if (File::exists($lastPage)) {
        File::delete($lastPage);
    }
});

it('creates a page and inserts route in talk.php', function () {
    $this->artisan('make:talk-page')
        ->expectsQuestion('Title', 'Intro to Hotwire')
        ->expectsQuestion('Slug', 'intro-to-hotwire')
        ->expectsChoice('Section', 'content-center', ['content-center', 'content'])
        ->expectsChoice(
            'Insert position (page will be inserted after the selected one)',
            '(end)',
            ['(beginning)', 'intro', 'thank-you', '(end)']
        )
        ->expectsConfirmation('Create page?', 'yes')
        ->assertExitCode(0);

    $viewPath = resource_path('views/talk/intro-to-hotwire.blade.php');
    expect(File::exists($viewPath))->toBeTrue();
    expect(File::get($viewPath))->toContain("@section('content-center')");

    $routes = File::get(base_path('routes/talk.php'));
    expect($routes)->toContain("Route::get('/intro-to-hotwire'");
    expect($routes)->toContain("->name('talk.intro-to-hotwire')");
    expect($routes)->toContain("route('talk.intro-to-hotwire')");

    expect(trim(File::get(storage_path('app/last-page.txt'))))->toBe('intro-to-hotwire');
});

it('updates adjacent routes when inserting in the middle', function () {
    $this->artisan('make:talk-page')
        ->expectsQuestion('Title', 'About Me')
        ->expectsQuestion('Slug', 'about-me')
        ->expectsChoice('Section', 'content-center', ['content-center', 'content'])
        ->expectsChoice(
            'Insert position (page will be inserted after the selected one)',
            'intro',
            ['(beginning)', 'intro', 'thank-you', '(end)']
        )
        ->expectsConfirmation('Create page?', 'yes')
        ->assertExitCode(0);

    $routes = File::get(base_path('routes/talk.php'));

    // intro should now point to about-me as next
    expect($routes)->toContain("'next' => route('talk.about-me')");

    // about-me should point to intro as previous and thank-you as next
    expect($routes)->toContain("Route::get('/about-me'");
    expect($routes)->toContain("'previous' => route('talk.intro')");
    expect($routes)->toContain("'next' => route('talk.thank-you')");

    // thank-you should now point to about-me as previous
    expect($routes)->toContain("'previous' => route('talk.about-me')");
});

it('inserts at the beginning', function () {
    $this->artisan('make:talk-page')
        ->expectsQuestion('Title', 'First Page')
        ->expectsQuestion('Slug', 'first-page')
        ->expectsChoice('Section', 'content-center', ['content-center', 'content'])
        ->expectsChoice(
            'Insert position (page will be inserted after the selected one)',
            '(beginning)',
            ['(beginning)', 'intro', 'thank-you', '(end)']
        )
        ->expectsConfirmation('Create page?', 'yes')
        ->assertExitCode(0);

    $routes = File::get(base_path('routes/talk.php'));

    // first-page should have no previous, next should be intro
    expect($routes)->toContain("Route::get('/first-page'");
    expect($routes)->not->toContain("Route::get('/first-page', function () {\n    return view('talk/first-page', ['previous' =>");
    expect($routes)->toContain("'next' => route('talk.intro')");

    // intro should now have first-page as previous
    expect($routes)->toContain("'previous' => route('talk.first-page')");
});
