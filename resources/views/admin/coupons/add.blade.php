@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3963px;">
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
                    <input type="hidden" id="vendor_id" name="vendor_id">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="object_type" >Object Type <span class="required">*</span>
                        </label>.
                        <div class="col-md-6 col-sm-6">
                           <select class="form-control" name="object_type">
                               <option value="">Select</option>
                                <option value="0">Amount</option>
                                <option value="1">Percent</option>
                        </select>
                        @error('object_type')
                        <p class="text-danger">{{ $message }}</p>
                       @enderror
                    </div>

                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="code">Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="code" name="code" required="required" class="form-control ">
                            @error('code')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="type">Type<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="type" name="type" required="required" class="form-control ">
                            @error('type')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="amount">Amount <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="amount" name="amount" required="required" class="form-control" step=".01">
                            @error('amount')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="minimum_spend"> Minimum Spend <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="minimum_spend" name="minimum_spend" required="required" class="form-control" step=".01">
                            @error('minimum_spend')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="maximum_spend"> Maximum Spend <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="maximum_spend" name="maximum_spend" required="required" class="form-control" step=".01">
                            @error('maximum_spend')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="start_date">Start Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="date" id="start_date" name="start_date" required="required" class="form-control" step=".01">
                            @error('start_date')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="end_date">End Date<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="date" id="end_date" name="end_date" required="required" class="form-control" step=".01">
                            @error('end_date')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="use_limit"> Use Limit<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="use_limit" name="use_limit" required="required" class="form-control" step=".01">
                            @error('use_limit')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="same_ip_limit">Same IP Limit<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="same_ip_limit" name="same_ip_limit" required="required" class="form-control" step=".01">
                            @error('same_ip_limit')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="use_limit_per_user">Use Limit Per User<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="use_limit_per_user" name="use_limit_per_user" required="required" class="form-control" step=".01">
                            @error('use_limit_per_user')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="use_device">Use Device<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="use_device" name="use_device" required="required" class="form-control" step=".01">
                            @error('use_device')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="multiple_use">Multiple Use<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6">
                            <select class="form-control" id="multiple_use" name="multiple_use" required>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            @error('multiple_use')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="total_use">Total Use<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="total_use" name="total_use" required="required" class="form-control" step=".01">
                            @error('total_use')
                             <p class="text-danger">{{ $message }}</p>
                            @enderror
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

<script>
    CKEDITOR.replace('description');
</script>

@endsection
