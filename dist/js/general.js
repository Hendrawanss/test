$(function() {
    "use strict";

    $('#logout').click(function(e) {
        e.preventDefault();
        // Call api login
        $.get("/dgs/logout", function(result){
            if(result.code==200){
                sessionStorage.removeItem("flag-wlcm");
                sessionStorage.removeItem("t.api");
                window.location = "/dgs";
            }else{
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: result.message,
                });
            }
        });
    });

});