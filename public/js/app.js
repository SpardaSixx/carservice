// Fetch Services data to Modal
function callServices(route) {
    $('.appendedRow').remove();

    $.get(route, function (data) {
        data.forEach((q) =>{
            if(q['event'] == 'regisztralt'){
                var changeRoute = '/services/changedate/' + q['car_id'];

                $.get(changeRoute, function (data) {
                    $('#servicesModalBody').append("<tr class='appendedRow'><td>" + q['log_number'] + "</td><td>" + q['event'] + "</td><td>" + data + "</td><td>" + q['document_id'] + "</td></tr>");
                })
            } else{
                $('#servicesModalBody').append("<tr class='appendedRow'><td>" + q['log_number'] + "</td><td>" + q['event'] + "</td><td>" + q['event_time'] + "</td><td>" + q['document_id'] + "</td></tr>");
            }
        })

        $('#servicesModal').modal('show');
   })
}

function checkAppend(){
    var appendedRow = document.getElementsByClassName("appendedRow");
    
    if(appendedRow.length > 0){
        return true;
    }
}
