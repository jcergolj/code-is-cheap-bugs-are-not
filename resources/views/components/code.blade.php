@props(['id' => 'code-example', 'dataLine' => null, 'language' => 'php'])

<div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 my-6">
    <pre id="{{ $id }}" class="m-0" @if($dataLine !== null) data-line="{{ $dataLine }}" @endif><code class="language-{{ $language }}">{{ $slot }}</code></pre>
</div>
