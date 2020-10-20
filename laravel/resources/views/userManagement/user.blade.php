@extends('app')

@section('title', 'Menu Setting')

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
            <div class="overlay"></div>
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addUserForm" action="#">
                        <b><h3 id="title-form-add-user" class="text-center">Form Add User</h3></b>
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
                                <h4 class="text-black">Lokasi</h4>
                                <select class="form-control" name="unik_lokasi" id="unik_lokasi">
                                    <option value="">==== Pilih Lokasi User ====</option>
                                </select>
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
                                <h4 class="text-black">Jabatan</h4>
                                <input class="form-control" id="jabatan" name="jabatan" type="text" placeholder="Jabatan" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <h4 class="text-black">NIK</h4>
                                <input class="form-control" id="nik" name="nik" type="text" placeholder="NIK" required>
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

        <!-- Modal Add User Role -->
        <div id="modal_form_add_user_role" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                    <form class="form-horizontal form-material" id="addUserRoleForm" action="#">
                        <b><h3 class="text-center"><b>Form Add Role In User</b></h3></b>
                        <input class="form-control" name="type" type="hidden" value="User Role">
                        <input class="form-control" id="user_role_id" name="id_user_role" type="hidden" placeholder="ID">
                        <div class="form-group m-t-40">
                            <div class="col-xs-12">
                                <h4 class="text-black">User</h4>
                                <input class="form-control" id="user_role_name" name="user_role_name" type="text" placeholder="User Name" readonly required>
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

        <div class="divider"></div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body card-body-pd-10">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#tab-user" role="tab" aria-controls="home5" aria-expanded="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">User Management</span></a> </li>
                            <li class="nav-item"> <a class="nav-link" id="user-role-tab" data-toggle="tab" href="#tab-user-role" role="tab" aria-controls="profile"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">User - Role Management</span></a></li>
                        </ul>
                        <div class="tab-content tabcontent-border p-20" id="myTabContent">
                            <div role="tabpanel" class="tab-pane fade show active" id="tab-user" aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table id="tb_user"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Username</th>
                                                <th>Password</th>
                                                <th>Nama Lengkap</th>
                                                <th>NIK</th>
                                                <th>Jabatan</th>
                                                <th>Tanggal lahir</th>
                                                <th>Unik Lokasi  (Wajib di Gita)</th>
                                                <th>No. Telp</th>
                                                <th>Role</th>
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
                            <div role="tabpanel" class="tab-pane fade" id="tab-user-role" aria-labelledby="user-role-tab">
                                <div class="table-responsive">
                                    <table id="tb_user_role"
                                        class="display nowrap table table-hover table-striped table-bordered"
                                        cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Username</th>
                                                <th>Nama Lengkap</th>
                                                <th>Role</th>
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
<script src="{{ asset('dist/js/setting_user.js') }}"></script>
<script>
    (function() {
        Setting.init();
    })();
</script>
@endsection