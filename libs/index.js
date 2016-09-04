function gpio() {
  $.ajax(
    {
      url: "config/config-gpio.json"
    }
  ).done(
    function(a)
      {
        for(var i = 0; i < a.length; i++) {
          if(a[i].mode === 0)
          {

          }
          if(a[i].mode == 1)
          {
            $("#gpio").append("<div class='buttoncontainer'><div class='lable'></div><button onclick='sendgpio("+a[i].pin+","+a[i].mode+", 1)'>On</button><button class='last'  onclick='sendgpio("+a[i].pin+","+a[i].mode+", 0)'>Off</button></div>");
          }
          else if(a[i].mode == 2)
          {
            $("#gpio").append("<div class='buttoncontainer'><div class='lable'></div><button class='last' onclick='sendgpio("+a[i].pin+","+a[i].mode+")'>Switch</button></div>");
          }
          $("#gpio .lable").last().text(a[i].name+" ("+a[i].pin+"):");
        }
      }
  );
}

function sockets() {
  $.ajax(
    {
      url: "config/config-socket.json"
    }
  ).done(
    function(a)
      {
        for(var i = 0; i < a.sockets.length; i++) {
          $("#socket").append("<div class='buttoncontainer'><div class='lable'></div><button onclick='sendsocket("+a.homecode+","+a.sockets[i].socketadr+", 1)'>On</button><button class='last' onclick='sendsocket("+a.homecode+","+a.sockets[i].socketadr+", 0)'>Off</button></div>");
          $(".lable").last().text(a.sockets[i].name+" ("+a.sockets[i].socketadr+"):");

        }
      }
  );
}




function sendsocket(h, i, s){
  $.ajax(
    {
      url: "libs/sendsocket.php",
      method: "POST",
      data:
      {
        homecode: h,
        id: i,
        status: s
      }
    }
  ).done();

}


function sendgpio(i, m, s){
  $.ajax(
    {
      url: "http://192.168.178.88/CraftWiring-2/libs/sendgpio.php",
      method: "POST",
      data:
      {
        mode: m,
        pin: i,
        status: s
      }
    }
  ).done();

}
