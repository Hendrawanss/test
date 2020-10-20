@extends('app')

@section('title', 'Dashboard Setting')

@section('content')
<div class="page-wrapper" style="background-image:url(images/gita/bg_poly.png); height: 100%; width: 100%; margin-top:20px;">
    <div class="card setting">
        <div class="card-header text-white card-black-costume">
            <h4 class="center">Setting Menu</h4>
        </div>
        <div class="panel center">
            <div class="child-setting">
                <div class="card">
                    <div class="card-body card-body-pd-10 menu">
                        <a href="#">
                            <img class="rounded" src="{{ asset('images/icon/add-user-256.png') }}" alt="Working Img">
                            <h3 class="text-white">User</h3>
                        </a>
                    </div>
                </div>
            </div>
            <div class="child-setting">
                <div class="card">
                    <div class="card-body card-body-pd-10 menu">
                        <a href="#">
                            <img class="rounded" src="{{ asset('images/gita/visit.png') }}" alt="Telkomesl Img">
                            <h3 class="text-white">Role</h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    © 2020 IT Support Bussiness Area 2
</footer>
@endsection

@section('script')
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
<!-- JS -->
@endsection