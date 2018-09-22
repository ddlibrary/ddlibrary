<script>
    $('.pagination a').on('click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#content-loading').show();
        $('#resource-list').hide();
        $.ajax({
            url: url,
            success: function(data){
                $('#content-loading').hide();
                $('#resource-list').show();
                $("#resource-list").html(data);
                $('html, body').animate({ scrollTop: 0 }, 200);
            }
        });
    });
</script>

<script>
    $("#search-form").submit( function(e){
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $('#content-loading').show();
        $('#resource-list').hide();
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data){
                $('#content-loading').hide();
                $('#resource-list').show();
                $("#resource-list").html(data);
                $('html, body').animate({ scrollTop: 0 }, 200);
            }
        });
    });
</script>

<script>
    $("#side-form").submit(function(e){
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');

        $('#content-loading').show();
        $('#resource-list').hide();
        $.ajax({
            type: "POST",
            url: url,
            headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
            data: form.serialize(), // serializes the form's elements.
            success: function(data){
                $('#content-loading').hide();
                $('#resource-list').show();
                $("#resource-list").html(data);
            }
        });
    });
</script>

@if (count($resources) > 0)
@foreach ($resources AS $resource)
<a href="{{ URL::to('resource/'.$resource->id) }}" title="{{ $resource->title }}">
    <article class="resource-article resource-information">
        <img class="resource-img" src="{{ getImagefromResource($resource->abstract) }}" alt="Resource Image">
        <div class="resource-title">{{ $resource->title }}</div>
        <div class="resource-details">
            <article>
                <i class="fas fa-eye"></i><span>{{ $resource->totalviews }}</span>
            </article>
            <article>
                <i class="fas fa-star"></i><span>{{ $resource->totalfavorite }}</span>
            </article>
            <article>
                <i class="fas fa-comment"></i><span>{{ $resource->totalcomments }}</span>
            </article>
        </div>
    </article>
</a>
@endforeach
@else
<h2>@lang('No records found!')</h2>
@endif
<div class="resource-pagination">
    {{ $resources->appends(request()->input())->links() }}
</div>