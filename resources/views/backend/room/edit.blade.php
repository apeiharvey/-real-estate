@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Room</h5>
    <div class="card-body">
      <form method="post" action="{{route('room.update',$room->id)}}">
        @csrf 
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Name <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="name" placeholder="Enter Name"  value="{{$room->name}}" class="form-control">
          @error('name')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="house_id">House Type</label>
          <select name="house_id" class="form-control select2">
              <option value="">--Select any house--</option>
              @foreach($houses as $house)
                  <option value='{{$house->id}}' {{(($room->house_id == $house->id ? 'selected' : ''))}}>{{$house->name}}</option>
              @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Images</label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="images" value="{{$room->images}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('images')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active" {{(($room->status=='active')? 'selected' : '')}}>Active</option>
              <option value="inactive" {{(($room->status=='inactive')? 'selected' : '')}}>Inactive</option>
          </select>
          @error('status')
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
    $('#summary').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
<script>
  $('#is_parent').change(function(){
    var is_checked=$('#is_parent').prop('checked');
    // alert(is_checked);
    if(is_checked){
      $('#parent_cat_div').addClass('d-none');
      $('#parent_cat_div').val('');
    }
    else{
      $('#parent_cat_div').removeClass('d-none');
    }
  })
</script>
@endpush