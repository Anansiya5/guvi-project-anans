
 alert("Welcome User");

function ajaxCall() {
    $.ajax({
        type: "GET",
        url: 'php/profile.php',
        dataType: "html",
        success: function (data) {
            $('#mydata').html(data);

        }
    })
};
ajaxCall();

 function ajaxCall() {
     $.ajax({

       url:
            'php/profile.php',

         type: "GET",

         success: function (data) {
             var x = JSON.stringify(data);
             console.log(x);
             console.log(JSON.parse(data));
             var d = (JSON.parse(data));

             console.log(d.name);
             for (const [key, value] of Object.entries(d)) {
                 $('input[name={key}]').val(value);
                 console.log(`${value}`);
             }
         },

         error: function (error) {
             console.log(`Error ${error}`);
         }
     });
 }
 ajaxCall();