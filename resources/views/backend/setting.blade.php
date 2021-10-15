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
          <label for="inputMaps" class="col-form-label">Maps <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm2" data-input="inputMaps" data-preview="holder_maps" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="inputMaps" class="form-control" type="text" name="photo" value="{{$data->photo}}">
        </div>
        <div id="holder_maps" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="inputMaps2" class="col-form-label">Maps 2<span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm3" data-input="inputMaps2" data-preview="holder_maps2" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Choose
                  </a>
              </span>
          <input id="inputMaps2" class="form-control" type="text" name="maps2" value="{{$data->maps2}}">
        </div>
        <div id="holder_maps2" style="margin-top:15px;max-height:100px;"></div>
          @error('maps2')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <!-- <div class="form-group">
          <label for="address" class="col-form-label">Address <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="address" required value="{{$data->address}}">
          @error('address')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->
        <!-- <div class="form-group">
          <label for="email" class="col-form-label">Email <span class="text-danger">*</span></label>
          <input type="email" class="form-control" name="email" required value="{{$data->email}}">
          @error('email')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>-->
        <!--<div class="form-group">
          <label for="phone" class="col-form-label">Phone Number <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="phone" required value="{{$data->phone}}">
          @error('phone')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->
        <div class="form-group">
          <label for="mobile_phone" class="col-form-label">Mobile Phone <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="mobile_phone" required value="{{$data->mobile_phone}}">
          @error('mobile_phone')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
        <label for="inputPhoto" class="col-form-label">Brochure <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-btn">
                <a id="lfm_file" data-input="brochure" data-preview="brochure" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choose
                </a>
            </span>
          <input id="brochure" class="form-control" type="text" name="brochure" value="{{$data->brochure}}">
        </div>
        <div id="images_thumbnail" style="margin-top:15px;max-height:100px;"></div>
          @error('images_thumbnail')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <!-- <div class="form-group">
          <label for="twitter" class="col-form-label">Twitter</label>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Twitter URL" name="twitter" value="{{$data->twitter}}">
            <input type="text" class="form-control" placeholder="Twitter Username" name="twitter_name" value="{{$data->twitter_name}}">
          </div>
          @error('twitter')
          <span class="text-danger">{{$message}}</span>
          @enderror
          @error('twitter_name')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> -->
        <div class="form-group">
          <label for="phone_4" class="col-form-label">Instagram</label>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Instagram URL" name="instagram" value="{{$data->instagram}}">
            <input type="text" class="form-control" placeholder="Instagram Username" name="instagram_name" value="{{$data->instagram_name}}">
          </div>
          @error('instagram')
          <span class="text-danger">{{$message}}</span>
          @enderror
          @error('instagram_name')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="facebook" class="col-form-label">Facebook</label>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Facebook URL" name="facebook" value="{{$data->facebook}}">
            <input type="text" class="form-control" placeholder="Facebook Username" name="facebook_name" value="{{$data->facebook_name}}">
          </div>
          @error('facebook')
          <span class="text-danger">{{$message}}</span>
          @enderror
          @error('facebook_name')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <!-- <div class="form-group">
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
        </div> -->

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
    $('#lfm1').filemanager('images');
    $('#lfm2').filemanager('images');
    $('#lfm3').filemanager('images');
    $('#lfm_file').filemanager('files');
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