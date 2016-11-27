<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

@section('htmlheader')
    @include('partymeister-accounting::layouts.partials.htmlheader')
@show

<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="skin-blue sidebar-mini">
<div id="app">

    <div class="row">
        <div class="col-md-9">
            <div>
                @foreach ($items as $item)
                    <div class="item well">
                        <h2>{{$item->name}}</h2>
                        <input type="hidden" id="price_{{$item->id}}" name="price_[{{$item->id}}]"
                               value="{{$item->price_with_vat}}">
                        <input type="hidden" id="link_{{$item->id}}"
                               data-negative="{{$item->pos_can_book_negative_quantities}}" name="link_{{$item->id}}"
                               value="{{$item->pos_create_booking_for_item_id}}">
                        <br/>
                        @if ($item->pos_can_book_negative_quantities)
                            <button data-quantity="-1" data-item="{{$item->id}}" class="add-item btn-lg btn-danger">-1</button>
                            <button data-quantity="-2" data-item="{{$item->id}}" class="add-item btn-lg btn-danger">-2</button>
                        @else
                            <button data-quantity="1" data-item="{{$item->id}}" class="add-item btn-lg btn-success">+1</button>
                            <button data-quantity="2" data-item="{{$item->id}}" class="add-item btn-lg btn-success">+2</button>
                        @endif
                    </div>
                    @if ($item->pos_do_break)<div style="clear: both;"></div>@endif
                @endforeach
            </div>
        </div>
        <div class="col-md-3">
            <div class="sales well">
                <a href="#" class="clear pull-right">
                    <button class="btn btn-danger">{{trans('partymeister-accounting::backend/pos.clear')}}</button>
                </a>
                <h2 class="pull-left">{{trans('partymeister-accounting::backend/items.items')}}</h2>

                <form id="submit" method="POST">
                    <table class="table beverage">
                        <thead>
                        <tr>
                            <td>{{trans('partymeister-accounting::backend/items.item')}}</td>
                            <td style="text-align: right;">{{trans('partymeister-accounting::backend/pos.price')}}</td>
                            <td>&nbsp;</td>
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
            @if (!is_null($last_booking))
                <div class="well">
                    <h2>{{trans('partymeister-accounting::backend/pos.last_booking')}}</h2>
                    <small>{{$last_booking->created_at}}</small>
                    <table class="table beverage">
                        <thead>
                        <tr>
                            <td>{{trans('partymeister-accounting::backend/items.item')}}</td>
                            <td style="text-align: right;">{{trans('partymeister-accounting::backend/pos.sum')}}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ nl2br($last_booking->description) }}</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; color: red;">{{trans('partymeister-accounting::backend/pos.total')}}</td>
                            <td style="font-weight: bold; color: red; text-align: right;">{{$last_booking->price_with_vat}} {{$last_booking->currency_iso_4217}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{route('backend.accounts.index')}}" class="clear">
                <button class="btn btn-primary"
                        style="float: right;">{{trans('partymeister-accounting::backend/pos.back')}}</button>
            </a>
        </div>
    </div>
</div> <!-- /container -->

</div>

@section('scripts')

    @include('partymeister-accounting::layouts.partials.scripts')

@show

@yield('view_scripts')
<script type="text/javascript">
    var accountId = {{ $record->id }};

    var addItem = function(quantity, itemId, deleteItem) {

        // Let the button blink
        $('.add-item[data-item="'+itemId+'"][data-quantity="'+quantity+'"').effect('highlight');

        // Get current item quantity
        var itemQuantity = parseInt($('#sales_' + itemId + ' td.sales_item span').html());

        // Get new item quantity
        var newQuantity = itemQuantity + quantity;

        // Get the linked item id (usually deposit)
        var deposit = $('#link_' + itemId).val();

        // TODO: link id must match the deposit it from the database
        // If we have a positive quantity or we can book negative, or an item is deleted
        if (newQuantity > 0 || $('#link_' + itemId).data('negative') == 1 || deleteItem == true) {
            $('#sales_' + itemId).css('display', 'table-row');
            $('#sales_' + itemId + ' td.sales_item span').html(newQuantity);
            recalculateDeposit(deposit, quantity, itemQuantity, false);
        } else {
            recalculateDeposit(deposit, quantity, itemQuantity, true);
            newQuantity = 0;
        }

        if (newQuantity == 0) {
            $('#sales_' + itemId + ' div').data('total', 0);
            $('#sales_' + itemId + ' div span').html(0);
            $('#sales_' + itemId).css('display', 'none');
        }

        var total = parseFloat($(':input#price_' + itemId).val()) * newQuantity;
        var totalHuman = calculateCurrency(total, 1);
        $('#sales_' + itemId + ' td.sales_price').data('total', total);
        $('#sales_' + itemId + ' td.sales_price span').html(totalHuman);

        var grandTotal = 0;
        $('.sales_price').each(function (index) {
            grandTotal += parseFloat($(this).data('total'));
        });
        $('.grand_total').text(calculateCurrency(grandTotal, 1));

    };

    var recalculateDeposit = function(depositId, quantity, itemQuantity, checkQuantity) {

        if (depositId == '') {
            return;
        }

        if (checkQuantity && quantity < itemQuantity) {
            quantity = itemQuantity * -1;
        }

        var depositQuantity = parseInt($('#sales_' + depositId + ' td.sales_item span').html());

        var newDepositQuantity = depositQuantity + quantity;

        console.log($(':input#price_' + depositId).val());

        var total = parseFloat($(':input#price_' + depositId).val()) * newDepositQuantity;

        var totalHuman = calculateCurrency(total, 1);
        $('#sales_' + depositId + ' td.sales_item span').html(newDepositQuantity);
        $('#sales_' + depositId + ' td.sales_price span').html(totalHuman);
        $('#sales_' + depositId + ' td.sales_price').data('total', total);

        $('#sales_' + depositId).css('display', 'table-row');
        if (total == 0) {
            $('#sales_' + depositId).css('display', 'none');
        }
    };

    $(document).ready(function () {

        $('.clear').click(function () {
            document.location.href = window.location.href;
        });

        $('.delete').click(function () {
            var itemId = $(this).data('item-id');

            var itemQuantity = parseInt($('#sales_' + itemId + ' td.sales_item span').html());

            addItem(itemQuantity * -1, itemId, true);
            return false;
        });

        $('#submit').submit(function () {

            // Let the button blink
            $('#book').effect('highlight');

            var data = {};
            $('.beverage tbody tr').each(function (index) {
                var itemId = $(this).data('item-id');
                if (itemId != undefined) {
                    var quantity = parseInt($('#sales_' + itemId + ' td.sales_item span').html());

                    if (quantity != 0) {
                        data[index] = {
                            'account_id': accountId,
                            'item_id': itemId,
                            'quantity': quantity
                        }
                    }
                }
            });

            $('#booking').val(JSON.stringify(data));

        });
    });

    var calculateCurrency = function(price, quantity) {
        return Number((parseFloat(quantity) * parseFloat(price)).toFixed(2)).toLocaleString('de-DE', {style: 'currency', currency: '{{$record->currency_iso_4217}}', currencyDisplay: 'symbol'});
    };



    $('.add-item').click(function () {
        addItem(parseInt($(this).data('quantity')), $(this).data('item'));
    });
</script>

</body>
</html>
