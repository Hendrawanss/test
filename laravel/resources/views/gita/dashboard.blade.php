@extends('app')

@section('title', 'Welcome to Gita Dashboard')

@section('content')
<!-- ============================================================== -->
<!-- Alarm Active -->
<!-- ============================================================== -->
<div class="page-wrapper" style="background-image:url(images/gita/bg_poly.png); height: 100%; width: 100%;">
    <section id="wrapper" class="login-register">
        <img src="{{ asset('images/gita/logo.png') }}" class="center" alt="Logo gita" width="30%" height="30%">
        <div class="card tranparent">
            <div class="card-header text-white card-danger">
                <h4 class="center">Sample Page Gita Dashboard</h4>
            </div>
            <div class="panel center">
                <div class="child">
                    <div class="card">
                        <div class="card-body card-body-pd-10">
                            <a href="#">
                                <img class="rounded" src="{{ asset('images/gita/working.png') }}" alt="Working Img">
                                <h3>Non Telkomsel / Working</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="child">
                    <div class="card">
                        <div class="card-body card-body-pd-10">
                            <a href="#">
                                <img class="rounded" src="{{ asset('images/gita/visit.png') }}" alt="Telkomesl Img">
                                <h3>Telkomsel</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="child">
                    <div class="card">
                        <div class="card-body card-body-pd-10">
                            <a href="#">
                                <img class="rounded" src="{{ asset('images/gita/vip.png') }}" alt="VIP Img">
                                <h3>VIP</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="child">
                    <div class="card">
                        <div class="card-body card-body-pd-10">
                            <a href="#">
                                <img class="rounded" src="{{ asset('images/gita/media_interactive.png') }}" alt="Media Interactive Img">
                                <h3>Media Interactive</h3>
                            </a>
                        </div>
                    </div>
                </div>  
                <div class="child">
                    <div class="card">
                        <div class="card-body card-body-pd-10">
                            <a href="#">
                                <img class="rounded" src="{{ asset('images/gita/frontdesk.png') }}" alt="Frontdesk">
                                <h3>Frontdesk</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer footer-gita">
        © 2020 IT Support Bussiness Area 2
    </footer>
</div>
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
<!-- JS -->
@endsection