@extends('layouts.main')
@section('content')
<section class="general-content">
    @include('layouts.messages')
    <header>
        <h1>Available Translations</h1>
    </header>
    <table class="translate">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <?php $item = $page->firstWhere('language',$localeCode);?>
            @if(count($item))
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ fixLanguage($item->language) }}</td>
                <td><a href="{{ URL::to($item->language.'/page/'.$item->id) }}">View</a></td>
            </tr>
            @else
                <tr>
                    <td>{{ $page_self->title }}</td>
                    <td>{{ fixLanguage($localeCode) }}</td>
                    <td><a href="{{ URL::to($localeCode.'/page/add/translate/'.$page_self->tnid.'/'.$localeCode) }}">Add</a></td>
                </tr>
            @endif
        @endforeach
    </table>
</section>
@endsection 