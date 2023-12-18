var togglePassword = document.getElementById("toggle-password");
var togglePasswordConfirm = document.getElementById("toggle-password-confirm");
var formContent = document.getElementsByClassName('form-content')[0];
var getFormContentHeight = formContent.clientHeight;

var formImage = document.getElementsByClassName('form-image')[0];
if (formImage) {
	var setFormImageHeight = formImage.style.height = getFormContentHeight + 'px';
}
if (togglePassword) {
	togglePassword.addEventListener('click', function() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	});
}

if (togglePasswordConfirm) {
	togglePasswordConfirm.addEventListener('click', function() {
	  var y = document.getElementById("password_confirmation");
	  if (y.type === "password") {
	    y.type = "text";
	  } else {
	    y.type = "password";
	  }
	});
}