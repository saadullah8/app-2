<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
    @if(\Illuminate\Support\Facades\Auth::user()->role->role=='Super Admin')
        <li class="header">System Management</li>

        <li id="menu-user-management"><a href="{{url('staff')}}"> <i class="fa fa-users"></i>
                <span>User Management</span>
            </a>
        </li>
        <li><a href="{{url('storeTiming')}}"> <i class="fa fa-shield"></i> <span>Store Timing</span> </a></li>
        <li><a href="{{url('marketing')}}"> <i class="fa  fa-share-alt"></i> <span>Promotion Message</span> </a></li>
        <li><a href="{{url('promos')}}"> <i class="fa fa-cc-discover"></i> <span>Promo codes</span>  </a> </li>
        <li><a href="{{url('export/orders')}}"> <i class="fa fa-book"></i> <span> Order Report</span>  </a> </li>

        <li class="header">Category & Product</li>
        <li class="treeview"><a href="#"> <i class="fa fa-industry"></i> <span>Product</span> </a>
            <ul class="treeview-menu">
                <li><a href="{{url('product/category/1')}}"><i class="fa fa-circle-o"></i> Fixed Product</a></li>
                <li><a href="{{url('product/category/2')}}"><i class="fa fa-circle-o"></i> Customized Product</a></li>
                {{--            <li><a href="{{url('product/category/3')}}"><i class="fa fa-circle-o"></i> Meal Product</a></li>--}}
            </ul>
        </li>
        <li><a href="{{url('category')}}"> <i class="fa  fa-suitcase"></i> <span>Category</span> </a></li>

        <li class="header">Ingredient & Groups</li>
        <li><a href="{{url('ingredient')}}"> <i class="fa fa-shield"></i> <span>Ingredient</span> </a></li>
        <li><a href="{{url('main-course')}}"> <i class="fa fa-list-ol"></i> <span>Ingredient Group</span> </a></li>
        <li><a href="{{url('main-course-list')}}"> <i class="fa  fa-columns"></i> <span>Ingredient Group List</span>
            </a>
        </li>
    @endif

    <li><a href="{{url('orders/pending')}}"> <i class="fa  fa-suitcase"></i> <span>Orders</span> </a></li>


</ul>
