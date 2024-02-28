<!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}"><img src="../../../../../../../assets/images/download.png" alt="logo" /></a>
          <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="../../../../../../../assets/images/download.png" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
         
          <ul class="navbar-nav navbar-nav-right">
            
           
         
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="nav-profile-img">
                  <img src="../../../../../../../Profile Images/user-icon.png" alt="image">
                </div>
                <div class="nav-profile-text">
                  <p class="mb-1 text-black">{{ auth()->user()->name }}</p>
                </div>
              </a>
              <div class="dropdown-menu navbar-dropdown dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="profileDropdown" data-x-placement="bottom-end">
                
                <div class="p-2">
                  
                  
                  <a class="dropdown-item py-1 d-flex align-items-center " href="{{route('profile.edit')}}">
                    <span>Profile</span>
                    <span class="p-0">
                      <i class="mdi mdi-account-outline ms-1"></i>
                    </span>
                  </a>
                  <a class=" py-1 d-flex align-items-center " href="#">
                    <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <div class>

                                    </div>
                                    <button  type="submit" class="btn btn-warning">Logout</button>
                                </form>
                  </a>
                </div>
              </div>
            </li>
          
           
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->