@extends('layouts.theme')
@section('title','Manage Promo Codes')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #errorMessage{
            color: red;
        }
    </style>
@endsection
@section('content')

    <div class="box">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('alert')

                    <h3 class="box-title">Promo  codes</h3>
                    <div class="text text-right">

                        <a href="#" data-toggle="modal" data-target="#addpromoModal" class="btn btn-primary btn-flat"
                           title="Add new Promo code!"><i class="fa fa-plus"></i> Add Promo</a>

                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive" style="margin-top:10px;">

                        <table class="table table-bordered productsTable" style="width: 100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Promo Code</th>
                                <th>Discount Value</th>
                                <th>Created Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                {{--                                    @if (Auth::user()->hasAnyPermission(['Create PromoCode','Update PromoCode','Status PromoCode','Delete PromoCode']) || Auth::user()->hasRole('Super Admin'))--}}

                                <th>Actions</th>
                                {{--                                    @endif--}}
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
    </div>

    @include('promo.inc.add')
    @include('promo.inc.edit')
@endsection

@section('script')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>

    <script data-require="datatables-responsive@*" data-semver="2.1.0"
            src="//cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        var url = "{{ route('promos.index') }}";
        var baseUrl = "{{ url('/') }}";
    </script>
    <script src="{{ asset('js/promo/list.js') }}"></script>
@endsection



