@extends('layouts.main')
@section('title')
    @lang('Create or edit a resource - step 3')
@endsection
@section('content')
    <div class="container mt-3">
        <h3>@lang('Create or edit a resource - step 3 of 3')</h3>
        <hr>
        <form method="POST" action="@if($edit){{ route('edit3', $resource['id']) }}@else{{ route('step3') }}@endif">
            @csrf
            <h4>@lang('Translation Rights') {{ en('Translation Rights') }}</h4>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" name="translation_rights" id="translation_rights" {{ $dbRecords->TranslationRights ? "checked" : "" }}>
                <label class="form-check-label" for="translation_rights">
                    @lang('I am providing a new translation. I have selected the license that appears on the original resource.')
                    @lang('If this is not translation, please skip this question and go to #2.')

                    <br>
                    <small>
                        {{ en('I am providing a new translation. I have selected the license that appears on the original resource If this is not translation, please skip this question and go to #2') }}
                    </small>
                </label>

                @if ($errors->has('translation_rights'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('translation_rights') }}</strong>
                    </span><br>
                @endif
            </div>
            <h4>@lang('Educational Resource') {{ en('Educational Resource') }}</h4>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" name="educational_resource" id="educational_resource" {{ $dbRecords->EducationalResources?"checked":""  }}>
                <label class="form-check-label" for="translation_rights">
                    @lang('I am submitting a resource to DDL that is already published. I have selected the license that is on the original resource.')
                    @lang('If you are the original author, please skip this question and go to #3.')

                    <br>
                    <small>
                        {{ en('I am submitting a resource to DDL that is already published. I have selected the license that is on the original resource. If you are the original author, please skip this question and go to #3.') }}
                    </small>
                </label>

                @if ($errors->has('educational_resource'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('educational_resource') }}</strong>
            </span><br>
                @endif
            </div>
            <h4>@lang('I am the author') {{ en('I am the author') }}</h4>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" name="iam_author" {{ $dbRecords->IamAuthors?"checked":"" }}>
                <label class="form-check-label" for="iam_author">
                    @lang('I am the author and I am submitting my resource to DDL. I am selecting a creative commons license for my resource below.')

                    <br>
                    <small>
                        {{ en('I am the author and I am submitting my resource to DDL. I am selecting a creative commons license for my resource below') }}
                    </small>
                </label>

                @if ($errors->has('iam_author'))
                    <span class="invalid-feedback">
                    <strong>{{ $errors->first('iam_author') }}</strong>
                </span><br>
                @endif
            </div>
            <div class="form-item col-6">
                <label for="copyright_holder">
                    <strong>@lang('License/Copyright Holder') {{ en('License/Copyright Holder') }}</strong>
                </label>
                <input class="form-control{{ $errors->has('copyright_holder') ? ' is-invalid' : '' }}"
                       id="copyright_holder"
                       name="copyright_holder"
                       size="40"
                       type="text"
                       aria-describedby="licenseHelp"
                       value="{{ $dbRecords->CopyrightHolder?$dbRecords->CopyrightHolder->value:"" }}"
                >
                <small id="licenseHelp" class="form-text text-muted">
                    @lang('Please enter the name of the person or organization owning or managing rights over the resource.') <br>
                    {{ en('Please enter the name of the person or organization owning or managing rights over the resource') }}
                </small>
                @if ($errors->has('copyright_holder'))
                    <span class="invalid-feedback">
                <strong>{{ $errors->first('copyright_holder') }}</strong>
            </span><br>
                @endif
            </div>
            <h4>@lang('Select one of these') {{ en('Select one of these') }}</h4>
            <div class="form-item">
                <label for="creative_commons">
                    <strong>@lang('If there is Creative Commons License on the resource, select one of these') <br>
                        {{ en('If there is Creative Commons License on the resource, select one of these') }}
                    </strong>
                </label>
                @foreach($creativeCommons AS $cc)
                        <?php
                        if (!empty($dbRecords)) {
                            if($dbRecords->creativeCommons){
                                $cc_common = $dbRecords->creativeCommons[0]->name;
                            }else{
                                $cc_common = "";
                            }
                        }
                        ?>
                    @if(in_array($cc->tnid, array(535, 536, 537, 159, 6187)))
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="{{ $cc->id }}" name="creative_commons" id="radio_for_{{ $cc->id }}" {{ ($cc_common == $cc->name)?"checked":"" }}>
                            <label class="form-check-label" for="radio_for_{{ $cc->id }}">
                                {{ $cc->name }}
                            </label>
                        </div>
                    @endif
                @endforeach
                <div class="description">
                    @lang('Unsure of which option to select?') <a href="{{ URL::to('/page/2252') }}" title="@lang('Copyright help')">@lang('Click here')</a> @lang('for guidance on licensing this resource.') <br>
                    <small dir="ltr"> {{ en('Unsure of which option to select? click the link above for guidance on licensing this resource') }} </small>
                </div>
            </div>
            <div class="form-item">
                <label for="creative_commons_other">
                    <strong>@lang('If there is no Creative Commons License on the resource, select one these:') <br>
                        {{ en('If there is no Creative Commons License on the resource, select one these') }}</strong>
                </label>
                @foreach($creativeCommons AS $other)
                    @if(!in_array($other->tnid, array(535, 536, 537, 159, 6187)))
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="{{ $other->id }}" name="creative_commons_other" id="radio_for_{{ $other->id }}" @if($dbRecords->SharePermissions) {{ $dbRecords->SharePermissions->tid == $other->id?"checked":"" }} @endif>
                            <label class="form-check-label" for="radio_for_{{ $other->id }}">
                                {{ $other->name . termEn($other->id) }}
                            </label>
                        </div>
                    @endif
                @endforeach
            </div>
            @if (isAdmin() or isLibraryManager())
                <div class="form-item">
                    <label for="published">
                        <strong>@lang('Published?') {{ en('Published?') }}</strong>
                    </label>
                    <input type="radio" name="published" id="no-pub" {{ ($resource['status'] == 0)?"checked":""}} value="0"> <label for="no-pub">@lang('No') {{ en('No') }}</label>
                    <input type="radio" name="published" id="yes-pub" {{ ($resource['status'] == 1)?"checked":""}} value="1"> <label for="yes-pub">@lang('Yes') {{ en('Yes') }}</label>
                </div>
            @endif
            @include('layouts.messages')
            <div class="d-grid gap-2 d-md-block">
                <input class="btn btn-primary btn-md" type="button" value="@lang('Previous') {{ en('Previous') }}" onclick="location.href='@if($edit){{ route('edit2', $resource['id']) }}@else{{ route('step2') }}@endif'">
                <input class="btn btn-primary btn-md" type="submit" value="@lang('Submit') {{ en('Submit') }}">
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('input:radio[name="creative_commons"]').change(function() {
                    $('input:radio[name="creative_commons_other"]').attr('disabled',true);
                });

                $('input:radio[name="creative_commons_other"]').change(function() {
                    $('input:radio[name="creative_commons"]').attr('disabled',true);
                });
            });
        </script>
    @endpush
@endsection
