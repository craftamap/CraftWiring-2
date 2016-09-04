function loadgpio()
{
  $.ajax(
    {
      url: 'config/config-gpio.json'
    }
  ).done(
    function(a) {
      $('#gpio .configcontainer').not('.new').remove();
      //console.log(a);
      for (var b in a) {
        if($('.configcontainer[group*="gpio"]').length === 0){
          $('#gpio h2').after("<div class='configcontainer' group='gpio"+a[b].pin+"'></div>");
        } else {
          $('.configcontainer[group*="gpio"]').last().after("<div class='configcontainer' group='gpio"+a[b].pin+"'></div>");
        }
        $(".configcontainer[group='gpio"+a[b].pin+"']").append("<div class='lable configlable' group='gpio"+a[b].pin+"'>"+a[b].pin+"</div>");
        $(".configcontainer[group='gpio"+a[b].pin+"']").append("<input value='"+a[b].name+"' group='gpio"+a[b].pin+"'/>");
        $(".configcontainer[group='gpio"+a[b].pin+"']").append("<select class='last' group='gpio"+a[b].pin+"'><option value='1'>standard</option><option value='2'>latching</option></select>");
        if(a[b].mode == 1) {
          $("select[group='gpio"+a[b].pin+"'] option[value='1']").attr("selected","selected");
        } else if(a[b].mode == 2) {
          $("select[group='gpio"+a[b].pin+"'] option[value='2']").attr("selected","selected");
        }
        $(".configcontainer[group='gpio"+a[b].pin+"']").append('<i class="material-icons md-24 md-dark" onclick=\"editgpio(\''+a[b].pin+'\')\">save</i>');
        $(".configcontainer[group='gpio"+a[b].pin+"']").append('<i class="material-icons md-24 md-dark" onclick=\"deletegpio('+a[b].pin+')\">delete</i>');
      }
    }
  );
}


function loadsocket() {
  $.ajax(
    {
      url: 'config/config-socket.json'
    }
  ).done(
    function(a){
      //clear
      $('#socket .configcontainer').not('.new').remove();
      //homecode
      $('#socket h2').after("<div class='configcontainer' group='sockethomecode'></div>");
      $(".configcontainer[group='sockethomecode']").append("<div class='lable'  group='sockethomecode'>homecode</div>");
      $(".configcontainer[group='sockethomecode']").append("<input value='"+a.homecode+"' style='width: 140px' class='last' group='sockethomecode'/>");
      $(".configcontainer[group='sockethomecode']").append('<i class="material-icons md-24 md-dark" onclick="edithomecode()">save</i>');

      var a2 = a.sockets;
      //sockets
      for(var b in a2) {
        if($('.configcontainer[group^="socket"]').length === 0){
          $('#socket h2').after("<div class='configcontainer' group='socket"+a2[b].socketadr+"'></div>");
        } else {
          $('.configcontainer[group*="socket"]').last().after("<div class='configcontainer' group='socket"+a2[b].socketadr+"'></div>");
        }
        $(".configcontainer[group='socket"+a2[b].socketadr+"']").append("<div class='lable configlable' group='socket"+a2[b].socketadr+"'>"+a2[b].socketadr+"</div>");
        $(".configcontainer[group='socket"+a2[b].socketadr+"']").append("<input value='"+a2[b].name+"' class='last' group='socket"+a2[b].socketadr+"'/>");
        $(".configcontainer[group='socket"+a2[b].socketadr+"']").append('<i class="material-icons md-24 md-dark" onclick="editsocket(\''+a2[b].socketadr+'\')">save</i>');
        $(".configcontainer[group='socket"+a2[b].socketadr+"']").append('<i class="material-icons md-24 md-dark" onclick="deletesocket(\''+a2[b].socketadr+'\')">delete</i>');

      }
    }
  );
}

function edithomecode(){
  var n = $(".configcontainer[group='sockethomecode'] input").val();
  $.ajax(
    {
      url: "libs/editsocket.php",
      method: "POST",
      dataType: "json",
      data:
        {
          action: "homecode",
          homecode: n
        }
    }
  ).done(function(a){
    if(a.status=="success"){
      loadsocket();
    } else {
      console.log(a);
      loadsocket();
    }
  });
}

function editsocket (i) {
  var n = $(".configcontainer[group='socket"+i+"'] input").val();
    $.ajax(
      {
        url: "libs/editsocket.php",
        method: "PUT",
        dataType: "json",
        data:
          {
            action: "name",
            id: i,
            name: n
          }
      }
    ).done(function(a){
      loadsocket();
    });
}


function deletesocket(i) {
  if(confirm("Bist du sicher, dass du Socket "+i+" löschen  willst?")) {
    $.ajax(
      {
        url: "libs/editsocket.php",
        method: "DELETE",
        dataType: "json",
        data:
          {
            id: i
          }
      }
    ).done(function(a)
      {
        loadsocket();
      }
    );
  }
}


function addsocket(){
i = $(".configcontainer[group='new.s'] input[type='number']").val();
n = $(".configcontainer[group='new.s'] input[type='text']").val();
  $.ajax(
    {
      url: "libs/editsocket.php",
      method: "POST",
      dataType: "json",
      data:
        {
          id: i,
          name: n
        }
    }
  ).done(function(a){
    console.log(a);
    loadsocket();
  });
}




function editgpio (i) {
  var n = $(".configcontainer[group='gpio"+i+"'] input").val();
  var m = $(".configcontainer[group='gpio"+i+"'] select").val();
    $.ajax(
      {
        url: "libs/editgpio.php",
        method: "PUT",
        dataType: "json",
        data:
          {
            id: i,
            name: n,
            mode: m
          }
      }
    ).done(function(a){
      loadgpio();
    });
}


function deletegpio(i) {
  if(confirm("Bist du sicher, dass du Socket "+i+" löschen  willst?")) {
    $.ajax(
      {
        url: "libs/editgpio.php",
        method: "DELETE",
        dataType: "json",
        data:
          {
            id: i
          }
      }
    ).done(function(a)
      {
        loadgpio();
      }
    );
  }
}


function addgpio(){
i = $(".configcontainer[group='new.g'] input[type='number']").val();
n = $(".configcontainer[group='new.g'] input[type='text']").val();
s = $(".configcontainer[group='new.g'] select").val();

  $.ajax(
    {
      url: "libs/editgpio.php",
      method: "POST",
      dataType: "json",
      data:
        {
          id: i,
          name: n,
          mode: s
        }
    }
  ).done(function(a){
    console.log(a);
    loadgpio();
  });
}
