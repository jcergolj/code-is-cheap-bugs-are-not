<?php

Route::get('/', function () {
    return view('talk/intro', ['next' => route('talk.silly-bit')]);
})->name('talk.intro');

Route::get('/silly-bit', function () {
    return view('talk/silly-bit', ['next' => route('talk.why'), 'previous' => route('talk.intro')]);
})->name('talk.silly-bit');

Route::get('/why', function () {
    return view('talk/why', ['next' => route('talk.phpunit'), 'previous' => route('talk.silly-bit')]);
})->name('talk.why');

Route::get('/phpunit', function () {
    return view('talk/phpunit', ['next' => route('talk.feature-vs-unit'), 'previous' => route('talk.why')]);
})->name('talk.phpunit');

Route::get('/feature-vs-unit', function () {
    return view('talk/feature-vs-unit', ['next' => route('talk.arrange-act-assert'), 'previous' => route('talk.phpunit')]);
})->name('talk.feature-vs-unit');

Route::get('/arrange-act-assert', function () {
    return view('talk/arrange-act-assert', ['next' => route('talk.test-structure'), 'previous' => route('talk.feature-vs-unit')]);
})->name('talk.arrange-act-assert');

Route::get('/test-structure', function () {
    return view('talk/test-structure', ['next' => route('talk.testing-views'), 'previous' => route('talk.arrange-act-assert')]);
})->name('talk.test-structure');

Route::get('/testing-views', function () {
    return view('talk/testing-views', ['next' => route('talk.testing-storing-in-db'), 'previous' => route('talk.test-structure')]);
})->name('talk.testing-views');

Route::get('/testing-storing-in-db', function () {
    return view('talk/testing-storing-in-db', ['next' => route('talk.testing-file-storage'), 'previous' => route('talk.testing-views')]);
})->name('talk.testing-storing-in-db');

Route::get('/testing-file-storage', function () {
    return view('talk/testing-file-storage', ['next' => route('talk.testing-pattern'), 'previous' => route('talk.testing-storing-in-db')]);
})->name('talk.testing-file-storage');

Route::get('/testing-pattern', function () {
    return view('talk/testing-pattern', ['next' => route('talk.testing-middleware'), 'previous' => route('talk.testing-file-storage')]);
})->name('talk.testing-pattern');

Route::get('/testing-middleware', function () {
    return view('talk/testing-middleware', ['next' => route('talk.testing-form-requests'), 'previous' => route('talk.testing-pattern')]);
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
    return view('talk/testing-repeated-jobs', ['next' => route('talk.testing-mails'), 'previous' => route('talk.testing-jobs')]);
})->name('talk.testing-repeated-jobs');

Route::get('/testing-mails', function () {
    return view('talk/testing-mails', ['next' => route('talk.testing-http-client'), 'previous' => route('talk.testing-repeated-jobs')]);
})->name('talk.testing-mails');

Route::get('/testing-http-client', function () {
    return view('talk/testing-http-client', ['next' => route('talk.mocks'), 'previous' => route('talk.testing-mails')]);
})->name('talk.testing-http-client');

Route::get('/mocks', function () {
    return view('talk/mocks', ['next' => route('talk.query-objects'), 'previous' => route('talk.testing-http-client')]);
})->name('talk.mocks');

Route::get('/query-objects', function () {
    return view('talk/query-objects', ['next' => route('talk.data-providers'), 'previous' => route('talk.mocks')]);
})->name('talk.query-objects');

Route::get('/data-providers', function () {
    return view('talk/data-providers', ['next' => route('talk.fake-implementations'), 'previous' => route('talk.query-objects')]);
})->name('talk.data-providers');

Route::get('/fake-implementations', function () {
    return view('talk/fake-implementations', ['next' => route('talk.contract-tests'), 'previous' => route('talk.data-providers')]);
})->name('talk.fake-implementations');

Route::get('/contract-tests', function () {
    return view('talk/contract-tests', ['next' => route('talk.preserving-failing-tests'), 'previous' => route('talk.fake-implementations')]);
})->name('talk.contract-tests');

Route::get('/back-to-beginning', function () {
    return redirect()->route('talk.intro');
})->name('talk.back-to-beginning');

Route::get('/preserving-failing-tests', function () {
    return view('talk/preserving-failing-tests', ['next' => route('talk.intro'), 'previous' => route('talk.contract-tests')]);
})->name('talk.preserving-failing-tests');
