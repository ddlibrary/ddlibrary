@extends('layouts.main')
@section('search')
    @include('layouts.search')
@endsection
@section('content')
<section class="resourceList">
    <aside>
        <h3>{{ $resources->total() }} Results</h3>
        <form method="POST" action="{{ route('resourceList') }}">
        @csrf
        <h4>Resource Subject Areas</h4>
        <ul>
        @foreach($subjects AS $subject)
            @if($subject->parent == 0)
                <li><input type="checkbox" name="subject_area[]" {{ (in_array($subject->id, $subjectAreaIds)?"checked":"")}} onchange="fnTest(this,'subSubject{{$subject->id}}');" value="{{ $subject->id }}">{{ $subject->name }} 
                    <?php $subjectParent = $subjects->where('parent', $subject->id);?>
                    @if(count($subjectParent) > 0)
                        <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subSubject{{$subject->id}}')"></i>
                    @endif
                @if(count($subjectParent) > 0)
                    <ul id="subSubject{{$subject->id}}" style="display:none;">
                        @foreach($subjectParent as $item)
                            <li><input type="checkbox" class="child"  name="subject_area[]" {{ (in_array($item->id, $subjectAreaIds)?"checked":"")}} value="{{ $item->id }}">{{ $item->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </li>
            @endif
        @endforeach
        </ul>
        <h4>Resource Types</h4>
        <ul>
            @foreach($types AS $type)
                <li><input type="checkbox" name="type[]" value="{{ $type->id }}" {{ (in_array($type->id, $typeIds)?"checked":"")}}>{{ $type->name }}</li>
            @endforeach
        </ul>
        <h4>Resource Levels</h4>
        <ul>
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li><input type="checkbox" name="level[]" {{ (in_array($level->id, $levelIds)?"checked":"")}} value="{{ $level->id }}" onchange="fnTest(this,'subLevel{{$level->id}}');">{{ $level->name }}
                        <?php $levelParent = $levels->where('parent', $level->id);?>
                        @if(count($levelParent) > 0)
                            <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$level->id}}')"></i>
                        @endif
                    @if(count($levelParent) > 0)
                        <ul id="subLevel{{$level->id}}" style="display:none;">
                            @foreach($levelParent as $item)
                                <li><input type="checkbox" name="level[]" {{ (in_array($item->id, $levelIds)?"checked":"")}} class="child" value="{{ $item->id }}">{{ $item->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </li>
                @endif
            @endforeach
        </ul>
        <input class="form-control normalButton" type="submit" value="Apply Filter">
        </form>
    </aside>
    <section class="resourceInformationSection">
    @if (count($resources) > 0)
    @foreach ($resources AS $resource)
    <article class="resourceArticle resourceInformation" onclick="location.href='{{ URL::to('resources/view/'.$resource->resourceid) }}'">
        <img class="resourceImg" src="{{ getImagefromResource($resource->abstract) }}">
        <div class="resourceTitle">{{ str_limit($resource->title, 55), ' (..)' }}</div>
        <div class="resourceDetails">
            <article>
                <i class="far fa-file-audio"></i><span>Audio</span>
            </article>
            <article>
                <i class="fas fa-eye"></i><span>3999</span>
            </article>
            <article>
                <i class="fas fa-star"></i><span>26</span>
            </article>
            <article>
                <i class="fas fa-comment"></i><span>4</span>
            </article>
        </div>
    </article>
    @endforeach
    @else
    <h2>No records found!</h2>
    @endif
    <div class="resourcePagination">
        {{ $resources->links() }}
    </div>
    </section>
</section>
@endsection 