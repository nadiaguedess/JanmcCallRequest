function ShowAlert(msg_title, msg_body, msg_type) {
    var AlertMsg = $('div[role="alert"]');
    $(AlertMsg).find('h5').html(msg_title);
    $(AlertMsg).find('p').html(msg_body);
    $(AlertMsg).removeAttr('class');
    $(AlertMsg).addClass('alert alert-' + msg_type);
    $(AlertMsg).show();
}

$(".btnShiplink").click(function(e) { // changed
    e.preventDefault();  
    var idbutton = $(this).parent().parent().parent().parent().children(":first");
    console.log($(this).parent().parent().parent().parent().children(":first").serialize());
    $.ajax({
           type: "POST",
           url: "ShipLink_POST.php",
           data: $(this).parent().parent().parent().parent().children(":first").serialize(), 
           success: function (data) {
            obj = jQuery.parseJSON(data);  
            ShowAlert(obj[0], obj[1], obj[2]);          
          },
          error: function(xhr, textStatus, error){
            ShowAlert("Attention", "Couldn't execute request.", "danger");
          }
    });    
});
