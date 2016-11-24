{!! form_start($form) !!}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('motor-backend::backend/global.base_info') }}</h3>
    </div>
    <div class="box-body">
        {!! form_until($form, 'is_manual_booking') !!}
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        {!! form_row($form->submit) !!}
    </div>
</div>
{!! form_end($form) !!}
@section ('view_scripts')
    <script type="text/javascript">
        var convertToPoint = function (value) {
            value = parseFloat(value.toString().replace(',', '.'));
            if (isNaN(value)) {
                value = parseFloat(0);
            }
            return value;
        };
        $('#price_with_vat').change(function () {
            var newValue = parseFloat(convertToPoint($(this).val()) / ((convertToPoint($('#vat_percentage').val()) / 100) + 1)).toFixed(2);
            $('#price_without_vat').val(newValue);
        });
        $('#price_without_vat').change(function () {
            var newValue = parseFloat(convertToPoint($(this).val()) * ((convertToPoint($('#vat_percentage').val()) / 100) + 1)).toFixed(2);
            $('#price_with_vat').val(newValue);
        });
        $('#vat_percentage').change(function () {
            var newValue = parseFloat(convertToPoint($('#price_without_vat').val()) * ((convertToPoint($(this).val()) / 100) + 1)).toFixed(2);
            $('#price_with_vat').val(newValue);
        });
    </script>
@append
