@extends('layouts.theme')
@section('title','Marketing')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{url('plugins/select2/select2.min.css')}}">


@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('alert')

                    <h3 class="box-title">Promotion SMS</h3>
                </div>
                <div class="box-body">
                    <form  id="form" method="post" action="{{url('marketing')}}" enctype="multipart/form-data">
                        @csrf


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customers Listing</label>
                                    <select class="form-control select2" name="customers[]"
                                            multiple="multiple" >
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ ucfirst($customer->first_name)  }}{{$customer->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">

                                    <input type="checkbox" class="minimal" name="all_customer"  value="1" @if(old('all_customer'))checked @endif> <label class="control-label" >You want to send All customer</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" name="subject" class="form-control" placeholder="Subject for email">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="message"
                                              rows="10" class="form-control" placeholder="Write message here..." required>{{old('message')}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="sms" value="SMS" name="sms" >
                                    <label for="sms">SMS</label>
                                </div>
                                &nbsp; &nbsp;

                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="email" value="email" name="email" >
                                    <label for="email">Email</label>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-flat text-white active pull-right">Save</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')


    <!-- Select2 -->
    <script src="{{url('plugins/select2/select2.full.min.js')}}"></script>

<script>
    $('.select2').select2({
        placeholder: 'Select a customer for marketing',
        allowClear: true
    });
</script>
@endsection
