<!DOCTYPE html>
<html lang="en">

@section('htmlheader')
    @include('partymeister-accounting::layouts.partials.htmlheader')
@show

<body class="skin-blue sidebar-mini">
<div id="app">
    <div class="row">
        <div class="col-md-9">
            @for ($i=1; $i<=5; $i++)
                <div id="droppable-{{$i}}" class="droppable"
                     style="height: 165px; margin-bottom: 20px;">
                    @if (isset($record->pos_configuration[$i]))
                        @foreach ($record->pos_configuration[$i] as $id)
                            @php
                                $item = Partymeister\Accounting\Models\Item::find($id);
                                if (!is_null($item)) {
                                    $items[] = $item;
                                }
                            @endphp
                            @if ($id == 'separator')
                                <div class="item well separator"></div>
                            @else
                                <div class="item well" data-item-id="{{$item->id}}">
                                    <h2>{{$item->name}}</h2>
                                    <br>
                                    <input type="hidden" id="price_{{$item->id}}" name="price_[{{$item->id}}]"
                                           value="{{$item->price_with_vat}}">
                                    <input type="hidden" id="link_{{$item->id}}"
                                           data-negative="{{$item->pos_can_book_negative_quantities}}"
                                           name="link_{{$item->id}}"
                                           value="{{$item->pos_create_booking_for_item_id}}">
                                    <div class="buttons">
                                        @if ($item->pos_can_book_negative_quantities)
                                            <button data-quantity="-1" data-item="{{$item->id}}"
                                                    class="add-item btn-lg btn-danger">-1
                                            </button>
                                            <button data-quantity="-2" data-item="{{$item->id}}"
                                                    class="add-item btn-lg btn-danger">-2
                                            </button>
                                        @else
                                            <button data-quantity="1" data-item="{{$item->id}}"
                                                    class="add-item btn-lg btn-success">+1
                                            </button>
                                            <button data-quantity="2" data-item="{{$item->id}}"
                                                    class="add-item btn-lg btn-success">+2
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endfor
        </div>
        <div class="col-md-3">
            <div class="sales well-lg mb-3">
                <button class="btn btn-danger btn-md clear pull-right float-right">{{trans('partymeister-accounting::backend/pos.clear')}}</button>
                <h3 class="pull-left">{{trans('partymeister-accounting::backend/pos.current_order')}}</h3>

                <form id="submit" method="POST">
                    {{ csrf_field() }}
                    <table class="table beverage">
                        <thead>
                        <tr>
                            <td>{{trans('partymeister-accounting::backend/items.item')}}</td>
                            <td>&nbsp;</td>
                            <td style="text-align: right;">{{trans('partymeister-accounting::backend/pos.price')}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $item)
                            <tr class="items" id="sales_{{$item->id}}" data-item-id="{{$item->id}}"
                                style="display: none;">
                                <td class="sales_item"><span>0</span>x {{$item->name}}</td>
                                <td style="text-align: right;" class="sales_price" data-total="0"><span>0</span></td>
                                <td style="text-align: right;">@if (!$item->pos_can_book_negative_quantities)
                                        <button class="btn btn-danger delete" data-item-id="{{$item->id}}">X
                                        </button>@else&nbsp;@endif</td>
                            </tr>
                        @endforeach
                        <tr id="sales_total">
                            <td style="font-weight: bold; color: red;">{{trans('partymeister-accounting::backend/pos.total')}}</td>
                            <td style="font-weight: bold; color: red; text-align: right;" class="grand_total"></td>
                            <td>&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                    <button id="book"
                            class="btn-lg btn-success">{{trans('partymeister-accounting::backend/pos.book')}}</button>
                    <input type="hidden" id="booking" name="booking"/>
                </form>
            </div>
            <div class="well-lg last-booking hide mb-3">
                <h3>{{trans('partymeister-accounting::backend/pos.last_booking')}}</h3>
                <small class="last-booking-created-at"></small>
                <table class="table beverage">
                    <thead>
                    <tr>
                        <td>{{trans('partymeister-accounting::backend/items.item')}}</td>
                        <td style="text-align: right;">{{trans('partymeister-accounting::backend/pos.sum')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="last-booking-description"></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: red;">{{trans('partymeister-accounting::backend/pos.total')}}</td>
                        <td style="font-weight: bold; color: red; text-align: right;" class="last-booking-total"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <a href="{{route('backend.accounts.index')}}" class="clear">
                <button class="btn btn-lg btn-primary"
                        style="float: right;">{{trans('partymeister-accounting::backend/pos.back')}}</button>
            </a>
        </div>
    </div>
</div>

@section('scripts')
    @include('partymeister-accounting::layouts.partials.scripts')
@show

@yield('view_scripts')
<script type="text/javascript">
    let accountId = {{ $record->id }};

    let addItem = function (quantity, itemId, deleteItem) {

        // Let the button blink
        $('.add-item[data-item="' + itemId + '"][data-quantity="' + quantity + '"').effect('highlight');

        // Get current item quantity
        let itemQuantity = parseInt($('#sales_' + itemId + ' td.sales_item span').html());

        // Get new item quantity
        let newQuantity = itemQuantity + quantity;

        // Get the linked item id (usually deposit)
        let depositSelector = $('#link_' + itemId);
        let deposit = depositSelector.val();

        // TODO: link id must match the deposit it from the database
        // If we have a positive quantity or we can book negative, or an item is deleted
        if (newQuantity > 0 || depositSelector.data('negative') === 1 || deleteItem === true) {
            $('#sales_' + itemId).css('display', 'table-row');
            $('#sales_' + itemId + ' td.sales_item span').html(newQuantity);
            recalculateDeposit(deposit, quantity, itemQuantity, false);
        } else {
            recalculateDeposit(deposit, quantity, itemQuantity, true);
            newQuantity = 0;
        }

        if (newQuantity === 0) {
            $('#sales_' + itemId + ' div').data('total', 0);
            $('#sales_' + itemId + ' div span').html(0);
            $('#sales_' + itemId).css('display', 'none');
        }

        let total = parseFloat($(':input#price_' + itemId).val()) * newQuantity;
        let totalHuman = calculateCurrency(total, 1);
        $('#sales_' + itemId + ' td.sales_price').data('total', total);
        $('#sales_' + itemId + ' td.sales_price span').html(totalHuman);

        let grandTotal = 0;
        $('.sales_price').each(function (index) {
            grandTotal += parseFloat($(this).data('total'));
        });
        $('.grand_total').text(calculateCurrency(grandTotal, 1));

    };

    let recalculateDeposit = function (depositId, quantity, itemQuantity, checkQuantity) {

        if (depositId === '') {
            return;
        }

        if (checkQuantity && quantity < itemQuantity) {
            quantity = itemQuantity * -1;
        }

        let depositQuantitySelector = $('#sales_' + depositId + ' td.sales_item span');

        let depositQuantity = parseInt(depositQuantitySelector.html());

        let newDepositQuantity = depositQuantity + quantity;

        let total = parseFloat($(':input#price_' + depositId).val()) * newDepositQuantity;

        let totalHuman = calculateCurrency(total, 1);
        depositQuantitySelector.html(newDepositQuantity);
        $('#sales_' + depositId + ' td.sales_price span').html(totalHuman);
        $('#sales_' + depositId + ' td.sales_price').data('total', total);

        $('#sales_' + depositId).css('display', 'table-row');
        if (total === 0) {
            $('#sales_' + depositId).css('display', 'none');
        }
    };

    $(document).ready(function () {

        $('.clear').click(function () {
            clearItems();
        });

        $('.delete').click(function () {
            let itemId = $(this).data('item-id');

            let itemQuantity = parseInt($('#sales_' + itemId + ' td.sales_item span').html());

            addItem(itemQuantity * -1, itemId, true);
            return false;
        });

        $('#submit').submit(function (e) {

            e.preventDefault();

            // Let the button blink
            $('#book').effect('highlight');

            let data = {};
            $('.beverage tbody tr').each(function (index) {
                let itemId = $(this).data('item-id');
                if (itemId !== undefined) {
                    let quantity = parseInt($('#sales_' + itemId + ' td.sales_item span').html());

                    if (quantity !== 0) {
                        data[itemId] = quantity;
                    }
                }
            });

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            $.ajax({
                type: "POST",
                url: '{{ route('backend.pos.create', ['account' => $record->id]) }}',
                data: {items: data},
                success: function (response) {
                    updateLastBooking(response.data);
                    clearItems();
                }
            });

            return false;
        });
    });

    let calculateCurrency = function (price, quantity) {
        return Number((parseFloat(quantity) * parseFloat(price)).toFixed(2)).toLocaleString('de-DE', {
            style: 'currency',
            currency: '{{$record->currency_iso_4217}}',
            currencyDisplay: 'symbol'
        });
    };

    let clearItems = function () {
        $('.beverage tbody tr').each(function (index) {

            let itemId = $(this).data('item-id');

            $('#sales_' + itemId + ' td.sales_item span').html(0);
            $('#sales_' + itemId + ' td.sales_price span').html(0);
            $('#sales_' + itemId + ' td.sales_price').data('total', 0);
            $('#sales_' + itemId).css('display', 'none');
        });

        $('.grand_total').text(calculateCurrency(0, 0));
    };

    let updateLastBooking = function (booking) {
        if (booking === null || booking === undefined) {
            return;
        }

        let lastBooking = $('.last-booking');

        $('.last-booking-created-at').html(booking.created_at);
        $('.last-booking-description').html(booking.description.replace(/\n/g, "<br>"));
        $('.last-booking-total').html(calculateCurrency(booking.price_with_vat, 1));
        lastBooking.removeClass('hide');
        lastBooking.effect('highlight');
    };


    $('.add-item').click(function () {
        addItem(parseInt($(this).data('quantity')), $(this).data('item'));
    });

    let lastBooking = {!! $last_booking !!};

    updateLastBooking(lastBooking);
</script>

</body>
</html>
