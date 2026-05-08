@extends('layouts.talk-app')

@section('content')
    <x-title>HTTP Client</x-title>

    <x-small-title>
        Fake the API, assert the request
    </x-small-title>

    <x-body>
        <x-p>
            External APIs are unreliable. Fake them and test your integration.
        </x-p>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/SyncController.php
class SyncController extends Controller
{
    public function __invoke()
    {
        // POST to Trello
        $trelloResponse = Http::withToken(config('services.trello.key'))
            ->post('https://api.trello.com/1/cards', [
                'idList' => config('services.trello.list_id'),
                'name' => 'New Lead',
                'desc' => 'Lead from website',
            ]);

        $cardId = $trelloResponse->json('id');

        // GET from Cloudflare
        $cloudflareResponse = Http::withToken(config('services.cloudflare.token'))
            ->get("https://api.cloudflare.com/client/v4/zones/{$cardId}");

        return response()->json([
            'trello_card' => $cardId,
            'zone' => $cloudflareResponse->json('result.name'),
        ]);
    }
}
        </x-code>

        <x-section-label>Feature Test — assert both requests</x-section-label>

        <x-code language="php" dataLine="2-9,13-16">
// tests/Feature/Http/Controllers/SyncControllerTest.php
Http::fake([
    'api.trello.com/*' => Http::response([
        'id' => 'trello-card-123',
    ], Response::HTTP_OK),
    'api.cloudflare.com/*' => Http::response([
        'result' => ['name' => 'example.com'],
    ], Response::HTTP_OK),
]);

$this->post(route('sync'));

Http::assertSentInOrder([
    $this->assertTrelloRequestIsSent(),
    $this->assertCloudflareRequestIsSent('trello-card-123'),
]);
        </x-code>

        <x-section-label>Protected callback methods for readability</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/SyncControllerTest.php
protected function assertTrelloRequestIsSent()
{
    return function (Request $request) {
        $this->assertSame('POST', $request->method());
        $this->assertSame(
            'https://api.trello.com/1/cards',
            $request->url()
        );
        $this->assertSame('New Lead', $request->data()['name']);
        $this->assertArrayHasKey('idList', $request->data());

        return true;
    };
}

protected function assertCloudflareRequestIsSent(string $cardId)
{
    return function (Request $request) use ($cardId) {
        $this->assertSame('GET', $request->method());
        $this->assertSame(
            "https://api.cloudflare.com/client/v4/zones/{$cardId}",
            $request->url()
        );
        $this->assertEmpty($request->data());

        return true;
    };
}
        </x-code>
    </x-body>
@endsection
