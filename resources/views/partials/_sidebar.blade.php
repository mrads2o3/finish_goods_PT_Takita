<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item {{($location == 'Dashboard')?'active':'';}}">
            <a class="nav-link" href="{{url("")}}">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item nav-category">main tools</li>
        <li class="nav-item {{($location == 'Transaction')?'active':' '}}">
            <a class="nav-link" href="{{url('transactions')}}">
                <i class="menu-icon mdi mdi-comment-text-outline"></i>
                <span class="menu-title">Transactions</span>
            </a>
        </li>
        <li class="nav-item {{($location == 'Orders')?'active':'';}}">
            <a class="nav-link" href="{{url('orders')}}">
                <i class="menu-icon mdi mdi-comment-text-outline"></i>
                <span class="menu-title">Orders</span>
            </a>
        </li>
        <li class="nav-item {{($location == 'Item List')?'active':'';}}">
            <a class="nav-link" href="{{url('items')}}">
                <i class="menu-icon mdi mdi-comment-text-outline"></i>
                <span class="menu-title">Item List</span>
            </a>
        </li>
        <li class="nav-item {{($location == 'Customer List')?'active':'';}}">
            <a class="nav-link" href="{{url('customers')}}">
                <i class="menu-icon mdi mdi-comment-text-outline"></i>
                <span class="menu-title">Customer List</span>
            </a>
        </li>

        <li class="nav-item nav-category">account</li>
        <li class="nav-item">
            <a id="logout-btn" class="nav-link"
                href="#">
                <i class="menu-icon mdi mdi-logout"></i>
                <span class="menu-title">Logout</span>
            </a>
        </li>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            @method('DELETE')
        </form>
    </ul>
</nav>

<script>
    window.onload = function() {
        document.getElementById('logout-btn').onclick = function() {
            if(confirm('Are you sure to Logout?')){
                document.getElementById('logout-form').submit();
            }
        };
    };
</script>
