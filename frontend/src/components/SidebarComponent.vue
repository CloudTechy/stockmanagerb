<template>
  <aside ref = "aside" class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#"  class="brand-link">
            <img v-bind:src="'../img/medium.png'" alt="StockManager Logo" class="brand-image img-circle elevation-3"
               style="opacity: .8">
            <span  class="brand-text font-weight-light">StockManager</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img v-bind:src="'../img/users.png'" class="img-circle elevation-2" alt="User Image">
            </div>
            <div v-if = "$auth.user().names != undefined" class="info">
              <a href="#" class="d-block small text-capitalize">{{ $auth.user().names }}</a>
            </div>
          </div>
          <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link">
                <i class="fas fa-tachometer-alt"></i>
                <p>
                  Dashboard

                </p>
              </router-link>
            </li>

            <li class="nav-header">Account Groups</li>

           
            <li class="nav-item">
               <router-link to="/users" class="nav-link">
                <i class="fas fa-users"></i>
                <p>
                   Users
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/customers" class="nav-link">
                <i class="fas fa-tags"></i>
                <p>
                   Customers
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/suppliers" class="nav-link">
                <i class="fas fa-tags"></i>
                <p>
                   Suppliers
                </p>
              </router-link>
            </li>

            <li class="nav-header">Attributes</li>

            <li class="nav-item">
               <router-link to="/brands" class="nav-link">
                <i class="fas fa-tags"></i>
                <p>
                   Brands
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/categories" class="nav-link">
                <i class="fas fa-layer-group"></i>
                <p>
                  Categories
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/units" class="nav-link">
                <i class="fas fa-weight"></i>
                <p>
                  Units
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/sizes" class="nav-link">
                <i class="fas fa-grip-horizontal"></i>
                <p>
                  Sizes
                </p>
              </router-link>
            </li>
            <li class="nav-item">
               <router-link to="/banks" class="nav-link">
                <i class="fas fa-grip-horizontal"></i>
                <p>
                  Banks
                </p>
              </router-link>
            </li>

            <li class="nav-header">Product</li>

            <li class="nav-item">
               <router-link to="/products" class="nav-link">
                <i class="fas fa-warehouse"></i>
                <p>
                  Products
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/orders" class="nav-link">
                <i class="fas fa-shopping-cart"></i>
                <p>
                  Orders
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/invoices" class="nav-link">
                <i class="fas fa-file-invoice"></i>
                <p>
                  Invoices
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/transactions" class="nav-link">
                <i class="fas fa-money-check"></i>
                <p>
                  Transactions
                </p>
              </router-link>
            </li>
            <li class="nav-item">
               <a href="#"  @click.prevent="$auth.logout()" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                <p>
                  Logout
                </p>
              </a>
            </li>


            <li class="nav-header">Report</li>

            <li class="nav-item">
               <router-link to="/statistics" class="nav-link">
                <i class="fas fa-chart-area"></i>
                <p>
                  Statistics
                </p>
              </router-link>
            </li><!-- 

            <li class="nav-header">Setting</li>

            <li class="nav-item">
               <router-link to="profile" class="nav-link">
                <i class="fas fa-user-cog"></i>
                <p>
                  Profile
                  <i class="right fa fa-angle-left"></i>
                </p>
              </router-link>
            </li>

            <li class="nav-item">
               <router-link to="/settings" class="nav-link">
                <i class="fas fa-cog"></i>
                <p>
                  Settings
                  <i class="right fa fa-angle-left"></i>
                </p>
              </router-link>
            </li> -->

            <!-- <li class="nav-item">
               <a href="#"  @click.prevent="$auth.logout()" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                <p>
                  Logout
                </p>
              </a>
            </li> -->

          </ul>
        </nav>
      </div>
    </aside>

</template>
<script>
  export default {
    data() {
      return{
        //user: this.$auth.user(),

      }
    },
    methods : {
    },
    created(){
    },
    computed : {
      // 
    }
  }
</script>