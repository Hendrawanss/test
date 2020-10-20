@extends('app')

@section('title', 'Detail DataCenter')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid" style="background-color: white;">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Dacita Dashboard</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dacita.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="divider"></div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-12">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <b><span class="datacenter_name"> </span> ( Lantai <span class="datacenter_floor"></span> )</b>
                            <button class="btn btn-danger color-opct-8" id="btn_viewall">All Data</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body card-login card-body-pd-10">
                            <div class="row">
                                <!-- column -->
                                <div class="col-lg-12 container-datacenter-layout" style="height:450px;width:100%;">
                                    <svg id="datacenter-layout" width="100%" height="450"></svg>
                                    <div class="datacenter-text-container">
                                        * Click rack to see detail
                                    </div>
                                    <div class="datacenter-button-container">
                                        <button class="btn btn-danger color-opct-8" id="up">Up</button>
                                        <button class="btn btn-danger color-opct-8" id="down">Down</button>
                                        <button class="btn btn-danger color-opct-8" id="left">Left</button>
                                        <button class="btn btn-danger color-opct-8" id="right">Right</button>
                                        <button class="btn btn-danger color-opct-8" id="zoomin">Zoom In</button>
                                        <button class="btn btn-danger color-opct-8" id="zoomout">Zoom Out</button>
                                    </div>
                                </div>
                                <!-- column -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <!-- modal rack detail -->
        <div id="modal-detail-rak" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="float-left">
                            <h4 id="modal-title">NAMA RAK</h4>
                        </div>
                        <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="row modal-body">
                        <div class="rak_sensor_detail">
                            <table>
                            <tr>
                                <td>Data Time</td>
                                <td id="sensor_time"></td>
                            </tr>
                            <tr>
                                <td>Temperature</td>
                                <td id="rak_temp">Temperature</td>
                            </tr>
                            <tr>
                                <td>Humidity</td>
                                <td id="rak_humid">Humidity</td>
                            </tr>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table id="table-detail-rak" class="table v-middle">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="border-top-0">InventoryId</th>
                                        <th class="border-top-0">TTC</th>
                                        <th class="border-top-0">Level</th>
                                        <th class="border-top-0">Zone</th>
                                        <th class="border-top-0">RackLabel</th>
                                        <th class="border-top-0">RackType</th>
                                        <th class="border-top-0">HWBrand</th>
                                        <th class="border-top-0">HWType</th>
                                        <th class="border-top-0">HWSeries</th>
                                        <th class="border-top-0">HWSn</th>
                                        <th class="border-top-0">ApplicationName</th>
                                        <th class="border-top-0">HostName</th>
                                        <th class="border-top-0">AssetOwner</th>
                                        <th class="border-top-0">NoHandphone</th>
                                        <th class="border-top-0">ProjectName</th>
                                        <th class="border-top-0">TanggalOn</th>
                                        <th class="border-top-0">SourcePowerA</th>
                                        <th class="border-top-0">SourcePowerB</th>
                                        <th class="border-top-0">Connectivity</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal all detail -->
        <div id="modal-detail-all" class="modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="float-left">
                            <h4 id="modal-title">All Rack Detail</h4>
                        </div>
                        <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="row modal-body">
                        <div class="table-responsive">
                            <table id="table-detail-all" class="table v-middle">
                                <thead>
                                    <tr class="bg-light">
                                        
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer">
        © 2020 IT Support Bussiness Area 2
    </footer>
</div>
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
<!-- Snap -->
<script src="{{ asset('dist/js/snapsvg/dist/snap.svg.js') }}"></script>
<script src="{{ asset('dist/js/snapsvg/dist/snap.svg.zpd.js') }}"></script>
<!-- JS -->
<script src="{{ asset('dist/js/data.js') }}"></script>
<script src="{{ asset('dist/js/detail.js') }}"></script>
<!--This page JavaScript -->
<script>
    (function() {
        Datacenter.init();
    })();    
</script>
@endsection