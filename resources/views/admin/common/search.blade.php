<div class="table-responsive">
    <form method="POST" action="{{ route($route) }}">
    @csrf
        <table class="table table-bordered" width="100%" cellspacing="0">
            <tr>
                <td>Term</td>
                <td>
                    <input class="form-control" type="text" name="term" value="{{ isset($filters['term'])?$filters['term']:"" }}">
                </td>
                <td>Vocabulary</td>
                <td>
                    <select class="form-control" name="vocabulary">
                    <option value="">Any</option>
                    @foreach($vocabulary as $vb)
                    <option value="{{ $vb->val }}" {{ (isset($filters['vocabulary']) && $filters['vocabulary'] == $vb->val)?"selected":"" }}>{{ $vb->name }}</option>
                    @endforeach
                    </select>
                </td>
                <td>Language</td>
                <td>
                    <select class="form-control" name="language">
                    <option value="">Any</option>
                    @foreach(LaravelLocalization::getSupportedLocales() as $localcode => $properties)
                    <option value="{{ $localcode }}" {{ (isset($filters['language']) && $filters['language'] == $localcode)?"selected":"" }}>{{ $properties['name'] }}</option>
                    @endforeach
                    </select>
                </td>
                <td colspan="2">
                    <input class="btn btn-primary float-right" type="submit" value="Filter">
                </td>
            </tr>
        </table>
    </form>
</div>