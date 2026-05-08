@extends('layouts.talk-app')

@section('content')
<x-code id="code-example" language="php" dataLine="1-2,3-4">
echo 213;
</x-code>

<x-code-from-file language="php" dataLine="1-2,3-4" file="routes/web.php" />

@endsection
