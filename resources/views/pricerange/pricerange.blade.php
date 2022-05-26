@extends('admin.admin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<body>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <ul class="breadcrumb">
                    <h4 class="breadcrumb-item"><?=$data['_page']; ?></a></h4>
                </ul>
                
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?= $data['_page']; ?></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{-- <h1>1<?php echo Config::get('constants.OWT_KEY');?></h1> --}}
                <h4 class="card-title mb-3"><?= $data['_title']; ?></h4>
                    <!-- end page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-sm-4">
                                            <div class="search-box me-2 mb-2 d-inline-block">

                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="text-sm-end">
                                                <a type="button" id="insert" data-role="insert" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2" href="#">+Add</i></a>
                                                <button type="submit" class="btn btn-danger btn-rounded waves-effect waves-light mb-2 me-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Delete</button>
                                            </div>
                                        </div><!-- end col-->
                                    </div>

                                    <div class="table-responsive">
                                        <table id="fullTable" class="table align-middle datatable table-nowrap table-check">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 20px;" class="align-middle">
                                                        <div class="form-check font-size-16">
                                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                                            <label class="form-check-label" for="checkAll"></label>
                                                        </div>
                                                    </th>
                                                    <th class="align-middle">No</th>
                                                    <th class="align-middle">Type</th>
                                                    <th class="align-middle">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-data">
                                               
                                                    @foreach($data['list'] as $index => $student)
                                                    
                                               
                                                    <tr data-target="fullRow" id="{{$student->id}}">
                                                        <td data-target="idSection">
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" value="{{$student->id}}" id="orderidcheck01">
                                                                <label class="form-check-label" for="orderidcheck01"></label>
                                                            </div>
                                                        </td>
                                                        <td data-target="idSection2"><a href="javascript: void(0);" id="roleID" class="text-body fw-bold"></a>{{$index+1}} </td>
                                                        <td data-target="roleName">{{$student->range}}</td>
                                                        <td data-target="roleAction">
                                                            <div class="d-flex gap-3">
                                                                <a href="#" id="update" data-role="update" data-id="{{$student->id}}"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                                <a href="#" id="deleteData" data-role="deleteData" data-id="{{$student->id}}" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="mdi mdi-delete font-size-18"></i></a>
                                                                <!-- <button type="submit" class="btn btn-danger btn-rounded waves-effect waves-light mb-2 me-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Delete</button> -->

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Manage Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>From Price </label>
                        <input type="text" id="fromPrice" placeholder="Type" class="form-control" autocomplete="off">
                    </div>
                    <br>
                    <div class="form-group">
                        <label>To Price </label>
                        <input type="text" id="toPrice" placeholder="Type" class="form-control" autocomplete="off">
                    </div>
                    <input type="hidden" id="userId" class="form-control">
                </div>
                <div class="modal-footer">
                    <a href="#" id="save" class="btn btn-primary pull-right">Save</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ###############DeleteSingleModal################ -->
<div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delte Property Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="url" value="" />
                <p>Are you sure you want to delete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="delete_single_row" class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- ###############OverDeleteSingleModal################ -->

<!-- ########DeleteMultipleModal############## -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="url" value="" />
                <p>Are you sure you want to delete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" id="confirm_btn" class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</div>
<!-- ###########DeleteMultipleModelOver################# -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>

        $(document).on('click','a[data-role=insert]',function(){
            id = undefined;
            console.log("clicked");
            var addRoleName = $('#roleName').children('td[data-target=roleName]').text();
            $('#fromPrice').val('');
            $('#toPrice').val('');
            $('#userId').val('');
            $('#exampleModalLabel').html('Add Property Type');

            $('#exampleModal').modal('toggle');
        });

        $(document).on('click','a[data-role=update]',function(){

            var id = $(this).data('id');
            console.log("clicked" + id);
            var roleName = $('#'+id).children('td[data-target=roleName]').text();
            console.log(roleName);
            $('#roleName').val(roleName);
            $('#userId').val(id);
            id  += "";
            $('#exampleModalLabel').html('Edit Property Type');

            $('#exampleModal').modal('toggle');

        });

        $('#save').click(function(){
            console.log("clicked save button");
            var id = $('#userId').val();
            var roleName = $('#roleName').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url : "/manageType",
                method : "post",
                data : {roleName : roleName, id: id},
                success : function(response){
                    console.log(response);
                    
                    if(response == "Role Field Is Requered!")
                    {
                        
                    }
                    else if(response == "data updated"){
                        console.log(response);
                        $('#'+id).children('td[data-target=roleName]').text(roleName);                                              
                        $('#exampleModal').modal('toggle');
                        location.reload();                                  
                    }
                    else {
                        
                        $('#exampleModal').modal('toggle');                       
                        $( "#fullTable" ).load( "index #fullTable" );
                        location.reload();
                    }
                }

            });

        });

        $(document).on('click','a[data-role=deleteData]',function(){
            var id = $(this).data('id');
            console.log("clicked" + id);

            $("#delete_single_row").on('click', function(){

                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : "/deleteType",
                    method : "post",
                    data : {id: id},
                    success : function(response){
                        $("#" + id).remove();
                        $('#deleteModal').modal('toggle');
                        location.reload();
                        // $( "#fullTable" ).load( "index #fullTable" );
                    }
                });

            });
        });

        $("#confirm_btn").on('click', function(){		
        var id = [];

            $(":checkbox:checked").each(function(key){
                id[key] = $(this).val();
            });

            console.log(id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                url : "/deleteMultiple",
                method : "post",
                data : {id : id},
                success : function(response){
                    for(let x of id){
                    $("#" + x).remove();
                    }
                    console.log(response);
                    $('#staticBackdrop').modal('toggle');
                    location.reload();

                }
            });

	    });

        $('#fromPrice').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode;
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
        });  
        $('#toPrice').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode;
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
        });  
    </script>

@endsection
