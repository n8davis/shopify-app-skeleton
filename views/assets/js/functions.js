function send( uri , data , type , callback){
    var send = $.ajax({
        type: type,
        data: data ,
        url: uri

    });
    send.done(function (res, status, xhr) {
        callback(res) ;
    });
}