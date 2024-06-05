@extends('admin.layout.main')
@section('page-content')
<div class="right_col" role="main" style="min-height: 3755px;">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit {{ ucfirst($page_fields->name) }} Page</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br>
                        <form action="{{ route('pages.update',['page'=>$detail->id]) }}" method="POST" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                            @csrf
                            @method('PUT')

                            @foreach($page_fields->sections as $section)
                            <div class="form-felids-wrap">
                                <h4 class="sec-tittle">{{ ucfirst(str_replace('_',' ',$section->title)) }}</h4>
                                @foreach ($section->fields as $field)
                                  {{-- text --}}
                                  @if($field->type == 'text')
                                        <div class="item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3 label-align">
                                                {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                            </label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" id="{{ ucfirst(str_replace('_',' ',$field->title)) }}" required="required" class="form-control" name="{{ $field->name }}">
                                            </div>
                                        </div>
                                  @endif
                                    {{-- textarea --}}
                                  @if($field->type == 'textarea')
                                  <div class="item form-group">
									<label class="col-form-label col-md-3 col-sm-3 label-align ">{{ ucfirst(str_replace('_',' ',$field->title)) }}</label>
									<div class="col-md-6 col-sm-6">
										<textarea class="resizable_textarea form-control" name="{{ $field->name }}"></textarea>
									</div>
								</div>
                                  @endif
                                 {{-- image --}}
                                @if($field->type == 'image')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="file" id="{{ ucfirst(str_replace('_',' ',$field->title)) }}" required="required" class="form-control" name="{{ $field->name }}">
                                    </div>
                                </div>
                                @endif
                                {{-- images --}}
                                @if($field->type == 'images')
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        {{ ucfirst(str_replace('_',' ',$field->title)) }} <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="file" id="{{ ucfirst(str_replace('_',' ',$field->title)) }}" required="required" class="form-control" name="{{ $field->name }}[]" multiple>
                                    </div>
                                </div>
                                @endif

                                @endforeach
                            </div>
                            @endforeach
                            <button type="submit" class="btn btn-success">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection