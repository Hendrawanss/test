@extends('app')

@section('title', 'Role Setting')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid" style="background-color: white;">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Management</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <!-- <li class="breadcrumb-item active">Dashboard 1</li> -->
                    </ol>
                </div>
            </div>
        </div>

        <!-- Modal Add User -->
        <div id="modal_form_add_user" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addUserForm" action="#">
                        <b><h3 class="text-center"><b>Form Add User</b></h3></b>
                        <input class="form-control" name="type" type="hidden" value="User">
                        <input class="form-control" id="id" name="id" type="hidden" placeholder="ID">
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <h4 class="text-black">Username</h4>
                                <input class="form-control" id="username" name="username" type="text" placeholder="Username" autofocus required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Password <label for=""><input id="using_ldap" name="using_ldap" type="checkbox" > Using Ldap?</label></h4>
                                <input class="form-control" id="password" name="password" type="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Nama Lengkap</h4>
                                <input class="form-control" id="nama_lengkap" name="nama_lengkap" type="text" placeholder="Nama Lengkap" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Lokasi</h4>
                                <select class="form-control" name="unik_lokasi" id="unik_lokasi">
                                    <option value="">==== Pilih Lokasi User ====</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Nomor Telephon</h4>
                                <input class="form-control" id="no_telp" name="no_telp" type="number" placeholder="Ex: 081288769890" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Tanggal Lahir</h4>
                                <input class="form-control" id="tgl_lahir" name="tgl_lahir" type="date" placeholder="Ex: 081288769890" required>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-danger btn-lg btn-block text-uppercase btn-rounded" type="submit"> <span id="loading"></span> Submit</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Modal Add Role -->
        <div id="modal_form_add_role" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addRoleForm" action="#">
                        <b><h3 class="text-center"><b>Form Add Role</b></h3></b>
                        <input class="form-control" name="type" type="hidden" value="Role">
                        <input class="form-control" id="id_role" name="id_role" type="hidden" placeholder="ID">
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <h4 class="text-black">Name</h4>
                                <input class="form-control" id="name_role" name="name_role" type="text" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Description (Optional)</h4>
                                <textarea class="form-control" name="description_role" id="description_role" cols="30" rows="5" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-danger btn-lg btn-block text-uppercase btn-rounded" type="submit"> <span id="loading"></span> Submit</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Modal Add Menu -->
        <div id="modal_form_add_menu" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addMenuForm" action="#">
                        <b><h3 class="text-center"><b>Form Add Menu</b></h3></b>
                        <input class="form-control" name="type" type="hidden" value="Menu">
                        <input class="form-control" id="id_menu" name="id_menu" type="hidden" placeholder="ID">
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <h4 class="text-black">Name</h4>
                                <input class="form-control" id="name_menu" name="name_menu" type="text" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Description (Optional)</h4>
                                <textarea class="form-control" name="description_menu" id="description_menu" cols="30" rows="5" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-danger btn-lg btn-block text-uppercase btn-rounded" type="submit"> <span id="loading"></span> Submit</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Modal Add User Role -->
        <div id="modal_form_add_user_role" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addUserRoleForm" action="#">
                        <b><h3 class="text-center"><b>Form Add Role In User</b></h3></b>
                        <input class="form-control" name="type" type="hidden" value="User Role">
                        <input class="form-control" id="id_user_role" name="id_user_role" type="hidden" placeholder="ID">
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <h4 class="text-black">User</h4>
                                <input class="form-control" id="user_role_name" name="user_role_name" type="text" placeholder="User Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Role</h4>
                                <Select id="role" name="role" class="form-control">
                                    <option value="">--- Choose the Role ---</option>
                                </Select>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-danger btn-lg btn-block text-uppercase btn-rounded" type="submit"> <span id="loading"></span> Submit</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Modal Add Role Menu -->
        <div id="modal_form_add_role_menu" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addRoleMenuForm" action="#">
                        <b><h3 class="text-center"><b>Form Add Menu In Role</b></h3></b>
                        <input class="form-control" name="type" type="hidden" value="Role Menu">
                        <input class="form-control" id="id_role_menu" name="id_role_menu" type="hidden" placeholder="ID">
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <h4 class="text-black">Role</h4>
                                <input class="form-control" id="role_menu_name" name="role_menu_name" type="text" placeholder="Role Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">Menu</h4>
                                <input type="radio" id="male" name="gender" value="male">
                                <label class="form-control" for="male">Male</label><br>
                                <input type="radio" id="female" name="gender" value="female">
                                <label class="form-control" for="female">Female</label><br>                         
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-danger btn-lg btn-block text-uppercase btn-rounded" type="submit"> <span id="loading"></span> Submit</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="divider"></div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body card-body-pd-10">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" id="role-tab" data-toggle="tab" href="#tab-role" role="tab" aria-controls="profile"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Role Management</span></a></li>
                            <li class="nav-item"> <a class="nav-link" id="menu-tab" data-toggle="tab" href="#tab-menu" role="tab" aria-controls="profile"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Menu Management</span></a></li>
                            <li class="nav-item"> <a class="nav-link" id="role-menu-tab" data-toggle="tab" href="#tab-role-menu" role="tab" aria-controls="profile"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Role - Menu Management</span></a></li>
                        </ul>
                        <div class="tab-content tabcontent-border p-20" id="myTabContent">
                            <div role="tabpanel" class="tab-pane fade show active" id="tab-role" aria-labelledby="role-tab">
                                <div class="table-responsive">
                                    <table id="tb_role"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Default Menu</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-menu" aria-labelledby="menu-tab">
                                <div class="table-responsive">
                                    <table id="tb_menu"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="tab-role-menu" aria-labelledby="role-menu-tab">
                                <div class="table-responsive">
                                    <table id="tb_role_menu"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Role Name</th>
                                                <th>List Menu</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="divider"></div>
@endsection

@section('script')
<!--morris JavaScript -->
<script src="{{ asset('assets/node_modules/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/morrisjs/morris.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/jquery-sparkline/jquery.sparkline.min.js') }}"></script> 
<!--DataTable -->
<script src="{{ asset('assets/node_modules/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js') }}"></script>
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<!-- Costume JS -->
<script src="{{ asset('dist/js/setting_role.js') }}"></script>
<script>
    (function() {
        Setting.init();
    })();
</script>
@endsection