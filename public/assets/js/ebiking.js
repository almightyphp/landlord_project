//20170504
var EB = {
	scriptName: "/index.php",
  windowClick: false,

	createXHR: function() {
	  try {
	    var XHR;
	    if(window.XMLHttpRequest) XHR = new XMLHttpRequest();
	    else if(window.ActiveXObject) XHR = new ActiveXObject("Microsoft.XMLHTTP");
	    return XHR;
	  } catch(e) { }
	},

	addEvent: function(elementTarget, eventType, functionHandler) {
	  try {
	    if(elementTarget.addEventListener) elementTarget.addEventListener(eventType, functionHandler, false);
	    else if(elementTarget.attachEvent) elementTarget.attachEvent("on" + eventType, functionHandler);
	    else elementTarget["on" + eventType] = functionHandler;
	  } catch(e) { }
	},

  toggleDisplay: function(objId) {
    var obj = document.getElementById(objId);
    obj.style.display = (obj.style.display == "block") ? "none" : "block";
  },

  setCapByCity: function(cityId) {
    try {
      var xhr = EB.createXHR();
      xhr.onreadystatechange = function() {
        if(xhr.readyState == 4) document.getElementById("formField_cap").value = xhr.responseText;
      }
      xhr.open("GET", "/" + EB.curlang() + "/ajax.html?cmd=getCapByCityId&id=" + cityId, true);
      xhr.send();
    } catch(e) { }
  },

  toggleMenu: function(status) {
    if(status == 1) {
      if(!EB.windowClick) {
        EB.windowClick = true;
        EB.addEvent(window, "click", function(e) {
          try {
            if(EB.checkParentsForId(e.target, "menuIcon")) return false;
          } catch(e) { }
          EB.toggleMenu(0);
        });
      }
      document.getElementById("mainMenu").style.display = "block";
    } else {
      document.getElementById("mainMenu").style.display = "none";
    }
  },

  checkParentsForId: function(obj, ids) {
    try {
      if(EB.inArray(obj.id, ids)) return true;
      return EB.checkParentsForId(obj.parentNode, ids);
    } catch(e) { }
    return false
  },

  inArray: function(needle, hystack) {
    hystack = hystack.split(",");
    for(i in hystack) {
      if(hystack[i] == needle) return true;
    }
    return false;
  },


  init: function() {
    if(!document.getElementById) return;
  }
}

EB.addEvent(window, "load", EB.init);

//------//


function openModal() {
  document.getElementById('myModal').style.display = "block";
}

function closeModal() {
  document.getElementById('myModal').style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  // slides[slideIndex-1].style.display = "block";
}

// $(document).on("click", "#select_timing", function () {
//   //your code
//   console.log("hello");
//   var element = $(this); // to get clicked element
// });



