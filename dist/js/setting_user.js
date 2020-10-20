let Setting = {
    init: function() {
        
        this.getDataLokasiModal();
        this.attachEventElement();
    },

    resetFormAddUser: function() {
        $('#username').val("");
        $('#using_ldap').prop('checked', false);
        $('#password').prop('readonly', false);
        $('#nama_lengkap').val("");
        $('#no_telp').val("");
        $('#tgl_lahir').val("");
        $('#jabatan').val("");
        $('#nik').val("");
        $('#unik_lokasi').val("");
    },

    attachEventElement: function() {
        var modaluser = $('#modal_form_add_user');
        var modaluserrole = $('#modal_form_add_user_role');

        // Table User
        var tb_user = $('#tb_user').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print', {
                    text: 'Add User',
                    className: 'btn-danger',
                    action: function ( e, dt, node, config ) {
                        modaluser.show();
                    }
                }
            ],
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
                        text: resp.statusText,
                    });
                }
            },
            scrollY:        "300px",
            scrollX:        true,
            scrollCollapse: true,
            fixedColumns:   {
                rightColumns: 2
            },
            "columns": [
                { data: 'id'},
                { data: 'username'},
                { data: 'password'},
                { data: 'nama_lengkap'},
                { data: 'nik'},
                { data: 'jabatan'},
                { data: 'tl'},
                { data: 'unik_lokasi'},
                { data: 'no_telp'},
                { data: 'role'},
                { data: 'created_at'},
                { data: 'updated_at'},
                { data: 'action'},
            ],
        });

        // Table User Role
        var tb_role = $('#tb_user_role').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'print'
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
                        return '<a href="#" type="button" id="btn_edit_user_role" class="btn btn-success color-opct-8">Edit</a>'
                    }
                },
            ],
        });

        // Dismiss modal
        window.onclick = (event) => {
            if (event.target == $('#modal_form_add_user')[0]) {
                $('#modal_form_add_user').hide();
                this.resetFormAddUser();
            } else if(event.target == $('#modal_form_add_user_role')[0]) {
                $('#modal_form_add_user_role').hide();
            }
        }

        // Handling form request
        $("form").submit(async function( event ) {
            event.preventDefault();
            var formData = $( this ).serializeArray();
            var state = 0; var resultCheck = 0;

                // Make Loading
            $('#loading').addClass('spinner-border spinner-border-sm');
            $('#loading').attr('role','status');
            $('#loading').attr('aria-hidden','true');

            switch (formData[0].value) {
                case 'User':
                    if(formData[1].value) {
                        // Update data user
                        console.log("Update on User");
                        await $.ajax({
                            url: "/dgs/api/v2/user/",
                            type: "PUT",
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
                                    }).then(()=>{
                                        location.reload();
                                    });
                                }
                            }, 
                            error: function(e) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: e.statusText,
                                }).then(()=>{
                                    location.reload();
                                });
                            }
                        });
                    } else {
                        // Insert data user
                        // Check field form is empty
                        formData.forEach(function(item,index) {
                            
                            if(item.name == "using_ldap" && item.value == "on") {
                                state = 1;
                            } 

                            if(item.value == ""){
                                
                                if(state == 1) {
                                    if(item.name != "password" && item.name != "id" ){
                                        Swal.fire({
                                            type: 'error',
                                            title: 'Oops...',
                                            text: 'Form harus dilengkapi dulu1',
                                        });
                                        resultCheck = 1;
                                    } else {
                                        resultCheck = 0;
                                    }
                                } else {
                                    if( item.name != "id" ){
                                        Swal.fire({
                                            type: 'error',
                                            title: 'Oops...',
                                            text: 'Form harus dilengkapi dulu2',
                                        });
                                        resultCheck = 1;
                                    } else {
                                        resultCheck = 0;
                                    }
                                }
                            }
                        }) 
                        if(resultCheck == 0) {
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
                                        }).then(()=>{
                                            location.reload();
                                        });
                                    }
                                }, 
                                error: function(e) {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: e.statusText,
                                    }).then(()=>{
                                        location.reload();
                                    });
                                }
                            });
                        }
                    }
                    break;
                
                case 'User Role':
                    if(formData[1].value) {
                        console.log("Update on User Role");
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
            $('#jabatan').val("");
            $('#nik').val("");
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
                        url: '/dgs/api/v2/user/ldap',
                        type: "POST",
                        data: {"username": $('#username').val()},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function (request) {
                            $("body").addClass("loading"); 
                            request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
                        },
                        success: function(result) { 
                            if(result.code == 200){
                                $('#password').prop('readonly', true);
                                $('#nama_lengkap').val(result.data.nama_lengkap);
                                $('#no_telp').val(result.data.no_telp);
                                $('#tgl_lahir').val(result.data.tanggal_lahir);
                                $('#jabatan').val(result.data.jabatan);
                                $('#nik').val(result.data.nik);
                                $('#unik_lokasi').focus();
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: result.data,
                                });
                                $('#using_ldap').prop('checked', false);
                            }
                            $("body").removeClass("loading");
                        }, 
                        error: function(e) {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: e.statusText,
                            });
                            $('#using_ldap').prop('checked', false);
                            $("body").removeClass("loading");
                        }
                    });
                }
            } else {
                $('#password').prop('readonly', false);
            }
        });

        // ============================ Handle Action button ==============================z
        tb_user.on( 'click', '#btn_edit_user', function () {
            var data = tb_user.row( $(this).parents('tr') ).data()
            var date = data.tl
            date = date.split(" ");
            $('#title-form-add-user').text('Form Edit User');
            if(data.password == "Ldap"){
                $('#using_ldap').prop('checked', true);
                $('#password').prop('readonly', true);
            } else {
                $('#using_ldap').prop('checked', false);
                $('#password').prop('readonly', false);
                $('#password').prop('required', false);
            }
            $('#id').val(data.id);
            $('#password').prop('placeholder','Isi jika ingin mengganti password user');
            $('#username').val(data.username);
            $('#nama_lengkap').val(data.nama_lengkap);
            $('#no_telp').val(data.no_telp);
            $('#tgl_lahir').val(date[4]+"-"+(new Date(Date.parse(date[1] +" 1, 2012")).getMonth()+1)+"-"+date[0]);
            $('#jabatan').val(data.jabatan);
            $('#nik').val(data.nik);
            $('#unik_lokasi').val(data.unik_lokasi);
            modaluser.show();
        } );

        tb_role.on( 'click', '#btn_edit_user_role', function (e) {
            var data = tb_role.row( $(this).parents('tr') ).data()
            $('#user_role_id').val(data.id);
            $('#user_role_name').val(data.nama_lengkap);
            $('#role').val("");
            modaluserrole.show();
            console.log(data)
        } );


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

    getListRole: function() {
        // list in modal edit user role field role
        $.ajax({
            url: '/dgs/api/v2/role/get',
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
            success: function(result) { 
                if(result.code == 200){
                    result.data.forEach(element => {
                        $('#role').append($('<option>').val(element.id).text(element.role_name))
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
    },

    getDataLokasiModal: function() {
        // list in modal add user field lokasi
        $.ajax({
            url: '/dgs/api/v2/lokasi',
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer "+sessionStorage.getItem("t.api"));
            },
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