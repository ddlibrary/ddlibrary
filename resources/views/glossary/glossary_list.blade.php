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
    <table class="glossary">
        <tr>
            <td style="width:5%">@lang('Text')</td>
            <td style="width:5%">
                <input class="form-control" type="text" name="text" id="text" value="{{ isset($filters['text'])?$filters['text']:"" }}">
            </td>
            <td style="width:5%">@lang('Subject')</td>
            <td style="width:5%">
                <select name="subject" class="form-control">
                    <option value="">@lang('Any')</option>
                    <option value="physics" {{ (isset($filters['subject']) && $filters['subject'] == "physics")?"selected":"" }}>@lang('Physics')</option>
                    <option value="math" {{ (isset($filters['subject']) && $filters['subject'] == "math")?"selected":"" }}>@lang('Math')</option>
                    <option value="chemistry" {{ (isset($filters['subject']) && $filters['subject'] == "chemistry")?"selected":"" }}>@lang('Chemistry')</option>
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
            <th>@lang('NO')</th>
            <th>@lang('English Name')</th>
            <th>@lang('Farsi Name')</th>
            <th>@lang('Pashto Name')</th>
            <th>@lang('Subject')</th>
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