<?php

Route::get('/', function () {
    return view('talk/intro', ['next' => route('talk.silly-bit')]);
})->name('talk.intro');

Route::get('/silly-bit', function () {
    return view('talk/silly-bit', ['next' => route('talk.why'), 'previous' => route('talk.intro')]);
})->name('talk.silly-bit');

Route::get('/why', function () {
    return view('talk/why', ['next' => route('talk.shoulders-of-giants'), 'previous' => route('talk.silly-bit')]);
})->name('talk.why');

Route::get('/shoulders-of-giants', function () {
    return view('talk/shoulders-of-giants', ['next' => route('talk.phpunit'), 'previous' => route('talk.why')]);
})->name('talk.shoulders-of-giants');

Route::get('/phpunit', function () {
    return view('talk/phpunit', ['next' => route('talk.feature-vs-unit'), 'previous' => route('talk.shoulders-of-giants')]);
})->name('talk.phpunit');

Route::get('/feature-vs-unit', function () {
    return view('talk/feature-vs-unit', ['next' => route('talk.arrange-act-assert'), 'previous' => route('talk.phpunit')]);
})->name('talk.feature-vs-unit');

Route::get('/arrange-act-assert', function () {
    return view('talk/arrange-act-assert', ['next' => route('talk.testing-views'), 'previous' => route('talk.feature-vs-unit')]);
})->name('talk.arrange-act-assert');

Route::get('/testing-views', function () {
    return view('talk/testing-views', ['next' => route('talk.testing-store-method'), 'previous' => route('talk.arrange-act-assert')]);
})->name('talk.testing-views');

Route::get('/testing-store-method', function () {
    return view('talk/testing-store-method', ['next' => route('talk.testing-middleware'), 'previous' => route('talk.testing-views')]);
})->name('talk.testing-store-method');

Route::get('/testing-middleware', function () {
    return view('talk/testing-middleware', ['next' => route('talk.testing-form-requests'), 'previous' => route('talk.testing-views')]);
})->name('talk.testing-middleware');

Route::get('/testing-form-requests', function () {
    return view('talk/testing-form-requests', ['next' => route('talk.events-listeners'), 'previous' => route('talk.testing-middleware')]);
})->name('talk.testing-form-requests');

Route::get('/events-listeners', function () {
    return view('talk/events-listeners', ['next' => route('talk.testing-jobs'), 'previous' => route('talk.testing-form-requests')]);
})->name('talk.events-listeners');

Route::get('/testing-jobs', function () {
    return view('talk/testing-jobs', ['next' => route('talk.testing-repeated-jobs'), 'previous' => route('talk.events-listeners')]);
})->name('talk.testing-jobs');

Route::get('/testing-repeated-jobs', function () {
    return view('talk/testing-repeated-jobs', ['next' => route('talk.testing-jobs-inside-jobs'), 'previous' => route('talk.testing-jobs')]);
})->name('talk.testing-repeated-jobs');

Route::get('/testing-jobs-inside-jobs', function () {
    return view('talk/testing-jobs-inside-jobs', ['next' => route('talk.testing-file-storage'), 'previous' => route('talk.testing-repeated-jobs')]);
})->name('talk.testing-jobs-inside-jobs');

Route::get('/testing-file-storage', function () {
    return view('talk/testing-file-storage', ['next' => route('talk.testing-mails'), 'previous' => route('talk.testing-jobs-inside-jobs')]);
})->name('talk.testing-file-storage');

Route::get('/testing-mails', function () {
    return view('talk/testing-mails', ['next' => route('talk.testing-http-client'), 'previous' => route('talk.testing-file-storage')]);
})->name('talk.testing-mails');

Route::get('/testing-http-client', function () {
    return view('talk/testing-http-client', ['next' => route('talk.mocks-stubs-spies'), 'previous' => route('talk.testing-mails')]);
})->name('talk.testing-http-client');

Route::get('/mocks-stubs-spies', function () {
    return view('talk/mocks-stubs-spies', ['next' => route('talk.mocking-in-laravel'), 'previous' => route('talk.testing-http-client')]);
})->name('talk.mocks-stubs-spies');

Route::get('/mocking-in-laravel', function () {
    return view('talk/mocking-in-laravel', ['next' => route('talk.complex-queries-problem'), 'previous' => route('talk.mocks-stubs-spies')]);
})->name('talk.mocking-in-laravel');

Route::get('/complex-queries-problem', function () {
    return view('talk/complex-queries-problem', ['next' => route('talk.repository-pattern'), 'previous' => route('talk.mocking-in-laravel')]);
})->name('talk.complex-queries-problem');

Route::get('/repository-pattern', function () {
    return view('talk/repository-pattern', ['next' => route('talk.query-objects'), 'previous' => route('talk.complex-queries-problem')]);
})->name('talk.repository-pattern');

Route::get('/query-objects', function () {
    return view('talk/query-objects', ['next' => route('talk.mocking-eloquent-models'), 'previous' => route('talk.repository-pattern')]);
})->name('talk.query-objects');

Route::get('/mocking-eloquent-models', function () {
    return view('talk/mocking-eloquent-models', ['next' => route('talk.data-providers'), 'previous' => route('talk.query-objects')]);
})->name('talk.mocking-eloquent-models');

Route::get('/data-providers', function () {
    return view('talk/data-providers', ['next' => route('talk.contract-tests'), 'previous' => route('talk.mocking-eloquent-models')]);
})->name('talk.data-providers');

Route::get('/contract-tests', function () {
    return view('talk/contract-tests', ['next' => route('talk.about-me'), 'previous' => route('talk.data-providers')]);
})->name('talk.contract-tests');

Route::get('/about-me', function () {
    return view('talk/about-me', ['previous' => route('talk.contract-tests')]);
})->name('talk.about-me');
