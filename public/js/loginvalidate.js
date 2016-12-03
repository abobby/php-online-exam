/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    $('#logForm').validate({
        rules: {
            username: "required",
            password: "required",
        },
        messages: {
            username: "Please provide valid Username",
            password: "Please provide Password"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

