@extends('layouts.talk-app')

@section('content')
    <x-title>File Storage</x-title>

    <x-small-title>
        Upload, store, download
    </x-small-title>

    <x-body>
        <x-p>
            Files are tricky. Fake the disk and test the full flow.
        </x-p>

        <x-section-label>Controller — Upload</x-section-label>

        <x-code language="php">
// app/Http/Controllers/DocumentController.php
public function store(Request $request)
{
    $request->file('document')->store('documents', 's3');

    // ...
}
        </x-code>

        <x-section-label>Feature Test — assert file is saved</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/DocumentController/StoreTest.php
Storage::fake('s3');

$file = UploadedFile::fake()->create('report.pdf', 100);

$this->actingAs($user)
    ->post(route('documents.store'), [
        'document' => $file,
    ]);

Storage::disk('s3')->assertExists('documents/'.$file->hashName());
        </x-code>

        <x-section-label>Controller — Download</x-section-label>

        <x-code language="php">
// app/Http/Controllers/DocumentController.php
public function show(Document $document)
{
    return Storage::disk('s3')
        ->download($document->path);
}
        </x-code>

        <x-section-label>Feature Test — assert file is downloaded</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/DocumentController/ShowTest.php
Storage::fake('s3');

$expectedFilename = "document.pdf";
Storage::disk('s3')->put($expectedFilename, 'fake-content');

$response = $this->actingAs($user)
    ->get(route('documents.show', $document));

$response->assertDownload($expectedFilename);
        </x-code>
    </x-body>
@endsection
