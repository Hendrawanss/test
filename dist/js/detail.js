let Datacenter = {
    init:function(){
        this.modaldetail = document.getElementById("modal-detail-rak");
        this.modaldetailall = document.getElementById("modal-detail-all");
        this.btn_viewall = $("#btn_viewall");
        this.svg = Snap("#datacenter-layout");

        this.loadsvg();
        this.attachEvent();

        // this.initTable();
        this.getDetailHeader();

        $('#logout').click(function(e) {
            e.preventDefault();
            sessionStorage.removeItem("flag-wlcm");
            sessionStorage.removeItem("t.api");
            // Call api login
            $.get("/dgs/logout", function(result){
            // $.get("/dacita/logout", function(result){
                if(result.code==200){
                    window.location = "/dgs/login";
                    // window.location = "/dacita/login"
                }else{
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: result.message,
                    });
                }
            });
        });
    },
    attachEvent:function(){
        var s = this.svg;
        var currentZoom = this.currentZoom;
        document.getElementById("up").addEventListener ("click", function () { s.panTo('+0', '-10'); }, false);
        document.getElementById("left").addEventListener ("click", function () { s.panTo('-10'); }, false);
        document.getElementById("right").addEventListener ("click", function () { s.panTo('+10'); }, false);
        document.getElementById("down").addEventListener ("click", function () { s.panTo('+0', '+10'); }, false);
        document.getElementById("zoomin").addEventListener ("click", function () { s.zoomTo(currentZoom+1, 400); currentZoom+=1 }, false);
        document.getElementById("zoomout").addEventListener ("click", function () { if(currentZoom>1){ s.zoomTo(currentZoom-1, 400); ; currentZoom-=1} }, false);

        document.onkeydown = function (e) {
            switch(e.keyCode) {
                case 37: // left
                    s.panTo('-10');
                    break;
                case 38: // up
                    s.panTo('+0', '-10');
                    break;
                case 39: // right
                    s.panTo('+10');
                    break;
                case 40: // down
                    s.panTo('+0', '+10');
                    break;
            }
        };

        this.modalclose_btn = document.getElementsByClassName("btn-close-modal")[0];
        this.modalclose_btn.onclick = function() {
            Datacenter.modaldetail.style.display = "none";
        }

        this.modalclose_btnall = document.getElementsByClassName("btn-close-modal")[1];
        this.modalclose_btnall.onclick = function() {
            Datacenter.modaldetailall.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {

            if (event.target == Datacenter.modaldetail[0]) {
                Datacenter.modaldetail.style.display = "none";
            }
            if (event.target == Datacenter.modaldetailall[0]) {
                Datacenter.modaldetailall.style.display = "none";
            }
        }

        this.btn_viewall.on("click",()=>{
            Datacenter.getRakDetailAll();
        });
        
    },
    loadsvg:function(){
        var url_string = window.location.href;
        var url = new URL(url_string);

        var data = url_string.split('/');
        var ttc = data[data.length-3];
        var lantai = data[data.length-1];

        var ttc_data = list_layoutdatacenter.find(function(element) {
            return element.id == ttc;
        });
        var layout_path = ttc_data.floors.find(function(element) {
            return element.lantai == lantai;
        });

        $(".datacenter_name").text(ttc_data.name+" ");
        $(".datacenter_floor").text(lantai);

        var s = this.svg;
        Snap.load("/dgs/"+layout_path.svg, function(f){
            var racks = f.selectAll("g[id*='rak']");

            for(i=0;i<racks.length;i++){
                if(racks[i].node.id!="rak_map"){
                    racks[i].click(function(){
                        Datacenter.modaldetail.style.display = "block";
                        $('#modal-title').text(this.node.id.replace("rak_","").toUpperCase());
                        Datacenter.getRakDetail(this.node.id.replace("rak_",""));
                    })
                }
            }

            s.append(f);
            s.zpd({ drag: false });
        })

        this.loadsensor();
        this.currentZoom = 1;
    },
    loadsensor:()=>{
        var url_string = window.location.href;

        var data = url_string.split('/');
        var datacenter = data[data.length-3];
        var lantai = data[data.length-1];
        
        $.ajax({
            type: "GET",
            url: "/dgs/api/v2/user/getSensor",
            data: { 
                "datacenter": datacenter,
                "lantai": lantai
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                if(result.code == 200) {
                    var data = result.data;
                    Datacenter.listalarm = [];
                    for(var i=0;i<data.length;i++){
                        var rak_id = data[i].rak_id.toLowerCase();
                        if(data[i].sensor_id == "temperature"){
                            if(data[i].sensor_val > 23.5){
                                Datacenter.listalarm.push(rak_id);
                            }
                            if(data[i].sensor_val > 24.5 ){
                                Datacenter.listalarm.push(rak_id);
                            }
                            if(data[i].sensor_val > 26 ){
                                Datacenter.listalarm.push(rak_id);
                            }
                        }
                        if(data[i].sensor_id == "humidity"){
                            if(data[i].sensor_val >= 60){
                                Datacenter.listalarm.push(rak_id);
                            }
                            if(data[i].sensor_val <= 40){
                                Datacenter.listalarm.push(rak_id);
                            }
                        }
                    }
                    Datacenter.listalarm.splice(0, Datacenter.listalarm.length, ...(new Set(Datacenter.listalarm)));

                    Datacenter.interval_sensor = setInterval(function(){
                        for (let i = 0; i < Datacenter.listalarm.length; i++) {
                            var rakid = Datacenter.listalarm[i];
                            var id = "#rak_"+rakid.toLowerCase()+" rect";
                            $(id).toggleClass("alarm");
                        }
                    }, 500);
                }
            },
            error: function () {
                alert("Error !!");
            }
        });

    },
    getRakDetailAll:()=>{
        // show modal
        Datacenter.modaldetailall.style.display = "block";

        // set param
        var url_string = window.location.href;
        var data = url_string.split('/');
        var datacenter = data[data.length-3];
        var lantai = data[data.length-1];
        Datacenter.tabledetailrakall.clear().draw();

        // get data asset 
        $.ajax({
            type: "GET",
            url: "/dgs/api/v2/user/getAssetPerlevel",
            data: { 
                "datacenter": datacenter,
                "lantai": lantai
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                if(result.code == 200) {
                    var data = result.data;
                
                    data.map(function(item) {
                        var keyse = Object.keys(item);
                        for (var i in keyse) {
                            item[ keyse[i].replace(/[^\w ]/, '') ] = item[ keyse[i] ];
                        }
                    });

                    Datacenter.alldata = data;
                    Datacenter.tabledetailrakall.rows.add(Datacenter.alldata).draw(false);
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
                    text: "Internal Server Error",
                });
            }
        });
    },
    getRakDetail: async function(rakid){
        var url_string = window.location.href;
        var data = url_string.split('/');
        var datacenter = data[data.length-3];
        var lantai = data[data.length-1];
        Datacenter.tabledetailrak.clear().draw();
        // get data asset 
        $.ajax({
            type: "GET",
            url: "/dgs/api/v2/user/getAssetRak",
            data: { 
                "datacenter": datacenter,
                "lantai": lantai,
                "rakid": rakid
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                if(result.code == 200) {
                    var data = result.data;
                
                    data.map(function(item) {
                        var keyse = Object.keys(item);
                        for (var i in keyse) {
                        item[ keyse[i].replace(/[^\w ]/, '') ] = item[ keyse[i] ];
                        }
                    });

                    Datacenter.filtereddata = data;
                    Datacenter.tabledetailrak.rows.add(Datacenter.filtereddata).draw(false);
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
                    text: "Internal Server Error",
                });
            }
        });

        // get sensor detail 
        $.ajax({
            type: "GET",
            url: "/dgs/api/v2/user/getSensorRak",
            data: { 
                "datacenter": datacenter.replace('datacenter_',''),
                "level": lantai,
                "rakid": rakid.toLowerCase()
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(result) => {
                if(result.code == 200) {
                    var data = result.data; 
                
                    var temperature = data.filter(function(temp) {
                        return temp.sensor_id == "temperature";
                    });
                    var humidity = data.filter(function(temp) {
                        return temp.sensor_id == "humidity";
                    });
    
                    var temp = temperature.length > 0 ? temperature[0].sensor_val : "Sensor not available";
                    var humid = humidity.length > 0 ? humidity[0].sensor_val : "Sensor not available";
                    var data_time = temperature[0].time_id != null ? temperature[0].time_id : humidity[0].time_id != null ? humidity[0].time_id : "Data not available";
                    var time_zone = temperature[0].time_zone != null ? temperature[0].time_zone : humidity[0].time_zone != null ? humidity[0].time_zone : "Data not available";

                    $("#rak_temp").text(temp + " ℃");
                    $("#rak_humid").text(humid + " %");
                    $("#sensor_time").text(data_time + " ( " + time_zone + " )" );

                    $("#rak_temp").removeClass();
                    $("#rak_humid").removeClass();
    
                    if( temperature[0].sensor_val >= 26 ){
                        $("#rak_temp").addClass("sensorCritical");
                    }else if( temperature[0].sensor_val > 24.5 ){
                        $("#rak_temp").addClass("sensorMajor");
                    }else if( temperature[0].sensor_val > 23.5 ){
                        $("#rak_temp").addClass("sensorMinor");
                    }else {
                        $("#rak_temp").addClass("sensorNormal");
                    }		
    
                    if( ( 30 >= humidity[0].sensor_val ) || ( humidity[0].sensor_val >= 70 ) ){
                        $("#rak_temp").addClass("sensorCritical");
                    }else if( ( 35 >= humidity[0].sensor_val ) || ( humidity[0].sensor_val >= 65 ) ){
                        $("#rak_humid").addClass("sensorMajor");
                    }else if( ( 40 >= humidity[0].sensor_val ) || ( humidity[0].sensor_val >= 60 ) ){
                        $("#rak_humid").addClass("sensorMinor");
                    }else{
                        $("#rak_humid").addClass("sensorNormal");
                    }
                } else {
                    var temp = "Sensor not available";
                    var humid = "Sensor not available";
                    var data_time = "Data not available";

                    $("#rak_temp").text(temp + " ℃");
                    $("#rak_humid").text(humid + " %");
                    $("#sensor_time").text(data_time);

                    $("#rak_temp").removeClass();
                    $("#rak_humid").removeClass();

                    $("#rak_temp").addClass("temp_normal");
                    $("#rak_humid").addClass("humid_normal");
                }
            },
            error: function () {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: "Internal Server Error",
                });
            }
        });

    },
    getDetailHeader: () => {
        var url_string = window.location.href;
        var data = url_string.split('/');
        var datacenter = data[data.length-3];
        
        $.ajax({
            type: "GET",
            url: "/dgs/api/v2/user/assetHeader",
            data: { 
                "datacenter": datacenter,
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success:(res) => {
                if(res.code == 200){
                    var result = res.data;

                    var table_header1 = $("#table-detail-rak thead tr"); 
                    var table_header2 = $("#table-detail-all thead tr"); 
                    table_header1.empty();
                    table_header2.empty();

                    result.header.forEach(col => {
                        table_header1.append('<th class="border-top-0">'+col+'</th>');
                        table_header2.append('<th class="border-top-0">'+col+'</th>')
                    });

                    // remove dot from property name
                    var datas = [];
                    result.data.forEach(col => {
                        datas.push({ data: col.replace(/[^\w ]/, '') });
                    });

                    Datacenter.tabledetailrak = $('#table-detail-rak').DataTable({
                        "dom": 'Bfrtip',
                        "buttons": [
                            'csv', 'excel','print'
                        ],
                        "iDisplayLength": 5,
                        "scrollX": true,
                        "columns": datas
                    });
                    Datacenter.tabledetailrakall = $('#table-detail-all').DataTable({
                        "dom": 'Bfrtip',
                        "buttons": [
                            'csv', 'excel','print'
                        ],
                        "iDisplayLength": 5,
                        "scrollX": true,
                        "autoWidth": true,
                        "columns": datas
                    });
                } else {
                    alert('Something went wrong');
                }
            },
            error: function () {
                alert("Something went wrong");
            }
        });

    },
    initTable:function(){
        this.tabledetailrak = $('#table-detail-rak').DataTable({
            "dom": 'Bfrtip',
       	    "buttons": [
            	'csv', 'excel','print'
       	    ],
	    "scrollX": true,
            "columns": [
                { data: 'inventoryid'},
                { data: 'ttc'},
                { data: 'Level'},
                { data: 'Zone'},
                { data: 'racklabel'},
                { data: 'racktype'},
                { data: 'hwbrand'},
                { data: 'hwtype'},
                { data: 'hwseries'},
                { data: 'hwsn'},
                { data: 'applicationname'},
                { data: 'hostname'},
                { data: 'assetowner'},
                { data: 'nohandphone'},
                { data: 'projectname'},
                { data: 'tanggalon'},
                { data: 'sourcepowera'},
                { data: 'sourcepowerb'},
                { data: 'connectivity'},
            ]
        });
    }
}