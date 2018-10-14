@extends('layouts.main')
@section('title')
DDL Glossary
@endsection
@section('description')
DDL Glossary
@endsection
@section('page_image')
{{ asset('storage/files/logo-dd.png') }}
@endsection
@section('content')
<section class="general-content">
<form method="POST" action="{{ route('glossary') }}" id="gform">
    @csrf
    <table>
        <tr>
            <td style="width:5%">Text</td>
            <td style="width:5%">
                <input class="form-control" type="text" name="text" id="text" value="{{ isset($filters['text'])?$filters['text']:"" }}">
            </td>
            <td>
                <input class="form-control" type="submit" value="Filter">
            </td>
        </tr>
    </table>
    </form>
    <table>
        <tr>
            <th>NO</th>
            <th>Name English</th>
            <th>Name Farsi</th>
            <th>Name Pashto</th>
            <th>Subject</th>
            @if (isAdmin())
            <th>Operations</th>
            @endif
        </tr>
        @foreach($glossary as $indexkey => $item)
        <tr>
        <td>
            {{ (($glossary->currentPage() - 1) * $glossary->perPage())+$indexkey + 1 }}
        </td>
        <td>
            {{ $item->name_en }}
        </td>
        <td>
            {{ $item->name_fa }}
        </td>
        <td>
            {{ $item->name_ps }}
        </td>
        <td>
            {{ $item->subject }}
        </td>
        @if (isAdmin())
        <td>
            <a href="{{ URL::to('page/edit/'.$item->id) }}">Edit</a>
            <a href="{{ URL::to('page/translate/'.$item->id.'/'.$item->tnid) }}">Translate</a>
        </td>
        @endif
    </tr>
    @endforeach
    </table>
    <div class="resource-pagination">
        {{ $glossary->links() }}
    </div>
</section>
@endsection 