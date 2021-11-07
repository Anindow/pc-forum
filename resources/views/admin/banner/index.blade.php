@extends('admin.layouts.app')
@section('title')
    @lang('trans.banner')
@endsection

@push('style')
    <style>
        .banner-image {
            height: 50px;
            width: 100px;
        }
    </style>
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_banner')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.banner')</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row no-gutters">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title float-left">@lang('trans.all_banners')
                        </h3>
                        <button type="button"
                                data-toggle="modal"
                                data-target="#createBannerModal"
                                class="btn btn-info float-right btn-sm">
                            <i class="fa fa-plus"></i> @lang('trans.add_new')
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="bannerTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 10%">No</th>
                                <th style="width: 40%">Image</th>
                                <th style="width: 30%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($banners as $banner)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        <img
                                            src="{{getImage(imagePath()['banner']['path'] . '/' . $banner->image, imagePath()['banner']['size'])}}"
                                            alt="Banner image" class="banner-image">
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('access-setting')
                                           onclick="bannerStatusChange('{{$banner->id}}')"
                                           @endcan
                                           data-href="{{route('banners.status.update', $banner->id)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="bannerStatus-{{$banner->id}}"
                                        >
                                            <span
                                                class="badge {{$banner->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$banner->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>


                                        {{Form::open(['route'=>['banners.destroy', $banner->id], 'method'=>'DELETE', 'id' => "deleteForm-$banner->id", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-setting')
                                                onclick="deleteBtn('{{$banner->id}}')"
                                                @endcan
                                                class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                title="@lang('trans.delete')"><i class="fa fa-trash"></i></button>
                                        {{Form::close()}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <div class="modal fade" id="createBannerModal" tabindex="-1" aria-labelledby="createBannerModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createBannerModalLabel">@lang('trans.create_banner')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{Form::open(['route' => 'banners.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'bannerCreateForm', 'enctype' => 'multipart/form-data'])}}
                    <div class="modal-body">

                        <div class="form-group row no-gutters">
                            <label for="image" class="col-sm-2 col-form-label" id="inputGroupFileAddon05">Image</label>
                            <div class="col-sm-10">
                                <div class="custom-file">
                                    <input type="file" name="image"
                                           class="custom-file-input @error('name') image @enderror"
                                           accept="image/jpeg, image/png" id="image"
                                           aria-describedby="inputGroupFileAddon05">
                                    <label class="custom-file-label" for="image">Choose Image</label>
                                </div>
                                @error('image')<span class="text-danger">{{$errors->first('image')}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row no-gutters">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <input type="checkbox" id="status" checked name="status" data-bootstrap-switch
                                       data-off-color="danger" data-on-color="info">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-sm btn-info">Create</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('script')
    <script>
        // all Shops in json format
        let banners = @json($banners);
        {{--let brandImagePath = "{{imagePath()['brand']['path']}}";--}}

        $(document).ready(function () {
            //datatable
            $("#bannerTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [3]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // shop create form
            $('#bannerCreateForm').validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter a shop name",
                    },
                    terms: "Please accept our terms"
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

        });


        // Functions


        // Delete Shop
        function deleteBtn(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $('#deleteForm-' + id).submit();
                }
            });
        }


        // Shop Status Change
        function bannerStatusChange(id) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#bannerStatus-' + id).data('href');
                }
            });
        }
    </script>
@endpush
