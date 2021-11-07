-
@if(isset($category->category->category_id))
    @include('admin.category.child', ['category' => $category->category])
@endif



