var loginEvent = {
    init:function() {
        this.login();
    },

    login:function(){
        $('#btn-sbmt').click( async function(e){
            // Disable behavior function in form
            e.preventDefault();
            // Disabling button
            this.disabled = true;
            // Make Loading
            $('#loading').addClass('spinner-border spinner-border-sm');
            $('#loading').attr('role','status');
            $('#loading').attr('aria-hidden','true');
            // this.text('Mohon Tunggu...');
            // Validate
            let data = $("#loginform").serializeArray();
            
            if(data[0].value == "" || data[1].value == ""){
                // Enabling button
                this.disabled = false;
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: "Field harus terisi semua",
                }); 
            } else {
                // Call api login
                let login = await $.ajax({
                    url: "/dgs/login",
                    type: "POST",
                    data: $("#loginform").serializeArray(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        console.log(result);
                        if(result.code==200){
                            // Create session username and token
                            sessionStorage.setItem("t.api", result.token);
                            // redirect to dacita
                            window.location = "/dgs/please-wait";
                        }else{
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: result.message,
                            });
                        }
                    }, 
                    error: function(e) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: e.statusText,
                        }).then((result) => {
                            if (result.value) {
                                window.location = "/dgs/login";
                            }
                        });
                    }
                })
                // Enabling button
                if(login.code != 200) {
                    $('#loading').removeClass('spinner-border spinner-border-sm');
                    $('#loading').removeAttr('role');
                    $('#loading').removeAttr('aria-hidden');
                    this.disabled = false;
                }
            }
        })
    }
}