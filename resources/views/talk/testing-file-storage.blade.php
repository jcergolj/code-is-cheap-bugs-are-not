@extends('layouts.talk-app')

@section('content')
    <x-title>Testing File Storage</x-title>

    <x-small-title>
        Upload, store, download
    </x-small-title>

    <x-body>
        <x-p>
            Files are tricky. Fake the disk and test the full flow.
        </x-p>

        <x-p>
            <strong>The controller — upload:</strong>
        </x-p>

        <x-code language="php">
public function store(Request $request)
{
    $request->validate([
        'document' => ['required', 'file', 'mimes:pdf'],
    ]);

    $path = $request->file('document')
        ->store('documents', 's3');

    Document::create([
        'user_id' => auth()->id(),
        'path' => $path,
    ]);

    return redirect()->route('documents.index');
}
        </x-code>

        <x-p>
            <strong>Feature test — assert file is saved:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

Storage::fake('s3');

$file = UploadedFile::fake()->create('report.pdf', 100);

$this->actingAs($user)
    ->post(route('documents.store'), [
        'document' => $file,
    ]);

Storage::disk('s3')->assertExists('documents/'.$file->hashName());
        </x-code>

        <x-p>
            <strong>The controller — download:</strong>
        </x-p>

        <x-code language="php">
public function show(Document $document)
{
    return Storage::disk('s3')
        ->download($document->path);
}
        </x-code>

        <x-p>
            <strong>Feature test — assert file is downloaded:</strong>
        </x-p>

        <x-code language="php">
Storage::fake('s3-standard-report');

$token = Token::factory()
    ->completed()
    ->create([
        'code' => 'ABCD1234',
        'scores' => '1',
    ]);

$expectedFilename = "{$token->code}-2.pdf";

Storage::disk('s3-standard-report')->put($expectedFilename, 'fake-content');

$response = $this->actingAs($user)
    ->get(route('reports.show', $token->code));

$response->assertStatus(Response::HTTP_OK);

$response->assertHeader('content-type', 'application/pdf');

$response->assertDownload($expectedFilename);

$response->assertHeader('Content-Disposition', "attachment; filename={$expectedFilename}");
        </x-code>
    </x-body>
@endsection
