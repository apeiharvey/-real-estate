@extends('backend.layouts.master')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Mortgage Lists</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($mortgage)>0)
        <table class="table table-bordered" id="banner-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>S.N.</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>House</th>
              <th>Payment</th>
              <th>Date Added</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>S.N.</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>House</th>
              <th>Payment</th>
              <th>Date Added</th>
            </tr>
          </tfoot>
          <tbody>
           
            @foreach($mortgage as $mort)
                <tr>
                    <td>{{$mort->id}}</td>
                    <td>{{$mort->name}}</td>
                    <td>{{$mort->phone_number}}</td>
                    <td>{{$mort->email}}</td>
                    <td>{{$mort->house_name}}</td>
                    <td>
                        Payment : {{$mort->payment}}
                        <br>
                        @if(isset($mort->time_period))
                        Time Period : {{$mort->time_period}} Years
                        @endif
                    </td>
                    <td>{{$mort->created_at}}</td>
                </tr>  
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$mortgage->links()}}</span>
        @else
          <h6 class="text-center">No User Mortgage found!!! Please create User Mortgage</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>
      
      $('#banner-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[3,4,5],
                    "order": [[ 0, "desc" ]]
                }
            ]
        } );

        // Sweet alert

        function deleteData(id){
            
        }
  </script>
  <script>
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          $('.dltBtn').click(function(e){
            var form=$(this).closest('form');
              var dataID=$(this).data('id');
              // alert(dataID);
              e.preventDefault();
              swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                       form.submit();
                    } else {
                        swal("Your data is safe!");
                    }
                });
          })
      })
  </script>
@endpush