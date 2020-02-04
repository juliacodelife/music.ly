$(document).ready(function(){    //w momencie zaladowania dokumentu wywolaj funkcje automatyczna
    $("#hideLogin").click(function(){
        $("#loginForm").hide();
        $("#registerForm").show();
    });
    $("#hideRegister").click(function(){
        $("#loginForm").show();
        $("#registerForm").hide();
    });
});