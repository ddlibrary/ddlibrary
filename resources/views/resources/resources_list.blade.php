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
                <li><input type="checkbox" name="subject_area[]" {{ (in_array($subject->tid, $subjectAreaIds)?"checked":"")}} onchange="fnTest(this,'subSubject{{$subject->tid}}');this.form.submit();" value="{{ $subject->tid }}">{{ $subject->name }} 
                    <?php $subjectParent = $subjects->where('parent', $subject->tid);?>
                    @if(count($subjectParent) > 0)
                        <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subSubject{{$subject->tid}}')"></i>
                    @endif
                @if(count($subjectParent) > 0)
                    <ul id="subSubject{{$subject->tid}}" class="subItem" style="display:none;">
                        @foreach($subjectParent as $item)
                            <li><input type="checkbox" class="child"  name="subject_area[]" onchange="this.form.submit()" {{ (in_array($item->tid, $subjectAreaIds)?"checked":"")}} value="{{ $item->tid }}">{{ $item->name }}</li>
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
                <li><input type="checkbox" name="type[]" value="{{ $type->tid }}" onchange="this.form.submit()" {{ (in_array($type->tid, $typeIds)?"checked":"")}}>{{ $type->name }}</li>
            @endforeach
        </ul>
        <h4>Resource Levels</h4>
        <ul>
            @foreach($levels AS $level)
                @if($level->parent == 0)
                    <li><input type="checkbox" name="level[]" {{ (in_array($level->tid, $levelIds)?"checked":"")}} value="{{ $level->tid }}" onchange="fnTest(this,'subLevel{{$level->tid}}');this.form.submit()">{{ $level->name }}
                        <?php $levelParent = $levels->where('parent', $level->tid);?>
                        @if(count($levelParent) > 0)
                            <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$level->tid}}')"></i>
                        @endif
                    @if(count($levelParent) > 0)
                        <ul id="subLevel{{$level->tid}}" class="subItem" style="display:none;">
                            @foreach($levelParent as $item)
                                <li><input type="checkbox" name="level[]" onchange="fnTest(this,'subLevel{{$item->tid}}');this.form.submit()" {{ (in_array($item->tid, $levelIds)?"checked":"")}} class="child" value="{{ $item->tid }}">{{ $item->name }}
                            
                                <?php $levelItemParent = $levels->where('parent', $item->tid);?>
                                @if(count($levelItemParent) > 0)
                                    <i class="fas fa-plus fa-xs" onclick="javascript:showHide(this,'subLevel{{$item->tid}}')"></i>
                                @endif
                                @if(count($levelItemParent) > 0)
                                    <ul id="subLevel{{$item->tid}}" class="subItem" style="display:none;">
                                        @foreach($levelItemParent as $itemLevel)
                                            <li><input type="checkbox" name="level[]" onchange="this.form.submit()" {{ (in_array($itemLevel->tid, $levelIds)?"checked":"")}} class="child" value="{{ $itemLevel->tid }}">{{ $itemLevel->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
                @endif
            @endforeach
        </ul>
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
                <i class="fas fa-eye"></i><span>0</span>
            </article>
            <article>
                <i class="fas fa-star"></i><span>0</span>
            </article>
            <article>
                <i class="fas fa-comment"></i><span>0</span>
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