@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-accounting::backend/account_types.account_types') }}
    @if (has_permission('account_types.write'))
	    {!! link_to_route('backend.account_types.create', trans('partymeister-accounting::backend/account_types.new'), [], ['class' => 'pull-right btn btn-sm btn-success']) !!}
    @endif
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header">
            @include('motor-backend::layouts.partials.search')
        </div>
        <!-- /.box-header -->
        @if (isset($grid))
            @include('motor-backend::grid.table')
        @endif
    </div>
@endsection

@section('view_scripts')
    <script type="text/javascript">
        $('.delete-record').click(function (e) {
            if (!confirm('{{ trans('motor-backend::backend/global.delete_question') }}')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection