$(function() {
    "use strict";

    //This is for the Notification top right
    if(!sessionStorage.getItem("flag-wlcm")){
        sessionStorage.setItem("flag-wlcm",1);
        $.toast({
            heading: 'Welcome to Dacita Dashboard',
            text: 'Berikan yang terbaik untuk Telkomsel.',
            position: 'top-right',
            loaderBg: '#f62d51',
            icon: 'info',
            hideAfter: 3500,
            stack: 6
        })
    }

    $.ajax({
        url: "/dgs/api/v2/user/chartHis",
        type: 'GET',
        beforeSend: function (request) {
            request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
        },
        success: function(resp){
            if(resp.code == 200){
                Morris.Area({
                    element: 'morris-area-chart',
                    data: resp.data.value,
                    xkey: 'date',
                    ykeys: resp.data.label,
                    labels: resp.data.label,
                    pointSize: 0,
                    fillOpacity: 0,
                    pointStrokeColors: ['#f62d51', '#7460ee', '#009efb','#ff5722','#f09ae9'],
                    behaveLikeLine: true,
                    gridLineColor: '#f6f6f6',
                    lineWidth: 1,
                    hideHover: 'auto',
                    lineColors: ['#009efb', '#7460ee', '#009efb', '#ff5722', '#f09ae9'],
                    resize: true
                });
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: resp.data.message,
                });
            }
        }
    });
});

function mapsInit(){
    mapDashboard.init();
}

let mapDashboard = {
    init: function(){
        this.map;
        this.markers = [];
        this.datacenter = [
            { id: "datacenter_bsd",            name: "BSD"             , lat: -6.3064753   , lng: 106.6499949 },
            { id: "datacenter_buaran",         name: "BUARAN"          , lat: -6.3405066   , lng: 106.6879453 },
            { id: "datacenter_tbs",            name: "TB Simatupang"   , lat: -6.3047169   , lng: 106.8436446 },
            { id: "datacenter_sukoharjo",      name: "SUKOHARJO"       , lat: -7.6746436   , lng: 110.79154 },
            { id: "datacenter_gayungan",       name: "GAYUNGAN"        , lat: -7.3280201   , lng: 112.7092178 },
            { id: "datacenter_arifinahmad",    name: "ARIFIN AHMAD"    , lat: 0.4802844    , lng: 101.432637},
            { id: "datacenter_sudiang",        name: "SUDIANG"         , lat: -5.0832305   , lng: 119.5081353 },
        ]

        // element
        this.cardSummaryMinor = $("#card_summary_minor");
        this.cardSummaryMajor = $("#card_summary_major");
        this.cardSummaryCritical = $("#card_summary_critical");
        this.datacenterInformation = $("#datacenter_information");
        this.btn_reset = $("#btn_reset");
        this.btn_back = $("#btn_back");
        this.btn_fullscreen = $("#btn_fullscreen");

        this.modalalarmpercategory = $("#modal_per_category");
        this.card_summary_critical = $($("#card_summary_critical").parent().parent());
        this.card_summary_major = $("#card_summary_major").parent().parent();
        this.card_summary_minor = $("#card_summary_minor").parent().parent();

        // map 
        this.initMap();
        this.loadDatacenterInfoWindow();

        // table 
        this.initTable();
        // this.loadAlarmSummaryCard();
        this.attachEventElement();
        this.setStyle();

        this.autoRefresh = setInterval( () => {
            // refresh card 
            // this.loadAlarmSummaryCard();
            // refresh table
            this.loadAlarmActive();
        }, 5 * 60 * 1000 );

    },
    attachEventElement: function(){
        this.btn_back.on("click", () => {
            window.location.href = 'dashboard';
        });

        this.btn_reset.on("click", () => {
            try {
                // set
                $('#map').height('250px');
                // map
                const inaLat = -2.1848814,
                inaLng = 117.3218772
                inaZoom = 4.8 ;
                this.map.setCenter({lat: inaLat, lng: inaLng});
                this.map.setZoom(inaZoom);

                // close open window
                if (this.activeInfoWindow) { this.activeInfoWindow.close();}

                // detail 
                this.datacenterInformation.find('.card-header').empty();
                this.datacenterInformation.find('.card-header').append('Data Center');
                this.datacenterInformation.find('.card-body').empty();
                this.datacenterInformation.find('.card-body').append('<span><sup>*</sup>Select Data Center on MAP to show detail <span>');
                this.datacenterInformation.find('.card-footer').hide();
            }
            catch(err) {
                console.log("error something went wrong!!");
            }
        });

        // datacenter list lantai
        $(document).on('click','.list-lantai',function() {
            var ttc = this.getAttribute("ttc");
            var lantai = this.getAttribute("lantai");
            window.location.href = "/dgs/dacita_dashboard/detail/"+ttc+"/lantai/"+lantai;
        });

        $(document).on('click','.datacenter-detail-floors td',function() {
            var ttc = this.getAttribute("ttc");
            var lantai = this.getAttribute("lantai");
            window.location.href = "/dgs/dacita_dashboard/detail/"+ttc+"/lantai/"+lantai;
        });

        $(document).on('click','#btn-full',() => {
            this.toggleFullscreen();
        })

        document.addEventListener('fullscreenchange', () => { $(mapDashboard.btn_fullscreen.find('i')[0]).toggleClass('mdi-fullscreen-exit'); });
        document.addEventListener('webkitfullscreenchange', () => { $(mapDashboard.btn_fullscreen.find('i')[0]).toggleClass('mdi-fullscreen-exit'); });
        document.addEventListener('mozfullscreenchange', () => { $(mapDashboard.btn_fullscreen.find('i')[0]).toggleClass('mdi-fullscreen-exit'); });
        document.addEventListener('MSFullscreenChange', () => { $(mapDashboard.btn_fullscreen.find('i')[0]).toggleClass('mdi-fullscreen-exit'); });

        this.card_summary_critical.on("click", () => {
            this.getAlarmPercategoryAll("Critical");
        })

        this.card_summary_major.on("click", () => {
            this.getAlarmPercategoryAll("Major");
        })

        this.card_summary_minor.on("click", () => {
            this.getAlarmPercategoryAll("Minor");
        })

        //  button modal alarm per category 
        this.modalclose_btn = document.getElementsByClassName("btn-close-modal")[0];
        this.modalclose_btn.onclick = () => {
            // this.modalalarmpercategory.style.display = "none";
            this.tbAlarmPerCategory.clear().draw();
            this.modalalarmpercategory.hide();
        }

        window.onclick = (event) => {
            if (event.target == this.modalalarmpercategory[0]) {
                // this.modalalarmpercategory.style.display = "none";
                this.tbAlarmPerCategory.clear().draw();
                this.modalalarmpercategory.hide();
            }
        }
    },
    setStyle: function() {
        this.card_summary_critical.css("cursor", "pointer");
        this.card_summary_major.css("cursor", "pointer");
        this.card_summary_minor.css("cursor", "pointer");
    },
    initMap: async function(){
        const inaLat = -2.1848814,
            inaLng = 117.3218772
            inaZoom = 4.8 ;
    
        this.map =  await new google.maps.Map(document.getElementById('map'), {
            center: {lat: inaLat, lng: inaLng},
            zoom: inaZoom,
            mapTypeControl: false
        });

        this.map.addListener("click", function() {
            // close your window
            if (mapDashboard.activeInfoWindow) { mapDashboard.activeInfoWindow.close();}
        });
    
        this.putDatacenterMarker();
    },
    putDatacenterMarker: function putDatacenterMarker(){
        const iconBase = '/dgs/assets/images/marker/';
    
        this.datacenter.forEach((e)=>{
            let position = new google.maps.LatLng(e.lat,e.lng);
            let marker = new google.maps.Marker({
                position: position, 
                map: this.map,
                icon: {
                    url: iconBase + 'datacenter.png',
                    scaledSize: new google.maps.Size(25, 25)
                }
            });

            // get datacenter info 
            let datacenter = this.templateInfoWindow.find(element => element.datacenter_id == e.id);

            let infowindow = new google.maps.InfoWindow({
                content: datacenter.template
            });
            let infowindowDatacenterName = new google.maps.InfoWindow({
                content: 'TTC '+e.name
            });
            marker.addListener('click', function() {
                if (mapDashboard.activeInfoWindow) { mapDashboard.activeInfoWindow.close();}
                infowindow.open(map, marker);
                $('#map').height('440px');
                mapDashboard.showDataCenterDetail(e.id);
                mapDashboard.activeInfoWindow = infowindow;
            });
            marker.addListener('mouseover', function() {
                infowindowDatacenterName.open(map, this);
            });
            marker.addListener('mouseout', function() {
                infowindowDatacenterName.close();
            });
            this.markers.push(marker);
        })
    },
    loadDatacenterInfoWindow: function(){
        this.templateInfoWindow = [];
        list_datacenter.forEach(element => {
            var lantais = "";
            element.floors.forEach(lantai => {
                lantais += "<a class='dropdown-item list-lantai' ttc='"+element.id+"' lantai='"+lantai+"'>Lantai "+lantai+"</a>"
            })

            let template = {
                datacenter_id : element.id,
                template : `<div class='col-12 datacenter-infowindow'>
                    <div class='card dropdown'>
                        <div class='card-body hide-menu'>
                            <a href='javascript:void(0)' class='' id='Userdd' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <div class='d-md-flex align-items-center'>
                                    <div class='row'>
                                        <div class='col-lg-12'>
                                            <h4 class='card-title infowindowtitle'>`+element.name+` <i class='fa fa-angle-down'></i></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-lg-12'>
                                        <div class='datacenter-pic'>
                                            <img src='`+element.photo+`' alt='data center `+element.name+`' class='rounded-circle infowindowimg' width='110' height='110' />
                                        </div>
                                    </div>
                                </div>
                                <div class='row datacenter-detail'></div>
                            </a>
                            <div class='dropdown-menu dropdown-menu-right daftar-lantai' aria-labelledby='lantai-datacenter'>
                                `+lantais+`
                            </div>
                        </div>
                    </div>
                </div>`
            };

            this.templateInfoWindow.push(template);
        });
    },
    initTable: async function(){
        this.tbAlarmActive = $('#tblalarmActive').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print'
            ],
            "lengthMenu": [ 5,10, 25, 50, "All"],
            "ajax": {
                "url": "/dgs/api/v2/user/alarmAct",
                'type': 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                },
                "error": function() {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.data.message,
                    });
                }
            },
            "columns": [
                { data: 'time_id'},
                { data: 'rak_id'},
                { data: 'datacenter_id'},
                { data: 'severity'},
                { data: 'sensor_detail'}
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData["severity"] == "MAJOR") {
                    $('td', nRow).css('background-color', 'lightsalmon');
                } else if (aData["severity"] == "MINOR") {
                    $('td', nRow).css('background-color', 'lightblue');
                } else if (aData["severity"] == "CRITICAL") {
                    $('td', nRow).css('background-color', 'lightcoral');
                }
            }
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

        this.tbAlarmPerCategory = await $('#table-alarm-percategory').DataTable({
            "dom": 'Bfrtip',
            // "iDisplayLength": 5,
       	    "buttons": [
            	'csv', 'excel','print'
       	    ],
            "columns": [
                { data: 'time_id'},
                { data: 'rak_id'},
                { data: 'datacenter_id'},
                { data: 'severity'},
                { data: 'sensor_detail'}
            ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData["severity"] == "MAJOR") {
                    $('td', nRow).css('background-color', 'lightsalmon');
                } else if (aData["severity"] == "MINOR") {
                    $('td', nRow).css('background-color', 'lightblue');
                } else if (aData["severity"] == "CRITICAL") {
                    $('td', nRow).css('background-color', 'lightcoral');
                }
            }
        });
    },
    loadAlarmActive:async function(){
        await $.ajax({
            type: "GET",
            url: "/dgs/api/v2/user/alarmAct",
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                var data = result.data;
                this.tbAlarmActive.clear().draw();
                this.tbAlarmActive.rows.add(data).draw(false);
            },
            error: function () {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: resp.data.message,
                });
            }
        });
    },
    // loadAlarmHistory:async function(){
    //     await $.ajax({
    //         type: "GET",
    //         url: "/dacita/api/v2/user/datacenter/get_sensor_dashboard.php",
    //         beforeSend: function (request) {
    //             request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
    //         },
    //         success:(result) => {
    //             var data = JSON.parse(result);
    //             this.tbAlarmHistory.clear().draw();
    //             this.tbAlarmHistory.rows.add(data).draw(false);
    //         },
    //         error: function () {
    //          Swal.fire({
    //             type: 'error',
    //             title: 'Oops...',
    //             text: resp.data.message,
    //          });
    //         }
    //     });
    // },
    // loadAlarmSummaryCard:async function(){
    //     await $.ajax({
    //         type: "GET",
    //         url: "/dacita/api/v2/user/datacenter/get_sensor_summary.php",
    //         beforeSend: function (request) {
    //             request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
    //         },
    //         success:(result) => {
    //             var data = JSON.parse(result);

    //             let summaryMinor = data.find(element => element.severity == "MINOR");
    //             let summaryMajor = data.find(element => element.severity == "MAJOR");
    //             let summaryCritical = data.find(element => element.severity == "CRITICAL");

    //             this.cardSummaryMinor.text(summaryMinor.total);
    //             this.cardSummaryMajor.text(summaryMajor.total);
    //             this.cardSummaryCritical.text(summaryCritical.total);

    //         },
    //         error: function () {
    //          Swal.fire({
    //             type: 'error',
    //             title: 'Oops...',
    //             text: resp.data.message,
    //          });
    //         }
    //     });
    // },
    showDataCenterDetail:async function(datacenter_id){
        await $.ajax({
            url: "/dgs/api/v2/user/sumPerDataCenter",
            type: "GET",
            data: { 
                "datacenter": datacenter_id
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                if(result.code == 200) {
                    let data = result.data;
                    let detailSensor = "";
                    let total = 0;
                    let listFloors = "";

                    data.forEach(row => {
                        detailSensor += `<tr class='alarm`+ row.severity.charAt(0).toUpperCase() + row.severity.toLowerCase().slice(1) +`'>
                            <td >`+ row.severity+`</td>
                            <td >`+ row.total+`</td>
                        </tr>`;
                        total += parseInt(row.total);
                    });

                    detailSensor += `<tr class='totalSensor'>
                        <td >TOTAL SENSOR</td>
                        <td >`+ total+`</td>
                    </tr>`;

                    // clear information detail
                    this.datacenterInformation.find('.card-body').empty();
                    this.datacenterInformation.find('.card-header').empty();
                    let datacenter = list_datacenter.find( element => element.id == datacenter_id );

                    datacenter.floors.forEach(lantai => {
                        // listFloors += "<a class='dropdown-item list-lantai' ttc='"+element.id+"' lantai='"+lantai+"'>Lantai "+lantai+"</a>";
                        listFloors += "<tr class='border-bottom'><td class='p-0 text-center' ttc='"+datacenter_id+"' lantai='"+lantai+"'>"+lantai+"</td></tr>"
                    })

                    if(datacenter != undefined){
                        let template = `<div class='row'>
                            <div class='col-lg-4 col-sm-12'>
                                <div class='datacenter-pic h-100'>
                                    <img src='`+datacenter.photo+`' alt='data center' class='img-circle infowindowimg' width='90' height='90' />
                                </div>
                            </div>
                            <div class='col-lg-4 col-sm-12'>
                                <div class='row datacenter-detail h-100'>
                                    <div class='table-responsive'>  
                                        <table class="table table-striped">
                                            `+detailSensor+`
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class='col-lg-4 col-sm-12'>
                                <div class="card border datacenter-detail-floors">
                                    <div class="card-header">
                                        <div ="rows">
                                            Floors
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class='table-responsive'>  
                                            <table class="table table-striped">
                                                `+listFloors+`
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
            
                        this.datacenterInformation.find('.card-header').append('Data Center '+datacenter.name);
                        this.datacenterInformation.find('.card-body').append(template);
                        this.datacenterInformation.find('.card-footer').show();
                    }else{
                        this.datacenterInformation.find('.card-header').append('Data Center');
                        this.datacenterInformation.find('.card-body').append('<span><sup>*</sup>Select Data Center to show detail <span>');
                        this.datacenterInformation.find('.card-footer').hide();
                    }
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.data.message,
                    });
                }
            },
            error: function () {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Internal Server Error',
                });
            }
        });
    },
    toggleFullscreen: function() {
        let elem = document.getElementsByClassName('container-fluid')[0];
        elem.setAttribute("background","white")
      
        if (!document.fullscreenElement) {
          elem.requestFullscreen().catch(err => {
            alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
          });
        } else {
          document.exitFullscreen();
        }      
    },
    getAlarmPercategoryAll: function(category) {
        $("#title-alarm-category").text("("+category+")");
        this.modalalarmpercategory.show();
        this.loadAlarmPerCategory(category); 
    },
    loadAlarmPerCategory:async function(category){
        await $.ajax({
            type: "POST",
            url: "/dgs/api/v2/user/sensorPerCategory",
            data: {
                category: category
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                var data = result.data;
                this.tbAlarmPerCategory.rows.add(data).draw(false);
            },
            error: function () {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Internal Server Error',
                });
            }
        });
    },
};

