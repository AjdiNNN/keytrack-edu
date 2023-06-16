
let mainService = {
  init: function(){
    $.ajax({
      url: url+'totalsessions',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#sessionsnumber').html('<p class="item-metric">'+result.data.total_sessions+'</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'averagesessions',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#sessionsaverage').html('<p class="item-metric">'+parseFloat(result.data.avg_session_duration).toFixed(2)+'m</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'totalkeyboard',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#keyboardtotal').html('<p class="item-metric">'+result.data.total_keyboard_presses+'</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'totalmouse',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#mousetotal').html('<p class="item-metric">'+result.data.total_mouse_clicks+'</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'newlines',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#newline').html('<p class="item-metric">'+result.data.number_of_new_lines+'</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'mostfrequent',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        for(let i = 0; i<4 ; i++){
          $('#keyboardbutton'+i).html('<p class="item-metric">'+result[i].pressed+'</p>');
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'activeday',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#activeday').html('<p class="item-metric">'+result[0].active_day+'</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'distance',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#traveldistance').html('<p class="item-metric">'+parseInt(result.total_distance)+'</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'timebetweenpress',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#presstime').html('<p class="item-metric">'+parseFloat(result.avg_time_between_presses).toFixed(2)+'s</p>');
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'longestsession',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        console.log(result);
        $('#longestsession').html('<p class="item-metric">'+result.session_duration+'</p>');
        $('#longestsessionbutton').attr("onclick","mainService.load("+result.id+")");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'earlystart',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#earlysession').html('<p class="item-metric">'+result.earliest_start_time+'</p>');
        $('#earlysessionbutton').attr("onclick","mainService.load("+result.id+")");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'latestend',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#latesession').html('<p class="item-metric">'+result.latest_end_time+'</p>');
        $('#latesessionbutton').attr("onclick","mainService.load("+result.id+")");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
    $.ajax({
      url: url+'mostunique',
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (result) {
        $('#mostunique').html('<p class="item-metric">'+result.distinct_keys_pressed+'</p>');
        $('#mostuniquebutton').attr("onclick","mainService.load("+result.session_id+")");
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
  },
  load: function(id){
    $.ajax({
      url: url+'session/'+id,
      type: 'GET',
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', localStorage.getItem('token'))
      },
      success: function (data) {
        $('#sessionid').html(id);
        let html = '';
        for (let i = 0; i < data.mouse.length; i++) {
          html += `
            <tr>
              <td>` + data.mouse[i].position + `</td>
              <td>` + data.mouse[i].pressedAt + `</td>
            </tr>`;
        }
        $('#mousetablebody').html(html);
        html = '';
        for (let i = 0; i < data.keyboard.length; i++) {
          html += `
            <tr>
              <td>` + data.keyboard[i].pressed + `</td>
              <td>` + data.keyboard[i].pressedAt + `</td>
            </tr>`;
        }
        $('#keyboardtablebody').html(html);
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        toastr.error(XMLHttpRequest.responseJSON.message)
        userService.logout()
      }
    });
  },

  logout: function(){
    localStorage.clear();
    window.location.replace("login.html");
  }
};
