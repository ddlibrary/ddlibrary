@extends('layouts.main')
@section('content')
<section class="general-content">
    @include('layouts.messages')
    <header>
        <h1>Available Translations</h1>
    </header>
    <table class="translate">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <?php if (isset($news) && isset($localeCode)) {
                $item = $news->firstWhere('language',$localeCode);
            }?>
            @if($item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ fixLanguage($item->language) }}</td>
                <td><a href="{{ URL::to($item->language.'/news/'.$item->id) }}">View</a></td>
            </tr>
            @else
                <tr>
                    <td>{{ $news_self->title }}</td>
                    <td>{{ $properties['name'] }}</td>
                    <td><a href="{{ URL::to($localeCode.'/news/add/translate/'.$news_self->tnid.'/'.$localeCode) }}">Add</a></td>
                </tr>
            @endif
        @endforeach
    </table>
</section>
@endsection 
