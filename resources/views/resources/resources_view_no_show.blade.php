<base target="_parent">
@if ($state == 'no-auth')
    <h3 class="download-resource">@lang('Please <a href="'. URL::to('login')  .'">login</a> to view or download this resource.')</h3>
@elseif ($state == 'no-verify')
    <h3 class="download-resource">@lang('Please <a href="'. URL::to('email/verify')  .'">verify</a> your email to view or download this resource.')</h3>
@endif
