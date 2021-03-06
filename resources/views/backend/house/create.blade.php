@extends('backend.layouts.master')

@section('title','HIVE COMMERCIAL || Unit Type Create')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Unit Type</h5>
    <div class="card-body">
      <form method="post" action="{{route('unit-type.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Name <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="name" placeholder="Enter Name"  value="{{old('name')}}" class="form-control">
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <!-- <div class="form-group">
          <label for="inputDesc" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->

        <div class="form-group">
        <label for="inputPhoto" class="col-form-label">Thumbnail <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-btn">
                <a id="lfm" data-input="thumbnail" data-preview="image_thumbnail" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
                </a>
            </span>
          <input id="thumbnail" class="form-control" type="text" name="images_thumbnail" value="{{old('images_thumbnail')}}">
        </div>
        <div id="image_thumbnail" style="margin-top:15px;max-height:100px;"></div>
          @error('images_thumbnail')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
        <label for="inputPhoto" class="col-form-label">Detail <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-btn">
                <a id="lfm_detail" data-input="images_detail" data-preview="holder" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
                </a>
            </span>
          <input id="images_detail" class="form-control" type="text" name="images_detail" value="{{old('images_detail')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('images_detail')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Price<span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Enter Price"  value="{{old('price')}}" class="form-control">
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <!-- <div class="form-group">
          <label for="area_building" class="col-form-label">Area Building<span class="text-danger">*</span></label>
          <input id="area_building" type="number" name="area_building" placeholder="Enter Building Area"  value="{{old('area_building')}}" class="form-control">
          @error('area_building')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="area_surface" class="col-form-label">Surface Area<span class="text-danger">*</span></label>
          <input id="area_surface" type="number" name="area_surface" placeholder="Enter Surface Area"  value="{{old('area_surface')}}" class="form-control">
          @error('area_surface')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="bathroom" class="col-form-label">Bathroom <span class="text-danger">*</span></label>
          <input id="bathroom" type="number" name="bathroom" placeholder="Enter Bathroom"  value="{{old('bathroom')}}" class="form-control">
          @error('bathroom')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="bedroom" class="col-form-label">Bedroom <span class="text-danger">*</span></label>
          <input id="bedroom" type="number" name="bedroom" placeholder="Enter Bedroom"  value="{{old('bedroom')}}" class="form-control">
          @error('bedroom')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="floor" class="col-form-label">Floor <span class="text-danger">*</span></label>
          <input id="floor" type="number" name="floor" placeholder="Enter Floor"  value="{{old('floor')}}" class="form-control">
          @error('floor')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="{{asset('backend/vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $('#lfm_detail').filemanager('image');

    $(document).ready(function() {
      $('#description').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush