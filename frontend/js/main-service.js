
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
        $('#keyboardbutton').html('<p class="item-metric">'+result.data.pressed+'</p>');
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
