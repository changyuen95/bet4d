
<x-app-layout>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="py-10">
                    <h1>Admins</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Create Admin</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if ($errors->any())
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-danger alert-dismissable">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    @if (Session::has('error'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissable">
                <ul>
                    <li>{{ Session::get('error') }}</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
    <section class="content">
        <div class="card card-primary">
            <div class="card-header bg-navy">
            <h3 class="card-title"><i class="fas fa-user-shield"></i> Create Admin</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                title="Collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
            </div>

        <div class="card-body">
        <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    
                    <form action="{{route('admin.admins.store')}}" class="form-horizontal" method="POST"  enctype="multipart/form-data">
                        @csrf
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Admin Details
                        </div>
                        <div class="panel-body">
                            {{-- {!! Form::open(["method"=>"POST", "id" => "sku_form", "name" => "sku_form", "class" => "form-horizontal", "url"=>url('product/sku/store/'.$product->id)]) !!} --}}
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Name <span class="red">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Email <span class="red">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Password <span class="red">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Confirm Password <span class="red">*</span></label>
                                    <div class="col-sm-5">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Role <span class="red">*</span><i class="fa fa-info-circle" aria-hidden="true" data-html="true" data-toggle="tooltip" data-placement="right" title="Super Admin -> Full Access to all company &#013; Admin - editor -> can view/edit all data from own company &#013; Admin - Viewer -> can view all data from own company"></i></label>
                                    <div class="col-sm-5">
                                        <select id ="role" name="role" class="form-control @error('role') is-invalid @enderror">
                                            <option value="">-- Please choose Role --</option>
                                            <option value="superadmin" {{old('role') == 'superadmin' ? 'selected' : ''}}>Super Admin</option>
                                            <option value="editor" {{old('role') == 'editor' ? 'selected' : ''}}>Admin - editor</option>
                                            <option value="viewer" {{old('role') == 'viewer' ? 'selected' : ''}}>Admin - viewer</option>
                                            <option value="challenger_admin" {{old('role') == 'challenger_admin' ? 'selected' : ''}}>Challenger - admin</option>
                                            <option value="challenger_admin_comment" {{old('role') == 'challenger_admin_comment' ? 'selected' : ''}}>Challenger - admin (Comment)</option>
                                        </select>
                                    </div>
									@error('role')
									<span class="invalid-feedback d-block" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>

                                <div id="multi_company_div" class="form-group">
                                    <label  class="col-sm-2 control-label">Company <span class="red">*</span></label>
                                    <div class="col-sm-5">
                                        <select class="form-control selectpicker @error('company_name') is-invalid @enderror"
                                            name="company_name[]" style="width: 100%;" data-placeholder="Select Company.."
                                            data-live-search="true" required multiple>
                                            {{-- <option value=""></option> --}}
                                            {{-- @forelse($companies as $company)
                                            <option data-content="<span class='badge'>{{$company->name}}</span>" value="{{$company->id}}"
                                                {{old('company_name') == $company->id ? 'selected' : ''}}>{{$company->name}}
                                            </option>
                                            @empty
                                            @endforelse --}}
                                        </select>
                                    </div>
                                    @error('company_name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>



                                <div class="form-group">
									<label for="customFile" class="col-sm-2">Image <i class="fa fa-info-circle" aria-hidden="true" data-html="true" data-toggle="tooltip" data-placement="right" title="Supported Formats<br>Image - jpg, jpeg, gif, png, bmp"></i></label>
									<br>
                                    <div class="custom-file col-sm-5">
										<input type="file" id="file-upload" name="file"
											class="custom-file-input @error('file') is-invalid @enderror"
											accept=".jpg,.jpeg,.png">
										<label id="filename" class="custom-file-label" for="customFile">Choose file (Max File Size:5 MB)</label>
									</div>
                                    <br>
									<small><b>NOTE: <b><i>Recommend Size 800 x 800 (px)</i></small>
									@error('file')
									<span class="invalid-feedback d-block" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								<div class="form-group col-sm-2 control-label">
									<div class="d-flex flex-wrap">
										<div class="col-md-6 text-left">
											<div class="preview_container" style="display: none">
												<div id="image-preview"></div>
												<div class="text-center">
													<button type="button" class="upload_image_link btn btn-success">Use
														this</button>
												</div>
											</div>
											<div id="uploaded_image">
												{{-- <img id="updated_image" src="" alt="" width="160px"> --}}
											</div>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary bg-navy"><span class="glyphicon glyphicon-send"></span> Submit</button>
                    </form>
                </div>
                <!-- /.col-lg-8 -->
            </div>
            <!-- /.row -->
        </div>
    </div>
    </section>
<!-- /#page-wrapper -->

<script>
    $(document).ready(function(){




        document.getElementById('file-upload').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var fileReader = new FileReader();
            if (file.type.match('image')) {
                fileReader.onload = function() {
                var img = document.createElement('img');
                img.src = fileReader.result;
                img.style.width = "100%";
                $('#preview-container').addClass('d-flex').removeClass('d-none');
                document.getElementById('uploaded_image').innerHTML = '';
                document.getElementById('uploaded_image').appendChild(img);
                // document.getElementsByTagName('div')[0].appendChild(img);
                };
                fileReader.readAsDataURL(file);
			// } else if(file.type.match('pdf')) {
        		// Note: Disable this if enable PDF format for Library
				// swal.fire("Error!", "PDF Format is not supported for Library", "error");

				// Note: Enable this if enable PDF format for Library
                // var img = document.createElement('img');
                // img.src = "{{ asset('images/pdf_preview.png') }}";
                // img.style.width = "50px";
				// $('#preview-container').addClass('d-flex').removeClass('d-none');
                // document.getElementById('uploaded_image').innerHTML = '';
                // document.getElementById('uploaded_image').appendChild(img);
            } else {
                fileReader.onload = function() {
                var blob = new Blob([fileReader.result], {type: file.type});
                var url = URL.createObjectURL(blob);
                var video = document.createElement('video');
                var timeupdate = function() {
                    if (snapImage()) {
                    video.removeEventListener('timeupdate', timeupdate);
                    video.pause();
                    }
                };
                video.addEventListener('loadeddata', function() {
                    if (snapImage()) {
                    video.removeEventListener('timeupdate', timeupdate);
                    }
                });
                var snapImage = function() {
                    var canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                    var image = canvas.toDataURL();
                    var success = image.length > 100000;
                    if (success) {
                    var img = document.createElement('img');
                    img.src = image;
                    img.style.width = "100%";
					$('#preview-container').addClass('d-flex').removeClass('d-none');
					document.getElementById('uploaded_image').innerHTML = '';
					document.getElementById('uploaded_image').appendChild(img);
						// document.getElementsByTagName('div')[0].appendChild(img);
						URL.revokeObjectURL(url);
						}
						return success;
                };
                video.addEventListener('timeupdate', timeupdate);
                video.preload = 'metadata';
                video.src = url;
                // Load video in Safari / IE11
                video.muted = true;
                video.playsInline = true;
                video.play();
                };
                fileReader.readAsArrayBuffer(file);
            }
        });

    });
</script>
</x-app-layout>