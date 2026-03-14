@foreach ($itemTypes as $itemType)
    <div class="mb-8 last:mb-0">
    <h4 class="mb-2">{{$itemType->name}}</h4>
    @if ($itemType->items->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                <tr>
                    <th class="px-4 py-3 font-medium text-heading bg-surface">Name</th>
                    <th class="px-4 py-3 font-medium text-heading bg-surface text-right">Price</th>
                </tr>
                </thead>
                <tbody>
                @foreach($itemType->items as $item)
                    <tr>
                        <td class="px-4 py-3 border-t border-border text-text">
                            {{$item->name}}
                            @if (strlen($item->description) > 2)
                                <br>
                                <small class="text-sm text-text-muted">{{$item->description}}</small>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-t border-border text-text text-right">
                            {{number_format($item->price_with_vat, 2, ',', '.')}} &euro;
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    </div>
@endforeach
