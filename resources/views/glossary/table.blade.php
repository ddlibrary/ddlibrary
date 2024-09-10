<table>
    <tr>
        <th>{{ __('No.') }}</th>
        <th>{{ __('English') }}</th>
        <th>{{ __('Farsi') }}</th>
        <th>{{ __('Pashto') }}</th>
        <th>{{ __('Subject') }}</th>
        @if (isLibraryManager() or isAdmin())
            <th style="text-align: center;">
                {{ __('Delete') }}
            </th>
            @if ($flagged_queue)
                <th style="text-align: center;">
                    {{ __('Approve') }}
                </th>
            @endif
        @endif
    </tr>
    @forelse($glossary as $indexkey => $item)
        <tr>
            <td>
                {{ (($glossary->currentPage() - 1) * $glossary->perPage())+$indexkey + 1 }}
            </td>
            <td @if (isLibraryManager() or isAdmin()) contenteditable="true" data-id="{{ $item->id }}" data-type="glossary" data-language="en" @endif>
                @if (! $item->name_en)
                    -
                @else
                    {!! $item->name_en !!}
                @endif
            </td>
            <td @if (isLibraryManager() or isAdmin()) contenteditable="true" data-id="{{ $item->id }}" data-type="glossary" data-language="fa" @endif>
                @if (! $item->name_fa)
                    -
                @else
                    {!! $item->name_fa !!}
                @endif
            </td>
            <td @if (isLibraryManager() or isAdmin()) contenteditable="true"  data-id="{{ $item->id }}" data-type="glossary" data-language="ps" @endif>
                @if (! $item->name_ps)
                    -
                @else
                    {!! $item->name_ps !!}
                @endif
            </td>
            <td>
                @if (! $item->subject)
                    -
                @else
                    {{ $glossary_subjects[$item->subject] }}
                @endif
            </td>
            @if (isLibraryManager() or isAdmin())
                <td style="text-align: center">
                    <button type="button" class="btn glossary_delete" data-id="{{ $item->id }}"><i class="far fa-trash-alt"></i></button>
                </td>
                @if ($flagged_queue)
                    <td style="text-align: center">
                        <button type="button" class="btn glossary_approve" data-id="{{ $item->id }}"><i class="fas fa-check"></i></button>
                    </td>
                @endif
            @endif
        </tr>
    @empty
        <tr>
            <td>{{ __('No items to show.') }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforelse
</table>
