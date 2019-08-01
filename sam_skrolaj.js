  function scrollpage() {
    function f() {
      window.scrollTo(0, i);
      if (status == 0) {
        i = i + 40;
        if (i >= Height) {
          status = 1;
        }
      } else {
        i = i - 40;
        if (i <= 1) { // if you don't want continue scroll then remove this if condition 
          status = 0;
        }
      }
      setTimeout(f, 0.01);
    }
    f();
  }
var Height = document.documentElement.scrollHeight;
var i = 1,
  j = Height,
  status = 0;
scrollpage();