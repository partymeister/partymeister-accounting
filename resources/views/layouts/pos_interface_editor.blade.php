<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

@section('htmlheader')
    @include('partymeister-accounting::layouts.partials.htmlheader')
    <style type="text/css">
        .buttons button {
            max-width: 40%;
        }
        button.delete-config-item {
            top: -25px;
        }
    </style>
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
            @for ($i=1; $i<=5; $i++)
                <div id="droppable-{{$i}}" class="droppable"
                     style="border: 1px dotted red; border-radius: 5px; height: 165px; margin-bottom: 20px;">
                    @if (isset($record->pos_configuration[$i]))
                        @foreach ($record->pos_configuration[$i] as $item)
                            <div class="item well" data-item-id="{{$item}}" style="position: relative;">
                                @if ($item == 'separator')
                                    <h2>Separator</h2>
                                    <button class="btn btn-xs btn-danger delete-config-item">X</button>
                                @else
                                    <h2>{{\Partymeister\Accounting\Models\Item::find($item)->name}}</h2>
                                    <button class="btn btn-xs btn-danger delete-config-item">X</button>
                                    <div class="buttons">
                                        <br/>
                                        @if (\Partymeister\Accounting\Models\Item::find($item)->pos_can_book_negative_quantities)
                                            <button data-quantity="-1" class="add-item btn-lg btn-danger">-1</button>
                                            <button data-quantity="-2" class="add-item btn-lg btn-danger">-2</button>
                                        @else
                                            <button data-quantity="1" class="add-item btn-lg btn-success">+1</button>
                                            <button data-quantity="2" class="add-item btn-lg btn-success">+2</button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                </div>
            @endfor
        </div>
        <div class="col-md-3">
            <div class="sales well" style="border: 1px dotted #ccc;">
                <h2 style="text-align: center; vertical-align: middle;">POS Editor</h2>
                <br>
                <p style="text-align: center;">
                    Drag items from the list to the rows on the left side.
                </p>
                <button class="btn btn-sm btn-success save">Save</button>
            </div>

            <div id="draggable" style="height: 80vh; overflow: scroll;">
                <h3>Separator</h3>
                <div class="item well" data-item-id="separator" style="position: relative;">
                    <h2>Separator</h2>
                    <button class="btn btn-xs btn-danger delete-config-item">X</button>
                </div>
                @foreach ($itemTypes as $itemType)
                    <h3>{{$itemType->name}}</h3>
                    @foreach ($itemType->items()->orderBy('sort_position', 'ASC')->get() as $item)
                        <div class="item well" data-item-id="{{$item->id}}" style="position: relative;">
                            <h2>{{$item->name}}</h2>
                            <button class="btn btn-xs btn-danger delete-config-item">X</button>
                            <div class="buttons">
                                <br/>
                                @if ($item->pos_can_book_negative_quantities)
                                    <button data-quantity="-1" class="add-item btn-lg btn-danger">-1</button>
                                    <button data-quantity="-2" class="add-item btn-lg btn-danger">-2</button>
                                @else
                                    <button data-quantity="1" class="add-item btn-lg btn-success">+1</button>
                                    <button data-quantity="2" class="add-item btn-lg btn-success">+2</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>


        </div>
    </div>
</div> <!-- /container -->

</div>

@section('scripts')

    @include('partymeister-accounting::layouts.partials.scripts')

@show

@yield('view_scripts')
<script src="//cdn.jsdelivr.net/npm/sortablejs@1.6.1/Sortable.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        $('button.delete-config-item').click(function(e) {
            $(this).parent().remove();
        });

        $('button.save').click(function(e) {
            var data = {};
            for (i=1; i<=5; i++) {
                var row = [];
                $('#droppable-'+i+' div.item').each(function(index, element) {
                    row.push($(element).data('item-id'));
                });
                data[i] = row;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{route('backend.pos.update', ['account' => $record->id])}}',
                data: {pos_configuration: data},
                type: 'PATCH',
                success: function(response) {
                    $('button.save').html('Saved!');
                    $('button.save').effect('highlight').delay(500);
                    setTimeout(function(){ $('button.save').html('Save'); }, 2000);
                }
            });
        });
    });

    Sortable.create(document.getElementById('droppable-1'), {
        group: 'items',
        draggable: ".item",
        animation: 100
    });

    Sortable.create(document.getElementById('droppable-2'), {
        group: 'items',
        draggable: ".item",
        animation: 100
    });

    Sortable.create(document.getElementById('droppable-3'), {
        group: 'items',
        draggable: ".item",
        animation: 100
    });

    Sortable.create(document.getElementById('droppable-4'), {
        group: 'items',
        draggable: ".item",
        animation: 100
    });

    Sortable.create(document.getElementById('droppable-5'), {
        group: 'items',
        draggable: ".item",
        animation: 100
    });

    Sortable.create(document.getElementById('draggable'), {
        group: {name: 'items', pull: "clone"},
        draggable: ".item",
        animation: 100,
        sort: false,
    });
</script>

</body>
</html>
