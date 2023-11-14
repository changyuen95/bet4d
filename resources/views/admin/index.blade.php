
@extends('admin.layouts.app')
@section("content")

<div class="content-wrapper">
        <section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
                        <h1>Manage Admin</h1>
                        @php
                            echo Auth::user()->email;
                        @endphp
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
							<li class="breadcrumb-item active">Admin</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
        <!-- /.row -->

        @if(Session::has('success'))
        <div class="alert alert-success">{!! Session::get('success') !!}</div>
        @endif
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header bg-navy">
                        <h3 class="card-title"><i class="fas fa-user-shield"></i> Admin List</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        </div>

                    <div class="card-body">


                        <div class="visible-md-block visible-lg-block">
                            @if (Auth::user()->role == 'superadmin')

                                    <a href="{{ route('admins.create') }}" type="button" class="btn btn-primary bg-navy">
                                        <span class="glyphicon glyphicon-plus"></span>&nbsp;Add
                                    </a>
                                    <div style="margin-top:8px;"></div>
                            @endif
                        </div>

                        <table width="100%" class="table table-striped table-bordered dataTable no-footer dtr-inline" id="dataTables-history" role="grid" aria-describedby="dataTables-example_info">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Company</th>
                                    {{-- <th>Created At</th>
                                    <th>Created By</th>
                                    <th>Last Updated At</th> --}}
                                    @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'editor')
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $key => $value)
                                <?php
                                    if($value->role == 'challenger_admin')
                                    {
                                        $roleintext = 'Challenger Admin';

                                    }elseif($value->role == 'viewer')
                                    {
                                        $roleintext = 'Viewer';

                                    }elseif($value->role == "editor")
                                    {
                                        $roleintext = 'Editor';

                                    }elseif($value->role == "superadmin")
                                    {
                                        $roleintext = "Super Admin";

                                    }elseif($value->role == "challenger_admin_comment")
                                    {
                                        $roleintext = "Challenger Admin (Comment)";
                                    }
                                    ?>
                                <tr>
                                    <td>{!! $key+1 !!}</td>
                                    <td>{!! $value->name ?? '' !!}</td>
                                    <td>{!! $value->email ?? '' !!}</td>
                                    <td>{!! $roleintext ?? '' !!}</td>
                                    <td>{!! $value->company_name ?? '-' !!}</td>
                                    {{-- <td>{!! $value->mobile_no ?? "" !!}</td> --}}
                                    {{-- <td>{!! ucwords($value->status) !!}</td> --}}
                                    {{-- <td>{!! $value->created_at !!}</td>
                                    <td>{!! $value->creator->name ?? '-' !!}</td>
                                    <td>{!! $value->lastUpdate->name ?? '-' !!}</td> --}}
                                    @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'editor')
                                    <td>

                                        @if(Auth::user()->name != $value->name)
                                        <a class="btn btn-primary" href="{{ route('admins.show', ['admin'=>$value->id]) }}"><i class="fa fa-search" aria-hidden="true"></i></a>
                                        <a class="btn btn-warning" href="{{ route('admins.edit', ['admin'=>$value->id]) }}"><i class="fa fa-edit text-white" aria-hidden="true"></i></a>
                                        {{-- <a class="btn btn-primary" href="{{ route('admins.destroy', ["admin"=>$value->id]) }}" class="btn btn-danger delete-btn"><i class="fa fa-trash" aria-hidden="true"></i></a> --}}
                                        <button data-id="{{ $value->id }}" class="btn btn-danger delete-btn delete_link" href="{{ route('admins.destroy', ['admin'=>$value->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        @endif

                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.col-lg-8 -->
            </div>
            <!-- /.row -->
        </div>
    </section>
<!-- /#page-wrapper -->

@section('script')
<script>
	$( document ).ready(function() {

        $('.dataTables').DataTable({
'stateSave': true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf','print'
            ],
            responsive: true
        });

        $('#dataTables-history').DataTable({
'stateSave': true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf','print'
            ],
            responsive: true
        });

        $("#export").click(function () {
            $("#form_export").submit();
        });

        $("#paging").change(function () {
            $("#loading_panel").addClass("loading-show");
        });

        $("ul.pagination>li").click(function () {
            if (!$(this).hasClass("disabled")) {
                $("#loading_panel").addClass("loading-show");
            }
        });

        $(".sort, .sort_asc, .sort_desc").click(function () {
            if ($(this).attr("value") != "")
            {
                $("#sort").val($(this).attr("value"));
                if ($(this).hasClass("sort_asc")) {
                    $("#loading_panel").addClass("loading-show");
                    $("#sort_order").val("desc");
                } else {
                    $("#loading_panel").addClass("loading-show");
                    $("#sort_order").val("asc");
                }
                $("#form_search").submit();
            }
        });
        $(document).on('click', '.trigger-modal', function (e) {
            e.preventDefault();
            var source = $(this).attr('url');
            var title =$(this).attr('title');

            if (source != 'undefined') {
                $('#bigger-image').attr('src', source);
                $('#exampleModal').modal('show');
                $('.modal-title').text(title);
            }

    })
    });

    $(document).ready(function(){
        $(document).on('click', '.delete_link', function(){
            let id = $(this).data('id');
            let url = "{{ route('admins.destroy', ['admin'=>'placeholder']) }}";
            url = url.replace('placeholder',id);

            Swal.fire({
                title: 'Are you sure?',
                html: '<i style="color:red">Admin will be deleted permanently!</i>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        method: "post",
                        data: {
                            _method: "DELETE",
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response){
                            if(response.success){

                                Swal.fire(
                                'Deleted!',
                                'Admin has been deleted.',
                                'success'
                                ).then(function(){
                                    window.location.reload(true);
                                })
                            }
                        }
                    });

                // For more information about handling dismissals please visit
                // https://sweetalert2.github.io/#handling-dismissals
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                    )
                }
            })
        });
    })
</script>
@endsection
@stop

