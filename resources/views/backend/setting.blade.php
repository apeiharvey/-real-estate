@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Post</h5>
    <div class="card-body">
    <form method="post" action="{{route('settings.update')}}">
        @csrf 
        {{-- @method('PATCH') --}}

        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Logo <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm1" data-input="thumbnail1" data-preview="holder1" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="thumbnail1" class="form-control" type="text" name="logo" value="{{$data->logo}}">
        </div>
        <div id="holder1" style="margin-top:15px;max-height:100px;"></div>

          @error('logo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="address" class="col-form-label">Address <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="address" required value="{{$data->address}}">
          @error('address')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="email" class="col-form-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control" name="email" required value="{{$data->email}}">
          @error('email')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="phone" class="col-form-label">Phone Number <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="phone" required value="{{$data->phone}}">
          @error('phone')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="twitter" class="col-form-label">Twitter</label>
          <input type="text" class="form-control" name="twitter" value="{{$data->twitter}}">
          @error('twitter')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="tiktok" class="col-form-label">Tiktok</label>
          <input type="text" class="form-control" name="tiktok" value="{{$data->tiktok}}">
          @error('tiktok')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="phone_4" class="col-form-label">Instagram</label>
          <input type="text" class="form-control" name="instagram" value="{{$data->instagram}}">
          @error('instagram')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="facebook" class="col-form-label">Facebook</label>
          <input type="text" class="form-control" name="facebook" value="{{$data->facebook}}">
          @error('facebook')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="long" class="col-form-label">Longitude</label>
          <input type="text" class="form-control" name="long" value="{{$data->long}}">
          @error('long')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="lat" class="col-form-label">Latitude</label>
          <input type="text" class="form-control" name="lat" value="{{$data->lat}}">
          @error('lat')
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

@endpush
@push('scripts')
<script src="{{asset('backend/vendor/laravel-filemanager/js/stand-alone-button.js')}}"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');
    $('#lfm1').filemanager('image');
    $(document).ready(function() {
    $('#summary').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });

    $(document).ready(function() {
      $('#quote').summernote({
        placeholder: "Write short Quote.....",
          tabsize: 2,
          height: 100
      });
    });
    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Write detail description.....",
          tabsize: 2,
          height: 150
      });
    });
</script>
@endpush