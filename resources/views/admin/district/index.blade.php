@extends('admin.layouts.app')
@section('title')
    District
@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Manage Districts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">District</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row no-gutters">
            <div class="col-md-8 offset-md-2 ">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title float-left">All Districts</h3>
                    </div>
                    <div class="card-body">
                        <table id="DistrictTable" class="table text-center table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Name</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @forelse($districts as $district)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$district->name}}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           onclick="statusChange('{{$district->id}}')"
                                           data-toggle="tooltip"
                                           data-href="{{route('districts.status-update', $district->id)}}"
                                           title="@lang('trans.change_status')"
                                           id="districtStatus-{{$district->id}}"
                                        >
                                            <span class="badge {{$district->status == 1 ? 'badge-success' : 'badge-danger'}}">
                                            {{$district->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('script')
    <script>
        // all Districts in json format
        {{--let district = @json($district);--}}

        $(document).ready(function () {

            $("#DistrictTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [2]}
                ],
                "pageLength": {{settings('per_page')}}
            });

        });

        function statusChange(id) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#districtStatus-' + id).data('href');
                }
            });
        }
    </script>
@endpush
