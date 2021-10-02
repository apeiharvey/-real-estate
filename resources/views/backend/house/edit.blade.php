@extends('backend.layouts.master')
@section('title','E-SHOP || House Edit')
@section('main-content')

<div class="card">
    <h5 class="card-header">Edit House</h5>
    <div class="card-body">
      <form method="post" action="{{route('house.update',$house->id)}}">
        @csrf 
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Name <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="name" placeholder="Enter title"  value="{{$house->name}}" class="form-control">
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="inputDesc" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{$house->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
        <label for="inputPhoto" class="col-form-label">Thumbnail <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-btn">
                <a id="lfm" data-input="thumbnail" data-preview="images_thumbnail" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
                </a>
            </span>
          <input id="thumbnail" class="form-control" type="text" name="images_thumbnail" value="{{$house->images_thumbnail}}">
        </div>
        <div id="images_thumbnail" style="margin-top:15px;max-height:100px;"></div>
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
          <input id="images_detail" class="form-control" type="text" name="images_detail" value="{{$house->images_detail}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('images_detail')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="active" {{(($house->status=='active') ? 'selected' : '')}}>Active</option>
            <option value="inactive" {{(($house->status=='inactive') ? 'selected' : '')}}>Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="area" class="col-form-label">Area <span class="text-danger">*</span></label>
          <input id="area" type="number" name="area" placeholder="Enter Area"  value="{{$house->area}}" class="form-control">
          @error('area')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="bathroom" class="col-form-label">Bathroom <span class="text-danger">*</span></label>
          <input id="bathroom" type="number" name="bathroom" placeholder="Enter Bathroom"  value="{{$house->bathroom}}" class="form-control">
          @error('bathroom')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="bedroom" class="col-form-label">Bedroom <span class="text-danger">*</span></label>
          <input id="bedroom" type="number" name="bedroom" placeholder="Enter Bedroom"  value="{{$house->bedroom}}" class="form-control">
          @error('bedroom')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="floor" class="col-form-label">Floor <span class="text-danger">*</span></label>
          <input id="floor" type="number" name="floor" placeholder="Enter Floor"  value="{{$house->floor}}" class="form-control">
          @error('floor')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
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

    $(document).ready(function() {
      $('#description').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush