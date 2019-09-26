var submit;

function Clicked(button) {
  submit = button;
}

function isTop() {
  if (window.pageYOffset == 0) {
    document.getElementById("su").style.visibility = "hidden";
  } else {
    document.getElementById("su").style.visibility = "visible";
  }
}

function showSizeChanger() {
  document.getElementsByClassName("changetextsize")[0].style.display = "block";
}

function EditorValidation() {
  var fn = document.intestazione.titolo;
  return fname_validation(fn);
}

function blockEditorValidation() {
  var fn = document.blockname.nome;
  return fname_validation(fn);
}

function loginValidation() {
  var us = document.loginname.name;
  var pw = document.loginname.pass;
  if (us.value.length < 2 || pw.value.length < 4) {
    alert("Il login è fallito perché il campo Username deve essere lungo almeno 2 caratteri e Password almeno 4!");
    return false;
  }
}

function registrationValidation() {
  var us = document.registrationname.name;
  var pw1 = document.registrationname.pass1;
  var pw2 = document.registrationname.pass2;
  if (pw1.value !== pw2.value) {
    alert("Le password sono diverse.");
    return false;
  } else if (us.value.length < 2 && pw1.value.length < 4) {
    alert("Username deve essere lungo almeno 2 caratteri e Password almeno 4");
    return false;
  } else if (us.value.length < 2) {
    alert("Username deve essere lungo almeno 2 caratteri");
    return false;
  } else if (pw1.value.length < 4) {
    alert("Password deve essere lunga almeno 4 caratteri");
    return false;
  }
}

function newPassValidation() {
  var pwO = document.changePass.oldpass;
  var pw1 = document.changePass.newpass1;
  var pw2 = document.changePass.newpass2;
  if (pwO.value.length < 4) {
    alert("La vecchia password inserita non è corretta.");
    return false;
  } else if (pw1.value !== pw2.value) {
    alert("Le password sono diverse.");
    return false;
  } else if (pw1.value.length < 4) {
    alert("La password deve essere lunga almeno 4 caratteri");
    return false;
  }
}

function deleteAccValidation() {
  var pw1 = document.deleteAcc.oldpass1;
  var pw2 = document.deleteAcc.oldpass2;
  if (pw1.value.length < 4) {
    alert("La vecchia password inserita non è corretta.");
    return false;
  } else if (pw1.value !== pw2.value) {
    alert("Le password sono diverse.");
    return false;
  } else {
    var r = confirm("Sei veramente sicuro di voler eliminare l'account? Questa azione é irreversibile!");
    if (r == false) return false;
  }
}

function makeAdminValidation() {
  if (submit.name == "setAdmin") {
    var r = confirm("Sei veramente sicuro? Una volta reso admin questo utente non potrai più gestirlo! Continua solo se ti fidi!");
    if (r == false) return false;
  }
}

function signalValidation() {
  var r = confirm("Sei sicuro di voler segnalare l'articolo?");
  if (r == false) return false;
}

function fname_validation(fn) {
  var fn_len = fn.value.length;
  if (fn_len == 0) {
    alert("Attenzione, il primo campo è obbligatorio!");
    return false;
  }
}

function normal() {
  var normal = document.body.style.fontSize = "medium";
  sessionStorage.setItem("font-size", normal);
}

function medium() {
  var medium = document.body.style.fontSize = "large";
  sessionStorage.setItem("font-size", medium);
}

function bigger() {
  var big = document.body.style.fontSize = "larger";
  sessionStorage.setItem("font-size", big);
}

function getSize() {
  var size = sessionStorage.getItem("font-size");
  document.body.style.fontSize = size;
}

window.onload = function () {
  getSize();
  isTop();
  showSizeChanger();
};
window.onscroll = function () {
  isTop()
};