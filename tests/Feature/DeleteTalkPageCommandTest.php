<?php

use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Backup original routes
    $originalRoutes = File::exists(base_path('routes/talk.php'))
        ? File::get(base_path('routes/talk.php'))
        : "<?php\n";

    $this->originalRoutes = $originalRoutes;

    // Set up clean state with three pages
    File::put(base_path('routes/talk.php'), "<?php\n\nRoute::get('/', function () {\n    return view('talk/intro', ['next' => route('talk.about-me')]);\n})->name('talk.intro');\n\nRoute::get('/about-me', function () {\n    return view('talk/about-me', ['next' => route('talk.thank-you'), 'previous' => route('talk.intro')]);\n})->name('talk.about-me');\n\nRoute::get('/thank-you', function () {\n    return view('talk/thank-you', ['previous' => route('talk.about-me')]);\n})->name('talk.thank-you');\n");

    // Create dummy view files
    File::put(resource_path('views/talk/intro.blade.php'), '@extends("layouts.talk-app")');
    File::put(resource_path('views/talk/about-me.blade.php'), '@extends("layouts.talk-app")');
    File::put(resource_path('views/talk/thank-you.blade.php'), '@extends("layouts.talk-app")');
});

afterEach(function () {
    // Restore original routes
    File::put(base_path('routes/talk.php'), $this->originalRoutes);

    // Clean up views
    foreach (['intro', 'about-me', 'thank-you'] as $slug) {
        $viewPath = resource_path("views/talk/{$slug}.blade.php");
        if (File::exists($viewPath)) {
            File::delete($viewPath);
        }
    }
});

it('deletes a page and updates adjacent routes', function () {
    $this->artisan('delete-talk-page')
        ->expectsChoice('Which page do you want to delete?', 'about-me', ['intro', 'about-me', 'thank-you'])
        ->expectsConfirmation('Delete this page?', 'yes')
        ->assertExitCode(0);

    $routes = File::get(base_path('routes/talk.php'));

    // about-me route should be gone
    expect($routes)->not->toContain("Route::get('/about-me'");

    // intro should now point to thank-you as next
    expect($routes)->toContain("'next' => route('talk.thank-you')");

    // thank-you should now point to intro as previous
    expect($routes)->toContain("'previous' => route('talk.intro')");

    // View file should be deleted
    expect(File::exists(resource_path('views/talk/about-me.blade.php')))->toBeFalse();
});

it('prevents deleting the only page', function () {
    // Reduce to one page
    File::put(base_path('routes/talk.php'), "<?php\n\nRoute::get('/', function () {\n    return view('talk/intro');\n})->name('talk.intro');\n");

    File::delete(resource_path('views/talk/about-me.blade.php'));
    File::delete(resource_path('views/talk/thank-you.blade.php'));

    $this->artisan('delete-talk-page')
        ->assertExitCode(1);
});

it('aborts when user declines confirmation', function () {
    $this->artisan('delete-talk-page')
        ->expectsChoice('Which page do you want to delete?', 'about-me', ['intro', 'about-me', 'thank-you'])
        ->expectsConfirmation('Delete this page?', 'no')
        ->assertExitCode(0);

    // Route should still exist
    $routes = File::get(base_path('routes/talk.php'));
    expect($routes)->toContain("Route::get('/about-me'");
});
