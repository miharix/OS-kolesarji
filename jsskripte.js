function skrij(ID) {
  var x = document.getElementById("prekrskiOvire-"+ID);
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
} 