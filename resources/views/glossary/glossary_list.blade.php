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
            <td style="width:5%">Subject</td>
            <td style="width:5%">
                <select name="subject" class="form-control">
                    <option value="">Any</option>
                    <option value="physics" {{ (isset($filters['subject']) && $filters['subject'] == "physics")?"selected":"" }}>Physics</option>
                    <option value="math" {{ (isset($filters['subject']) && $filters['subject'] == "math")?"selected":"" }}>Math</option>
                    <option value="chemistry" {{ (isset($filters['subject']) && $filters['subject'] == "chemistry")?"selected":"" }}>Chemistry</option>
                </select>
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
    </tr>
    @endforeach
    </table>
    <div class="resource-pagination">
        {{ $glossary->links() }}
    </div>
</section>
@endsection 