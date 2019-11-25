$(document).ready(function() {
  $("body").keypress(function(e) {
    var key = e.which;
    if (key == 13) {
      // the enter key code
      $("#login-btn").click();
      return false;
    }
  });
  $(document).on("click", "#login-btn", function() {
    $("#Email, #UserPassword").removeClass("error");
    $(".error-div ul").html("");
    var error = "";
    var Email = $("#Email").val();
    var UserPassword = $("#UserPassword").val();
    if (Email == "") {
      $("#Email").addClass("error");
      error += "<li>Please enter Email</li>";
    }
    if (UserPassword == "") {
      $("#UserPassword").addClass("error");
      error += "<li>Please enter UserPassword</li>";
    }
    if (error != "") {
      $(".error-div ul").html(error);
      return;
    } else {
      API.call(
        "/login",
        function(data) {
          if (data.error == 0) {
            error += "<li>" + data.message + "</li>";
            $(".error-div ul").html(error);
          } else {
            error += "<li>" + data.message + "</li>";
            $(".error-div ul").html(error);
          }
        },
        { Email: Email, UserPassword: UserPassword }
      );
    }
  });
});
