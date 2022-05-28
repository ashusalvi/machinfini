{{-- <div class="course-edit-nav list-group list-group-horizontal-md mb-3 text-center  ">
    @php
        $nav_items = seminar_edit_navs();
    @endphp

    @if(is_array($nav_items) && count($nav_items))
        @foreach($nav_items as $route => $nav_item)
            <a href="#" class="list-group-item list-group-item-action list-group-item-info {{array_get($nav_item, 'is_active') ? 'active' : ''}}">
                {!! array_get($nav_item, 'icon') !!} 
                <p class="m-0">{{array_get($nav_item, 'name')}}</p>
            </a>
        @endforeach
    @endif

</div> --}}

<div class="course-edit-nav list-group list-group-horizontal-md mb-3 text-center  ">    
    <a href="#" class="list-group-item list-group-item-action list-group-item-info">
        <i class="la la-info-circle"></i>
        <p class="m-0">Information</p>
    </a>
</div>

