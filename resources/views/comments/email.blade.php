<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New comment on resource {{ $comment->resource_id }} from user {{ $comment->user->username }}</title>
</head>
<body>
<p>A new comment was posted on the <a href="{{ URL::to('resource/'.$comment->resource_id) }}" target="_blank">resource - {{ $comment->resource_id }}</a>. The comment says:</p>

<div><p style="border: 2px solid #f1f1f1f1;">{{ $comment->comment }}</p></div>

<p>Please review it on the <a href="{{ URL::to('admin/comments') }}" target="_blank">admin panel</a> at your earliest convenience. Thank you.</p>
</body>
</html>