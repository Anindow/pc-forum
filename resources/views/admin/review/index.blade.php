@extends('admin.layouts.app')
@section('title')
    @lang('trans.review')
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row no-gutters mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('trans.manage_review')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">@lang('trans.dashboard')</a></li>
                        <li class="breadcrumb-item active">@lang('trans.review')</li>
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
                        <h3 class="card-title float-left">@lang('trans.all_reviews')</h3>
                    </div>
                    <div class="card-body">
                        <table id="reviewTable" class="table text-center table-bordered ">
                            <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 15%">Product Name</th>
                                <th style="width: 15%">User Name</th>
                                <th style="width: 30%">Description</th>
                                <th style="width: 10%">Rating</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i = 1)
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>
                                        @can('access-product-link')
                                            <a class="list-style-none text-dark" href="{{route('products.edit', $review->product->slug)}}">{{substr(strip_tags($review->product->name),0,60)."..."}}</a>
                                        @else
                                            {{substr(strip_tags($review->product->name),0,60)."..."}}
                                        @endcan
                                    </td>
                                    <td>{{@$review->user->full_name}}</td>
                                    <td>{{substr(strip_tags($review->description),0,100)."..."}}</td>
                                    <td>
                                        @foreach(range(1,5) as $i)
                                            @if($i > $review->rating)
                                                    <i class="fa fa-star"></i>
                                            @else
                                                <i class="fa fa-star text-success"></i>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                           @can('access-review')
                                           onclick="reviewStatusChange('{{$review->id}}')"
                                           @endcan
                                           data-href="{{route('reviews.status.update', $review->id)}}"
                                           data-toggle="tooltip"
                                           title="@lang('trans.change_status')"
                                           id="reviewStatus-{{$review->id}}"
                                        >
                                            <span
                                                class="badge {{$review->status == 1 ? 'badge-success' : 'badge-warning'}}">
                                            {{$review->status == 1 ? 'Approved' : 'Pending' }}
                                        </span>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="openShowReviewModal('{{$review->id}}')" type="button"
                                                class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('trans.view')"><i class="fa fa-search-plus"></i></button>

                                        {{Form::open(['route'=>['reviews.destroy', $review->id], 'method'=>'DELETE', 'id' => "deleteForm-$review->id", 'class' => 'd-inline'])}}
                                        <button type="button"
                                                @can('access-review')
                                                onclick="deleteBtn('{{$review->id}}')"
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

        <div class="modal fade" id="showReviewModal" tabindex="-1" aria-labelledby="showReviewModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showReviewModalLabel">@lang('trans.show_review')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <th style="width: 20%">Product Name:</th>
                                <td id="showReviewProduct"></td>
                            </tr>
                            <tr>
                                <th style="width: 20%">User Name:</th>
                                <td id="showReviewUser"></td>
                            </tr>
                            <tr>
                                <th style="width: 20%">Description:</th>
                                <td id="showReviewDescription"></td>
                            </tr>
                            <tr>
                                <th style="width: 20%">Rating:</th>
                                <td id="showReviewRating"></td>
                            </tr>
                            <tr>
                                <th style="width: 20%">Status:</th>
                                <td id="showReviewStatus"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection

@push('script')
    <script>
        // all Reviews in json format
        let reviews = @json($reviews);

        $(document).ready(function () {
            //datatable
            $("#reviewTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    {"orderable": false, "targets": [6]}
                ],
                "pageLength": {{settings('per_page')}}
            });

            // Review edit form
            // $('#reviewEditForm').validate({
            //     rules: {
            //         name: {
            //             required: true,
            //         },
            //     },
            //     messages: {
            //         name: {
            //             required: "Please enter a review name",
            //         },
            //         terms: "Please accept our terms"
            //     },
            //     errorElement: 'span',
            //     errorPlacement: function (error, element) {
            //         error.addClass('invalid-feedback');
            //         element.closest('.form-group').append(error);
            //         if (element.parent('.input-group').length) {
            //             error.insertAfter(element.parent());
            //         } else {
            //             error.insertAfter(element);
            //         }
            //     },
            //     highlight: function (element, errorClass, validClass) {
            //         $(element).addClass('is-invalid');
            //     },
            //     unhighlight: function (element, errorClass, validClass) {
            //         $(element).removeClass('is-invalid');
            //     }
            // });
        });

        // Functions
        function openShowReviewModal(id) {
            let review = reviews.find(review => review.id == id);
            // Set update row value
            $('#showReviewDescription').html(review.description);
            $('#showReviewProduct').html(review.product ? review.product.name : '');
            $('#showReviewUser').html(review.user ? review.user.full_name : '');

            $('#showReviewStatus').html('');
            $('#showReviewStatus').append(review.status === 1 ? "<span class='badge badge-success'>Approved</span>" : "<span class='badge badge-warning'>Pending</span>");

            $('#showReviewRating').html('');

            for(i = 1; i <= 5; i++){
                if(i > review.rating){
                    $('#showReviewRating').append(`<i class="fa fa-star"></i>`);
                }else{
                    $('#showReviewRating').append(`<i class="fa fa-star text-success"></i>`);
                }
            }

            // Open modal
            $('#showReviewModal').modal('show');
        }

        // function openEditReviewModal(slug) {
        //     let review = reviews.find(review => review.slug == slug);
        //
        //     // Set edit form action url
        //     $('#reviewEditForm').attr('action', app_url + '/admin/reviews/' + review.slug);
        //
        //     // Set update row value
        //     $('#editReviewName').val(review.name);
        //
        //     review.status == 1 ? $('#editReviewStatus').bootstrapSwitch('state', review.status, true) : $('#editReviewStatus').bootstrapSwitch('state', review.status, false);
        //     ;
        //
        //     // Open modal
        //     $('#editReviewModal').modal('show');
        //
        // }

        // Delete Review
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

        // Review Status Change
        function reviewStatusChange(id) {
            Swal.fire({
                title: 'Are you sure to change?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = $('#reviewStatus-' + id).data('href');
                }
            });
        }
    </script>
@endpush
