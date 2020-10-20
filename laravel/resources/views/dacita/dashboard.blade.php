@extends('app')

@section('title', 'Welcome to Dacita Dashboard')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid" style="background-color: white;">
        <div id="for-fullscreen">
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
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                            <!-- <li class="breadcrumb-item active">Dashboard 1</li> -->
                        </ol>
                        <button type="button" id="btn_reset" class="btn btn-danger d-none d-lg-block m-l-15 color-opct-8">Reset</button>
                        <button id="btn-full" type="button" class="btn btn-danger d-none d-lg-block m-l-15 color-opct-8"><i class="fa fa-expand"></i></button>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div id="modal_per_category" class="modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">All Alarm</h4>
                            <h4 class="modal-title" id="title-alarm-category">-</h4>
                            <button type="button" class="close btn-close-modal" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="table-alarm-percategory"
                                    class="display nowrap table table-hover table-striped table-bordered"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>DateTime</th>
                                            <th>Object</th>
                                            <th>DataCenter</th>
                                            <th>Serverity</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="divider"></div>
            <!-- ============================================================== -->
            <!-- Yearly Sales -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-sm-9">
                    <div class="card oh">
                        <div class="card-body card-body-pd-10">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="row">
                        @foreach($sensor as $data)
                        <div class="col-sm-4">
                            <div class="card border-danger">
                                <div class="card-header {{ strtolower($data->severity) }}-card-header">
                                    <p class="m-b-0 text-white text-center">{{ ucfirst(strtolower($data->severity)) }}</p></div>
                                <div id="card_summary_{{ strtolower($data->severity) }}" class="card-body {{ strtolower($data->severity) }}-card-body flag-category">
                                    <h3 class="card-title text-center">{{ $data->total }}</h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="divider"></div>
                
                    <div class="row">
                        <div class="col-12">
                            <div class="card border" id="datacenter_information">
                                <div class="card-header">
                                    Data Center
                                </div>
                                <div class="card-body">
                                    <span><sup>*</sup>Select Data Center to show detail <span>
                                </div>
                                <div class="card-footer" style="display:none">
                                    <span><sup>*</sup>Select floor to open detail</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- ============================================================== -->
            <!-- Alarm Active -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body card-body-pd-10">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item"> <a class="nav-link" id="home-tab" data-toggle="tab" href="#alarmActive" role="tab" aria-controls="home5" aria-expanded="true"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Alarm Active</span></a> </li>
                                <li class="nav-item"> <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#alarmHistory" role="tab" aria-controls="profile"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Alarm History</span></a></li>
                            </ul>
                            <div class="tab-content tabcontent-border p-20" id="myTabContent">
                                <div role="tabpanel" class="tab-pane fade" id="alarmActive" aria-labelledby="home-tab">
                                    <div class="table-responsive">
                                        <table id="tblalarmActive"
                                            class="display nowrap table table-hover table-striped table-bordered"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>DateTime</th>
                                                    <th>Object</th>
                                                    <th>DataCenter</th>
                                                    <th>Serverity</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    
                                    </div>
                                </div>
                                <div class="tab-pane fade show active" id="alarmHistory" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="card-body">
                                        <div id="morris-area-chart" style="height: 300px;"></div>
                                    </div>
                                </div>
                            </div>
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
<script src="{{ asset('dist/js/data.js') }}"></script>
<script src="{{ asset('dist/js/dashboard.js') }}"></script>
<script defer
    src="https://maps.googleapis.com/maps/api/js?key=&callback=mapsInit">
</script>
@endsection