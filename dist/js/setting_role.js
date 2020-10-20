let Setting = {
    init: function() {
        
        this.getData();
        this.attachEventElement();
    },

    attachEventElement: function() {
        var body = $("body");


        // Table User
        this.tb_user = $('#tb_user').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print', {
                    text: 'Add User',
                    className: 'btn-danger',
                    action: function ( e, dt, node, config ) {
                        $('#modal_form_add_user').show();
                    }
                }
            ],
            select: true,
            "lengthMenu": [ 5,10, 25, 50, "All"],
            "ajax": {
                "url": "/dgs/api/v2/user/get",
                'type': 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                },
                "error": function(resp) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.data.message,
                    });
                }
            },
            "columns": [
                { data: 'id'},
                { data: 'username'},
                { data: 'nama_lengkap'},
                { data: 'unik_lokasi'},
                { data: 'no_telp'},
                { data: 'role'},
                { data: 'created_at'},
                { data: 'updated_at'},
                { data: 'action'},
            ],
        });

        // Table Role
        this.tb_role = $('#tb_role').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print', {
                    text: 'Add Role',
                    className: 'btn-danger',
                    action: function ( e, dt, node, config ) {
                        $('#modal_form_add_role').show();
                    }
                }
            ],
            select: true,
            "lengthMenu": [ 5,10, 25, 50, "All"],
            "ajax": {
                "url": "/dgs/api/v2/role/get",
                'type': 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                },
                "error": function(resp) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.statusText,
                    });
                }
            },
            "columns": [
                { data: 'id'},
                { data: 'name'},
                { data: 'description'},
                { data: 'default_menu'},
                { data: 'created_at'},
                { data: 'updated_at'},
                {
                    mRender: function (data, type, row) {
                        return '<a href="#" type="button" id="btn_add_role" class="btn btn-danger color-opct-8">Delete</a>  <a href="#" type="button" id="btn_add_role" class="btn btn-success color-opct-8">Edit</a>'
                    }
                },
            ],
        });

        // Table Menu
        this.tb_menu = $('#tb_menu').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print', {
                    text: 'Add Menu',
                    className: 'btn-danger',
                    action: function ( e, dt, node, config ) {
                        $('#modal_form_add_menu').show();
                    }
                }
            ],
            select: true,
            "lengthMenu": [ 5,10, 25, 50, "All"],
            "ajax": {
                "url": "/dgs/api/v2/menu/get",
                'type': 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                },
                "error": function(resp) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.statusText,
                    });
                }
            },
            "columns": [
                { data: 'id'},
                { data: 'name'},
                { data: 'description'},
                { data: 'created_at'},
                { data: 'updated_at'},
                {
                    mRender: function (data, type, row) {
                        return '<a href="#" type="button" id="btn_add_role" class="btn btn-danger color-opct-8">Delete</a>  <a href="#" type="button" id="btn_add_role" class="btn btn-success color-opct-8">Edit</a>'
                    }
                },
            ],
        });

        // Table User Role
        this.tb_role = $('#tb_user_role').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print', {
                    text: 'Add Role in User',
                    className: 'btn-danger',
                    action: function ( e, dt, node, config ) {
                        $('#modal_form_add_user_role').show();
                    }
                }
            ],
            select: true,
            "lengthMenu": [ 5,10, 25, 50, "All"],
            "ajax": {
                "url": "/dgs/api/v2/user/role/get",
                'type': 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                },
                "error": function(resp) {
                    console.log(resp)
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.statusText,
                    });
                }
            },
            "columns": [
                { data: 'id'},
                { data: 'username'},
                { data: 'nama_lengkap'},
                { data: 'role' },
                { data: 'created_at'},
                { data: 'updated_at'},
                {
                    mRender: function (data, type, row) {
                        return '<a href="#" type="button" id="btn_add_role" class="btn btn-success color-opct-8">Edit</a>'
                    }
                },
            ],
        });

        // Table Role Menu
        this.tb_role = $('#tb_role_menu').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print', {
                    text: 'Add Menu in Role',
                    className: 'btn-danger',
                    action: function ( e, dt, node, config ) {
                        $('#modal_form_add_role_menu').show();
                    }
                }
            ],
            select: true,
            "lengthMenu": [ 5,10, 25, 50, "All"],
            "ajax": {
                "url": "/dgs/api/v2/role/get",
                'type': 'GET',
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                },
                "error": function(resp) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: resp.statusText,
                    });
                }
            },
            "columns": [
                { data: 'id'},
                { data: 'name'},
                { data: 'created_at'},
                { data: 'updated_at'},
                {
                    mRender: function (data, type, row) {
                        return '<a href="#" type="button" id="btn_add_role" class="btn btn-success color-opct-8">Edit</a>'
                    }
                },
            ]
        });

        // Dismiss modal
        window.onclick = (event) => {
            if (event.target == $('#modal_form_add_user')[0]) {
                $('#modal_form_add_user').hide();
            } else if(event.target == $('#modal_form_add_role')[0]) {
                $('#modal_form_add_role').hide();
            } else if(event.target == $('#modal_form_add_menu')[0]) {
                $('#modal_form_add_menu').hide();
            } else if(event.target == $('#modal_form_add_user_role')[0]) {
                $('#modal_form_add_user_role').hide();
            } else if(event.target == $('#modal_form_add_role_menu')[0]) {
                $('#modal_form_add_role_menu').hide();
            }
            
        }

        // Handling form request
        $("form").submit(async function( event ) {
            event.preventDefault();
            var formData = $( this ).serializeArray();

            // Make Loading
            $('#loading').addClass('spinner-border spinner-border-sm');
            $('#loading').attr('role','status');
            $('#loading').attr('aria-hidden','true');

            switch (formData[0].value) {
                case 'User':
                    if(formData[1].value) {
                        console.log("Update on User");
                    } else {
                        await $.ajax({
                            url: "/dgs/api/v2/user",
                            type: "POST",
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            beforeSend: function (request) {
                                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                            },
                            success: function(result) { 
                                if(result.code==200){
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Good Job!',
                                        text: result.data,
                                    }).then(()=>{
                                        location.reload();
                                    });
                                }else{
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: result.data,
                                    });
                                }
                            }, 
                            error: function(e) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: e.statusText,
                                });
                            }
                        });
                        $('#modal_form_add_user').hide();
                    }
                    break;
            
                case 'Role':
                    if(formData[1].value) {
                        console.log("Update on Role");
                    } else {
                        console.log("Insert on Role");
                    }
                    break;
                
                case 'Menu':
                    if(formData[1].value) {
                        console.log("Update on Menu");
                    } else {
                        console.log("Insert on Menu");
                    }
                    break;
                
                case 'User Role':
                    if(formData[1].value) {
                        console.log("Update on User Role");
                    } else {
                        console.log("Insert on User Role");
                    }
                    break;

                case 'Role Menu':
                    if(formData[1].value) {
                        console.log("Update on Role Menu");
                    } else {
                        console.log("Insert on Role Menu");
                    }
                    break;

                default:
                    console.log("Default");
                    break;
            }
            $('#loading').removeClass('spinner-border spinner-border-sm');
            $('#loading').removeAttr('role');
            $('#loading').removeAttr('aria-hidden');
        });

        // ============================================== Event Form Add User =====================================
        $('#username').change( function(){
            $('#using_ldap').prop('checked', false);
            $('#password').prop('readonly', false);
            $('#nama_lengkap').val("");
            $('#no_telp').val("");
            $('#tgl_lahir').val("");
        })

        $('#using_ldap').change( function() {
            if(this.checked) {
                if($('#username').val() == ""){
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: "Mohon dilengkapi dahulu field username",
                    });
                    $('#using_ldap').prop('checked', false);
                } else {
                    $('#password').prop('readonly', false);
                    $.ajax({
                        url: '/dgs/api/v2/ldap',
                        type: "POST",
                        data: {"username": $('#username').val()},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        // beforeSend: function (request) {
                        //     request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                        // },
                        success: function(result) { 
                            if(result.code == 200){
                                $('#password').prop('readonly', true);
                                $('#nama_lengkap').val(result.data.nama_lengkap);
                                $('#no_telp').val(result.data.no_telp);
                                $('#tgl_lahir').val(result.data.tanggal_lahir);
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: result.data,
                                });
                                $('#using_ldap').prop('checked', false);
                            }
                        }, 
                        error: function(e) {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: e.statusText,
                            });
                            $('#using_ldap').prop('checked', false);
                        }
                    });
                }
            } else {
                $('#password').prop('readonly', false);
            }
        });


        // Handling using feature
        // $('#using_ldap').change( function() {
        //     if(this.checked) {
        //         if($('#username').val() == "" || $('#password').val() == "" ){
        //             Swal.fire({
        //                 type: 'error',
        //                 title: 'Oops...',
        //                 text: "Mohon dilengkapi dahulu field username dan password",
        //             });
        //             $('#using_ldap').prop('checked', false)
        //         } else {
        //             console.log('checked');
        //         }
        //     } else {
        //         console.log('no checked');
        //     }
            // $.ajax({
            //     url: $(this).attr('link'),
            //     // url: "/dacita/login",
            //     type: "DELETE",
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     beforeSend: function (request) {
            //         request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            //     },
            //     success: function(result) { 
            //         console.log(result);
            //         // if(result.code==200){
            //         //     Swal.fire({
            //         //         type: 'success',
            //         //         title: 'Good Job!',
            //         //         text: result.data,
            //         //     }).then(()=>{
            //         //         location.reload();
            //         //     });
            //         // }else{
            //         //     Swal.fire({
            //         //         type: 'error',
            //         //         title: 'Oops...',
            //         //         text: result.data,
            //         //     });
            //         // }
            //     }, 
            //     error: function(e) {
            //         Swal.fire({
            //             type: 'error',
            //             title: 'Oops...',
            //             text: e.statusText,
            //         });
            //     }
            // });
        // });
    },

    getData: function() {
        // list in modal add user field lokasi
        $.ajax({
            url: '/dgs/api/v2/lokasi',
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // beforeSend: function (request) {
            //     request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            // },
            success: function(result) { 
                if(result.code == 200){
                    result.data.forEach(element => {
                        $('#unik_lokasi').append($('<option>').val(element.unik_lokasi).text(element.nama_lokasi))
                    });
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: result.data,
                    });
                }
            }, 
            error: function(e) {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: e.statusText,
                });
            }
        });
    }
}