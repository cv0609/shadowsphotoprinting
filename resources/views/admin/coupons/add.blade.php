@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('coupons-list') }}">Coupons</a></li>
          <li class="breadcrumb-item"><a href="#">Add Coupon</a></li>
        </ol>
    </nav>
<div class="">
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Add Coupon</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form action="{{ route('coupon-save') }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate="">
                    @csrf
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="coupon_type" >Coupon Type <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" name="coupon_type">
                               <option value="">Select</option>
                                <option value="0">Amount</option>
                                <option value="1">Percent</option>
                            </select>
                            @error('coupon_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="code">Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required="required" class="form-control ">
                            @error('code')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="amount">Amount <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="amount" value="{{ old('amount') }}" name="amount" required="required" class="form-control" step=".01">
                            @error('amount')
                             <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @if(Session::has('percent_error'))
                             <span class="text-danger">{{ Session::get('percent_error') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="minimum_spend"> Minimum Spend <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="minimum_spend" value="{{ old('minimum_spend') }}" name="minimum_spend" required="required" class="form-control" step=".01">
                            @error('minimum_spend')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                           @if(Session::has('minimum_amount'))
                           <span class="text-danger">{{ Session::get('minimum_amount') }}</span>
                           @endif
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="maximum_spend"> Maximum Spend <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="maximum_spend" name="maximum_spend" value="{{ old('maximum_spend') }}"  required="required" class="form-control" step=".01">
                            @error('maximum_spend')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="start_date">Start Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required="required" class="form-control inputDate" step=".01">
                            @error('start_date')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="end_date">End Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required="required" class="form-control inputDate" step=".01">
                            @error('end_date')
                            <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                    </div>

                    <div class="item form-group ">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="products" >Only Category<span ></span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control " id="optcatelist" name="product_category[]" multiple="multiple">
                                @foreach ($ProductCategory as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('products')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group ">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="products" >Only Products<span ></span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control " id="optlist" name="products[]" multiple="multiple">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->product_title }}</option>
                                @endforeach
                            </select>
                            @error('products')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group ">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="products" >Bulk Quantity<span ></span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" class="form-control inputDate" name="bulk_qty" placeholder="Quantity">
                            @error('bulk_qty')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group ">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="products" >Usage Limit<span ></span>
                        </label>
                        <div class="col-md-6 col-sm-6">
                            <input type="number" class="form-control inputDate" name="use_limit" placeholder="Usage Limit">
                            @error('products')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="item form-group control-label">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="auto_applied">Automatic coupon applied<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                           <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="auto_applied"> Automatic coupon applied
                            </label>

                           </div>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@section('custom-script')
<script>
    $(function(){
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;

        $('.inputDate').attr('min', maxDate);
    });
    </script>


<script>
      $(document).ready(function() {
        $('#optlist').select2({
            placeholder: 'Select products',
            allowClear: true
        });
    });

    $(document).ready(function() {
        $('#optcatelist').select2({
            placeholder: 'Select product category',
            allowClear: true
        });
    });
</script>
@endsection
