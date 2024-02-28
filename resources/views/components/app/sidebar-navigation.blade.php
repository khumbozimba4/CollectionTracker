        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-category">MENU</li>

            <li class="nav-item">
              <a class="nav-link" href="{{route('dashboard')}}">
                <span class="icon-bg"><i class="fa fa-dashboard menu-icon"></i></span>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>

            @if(auth()->user()->hasRole('admin'))
                <li class="nav-item">
                    <a class="nav-link" href="{{route('dashboard')}}">
                        <span class="icon-bg"><i class="fa fa-file menu-icon"></i></span>
                        <span class="menu-title">Reports</span>
                    </a>
                    </li>

                <li class="nav-item">
                  <a class="nav-link" href="{{route('users')}}">
                      <span class="icon-bg"><i class="fa fa-user-circle menu-icon"></i></span>
                      <span class="menu-title">Users</span>
                  </a>
                </li>
               
            @endif
            <li class="nav-item">
              <a class="nav-link" href="{{route('customers')}}">
                  <span class="icon-bg"><i class="fa fa-user-circle menu-icon"></i></span>
                  <span class="menu-title">Customers</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route('invoices')}}">
                  <span class="icon-bg"><i class="fa fa-user-circle menu-icon"></i></span>
                  <span class="menu-title">Invoices</span>
              </a>
            </li>
          </ul>
        </nav>