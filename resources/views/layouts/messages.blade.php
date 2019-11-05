@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="form-required">
            {{ $error }}
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="form-required">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="form-required">
        {{ session('error') }}
    </div>
@endif