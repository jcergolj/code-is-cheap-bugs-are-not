@extends('layouts.talk-app')

@section('content')
    <x-title>Testing HTTP Client</x-title>

    <x-small-title>
        Fake the API, assert the request
    </x-small-title>

    <x-body>
        <x-p>
            External APIs are unreliable. Fake them and test your integration.
        </x-p>

        <x-p>
            <strong>The controller making two API calls:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

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

        <x-p>
            <strong>Feature test — assert both requests:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

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

        <x-p>
            <strong>Protected callback methods for readability:</strong>
        </x-p>

        <x-code language="php">
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
