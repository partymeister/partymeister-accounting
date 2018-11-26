@foreach ($itemTypes as $itemType)
    <h4>{{$itemType->name}}</h4>
    @if ($itemType->items->count() > 0)
        <table class="unstriped">
            <thead>
            <tr>
                <th>Name</th>
                <th class="text-right">Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($itemType->items()->where('is_visible', true)->orderBy('sort_position', 'ASC')->get() as $item)
                <tr>
                    <td>
                        {{$item->name}}
                        @if (strlen($item->description) > 2)
                            <br>
                            <small>{{$item->description}}</small>
                        @endif
                    </td>
                    <td class="text-right">
                        {{number_format($item->price_with_vat, 2, ',', '.')}} &euro;
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endforeach
