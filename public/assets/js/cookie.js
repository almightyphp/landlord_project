/**
 * @package WizLab Generic Purpose Class
 * @author WizLab.it
 * @version 20170523.028 - Hard linked file
 */

var COOKIE = {
  cookieName: "cookieAgree",
  cookieFirstPageName: "cookieFirstPage",
  topBarHeight: 40,
  topBarColor: "#333",
  pageOriginalMarginTop: null,
  infoText: "Questo sito web utilizza i cookie per migliorare la navigazione. Utilizzando il sito si intende accettata la Privacy Policy",
  agreeButton: "Accetto",
  readMore: { text:"Ulteriori informazioni", link:"/cookie.html", target:"" },
  GAstatus: false,

  addEvent: function(elementTarget, eventType, functionHandler) {
    try {
      if(elementTarget.addEventListener) elementTarget.addEventListener(eventType, functionHandler, false);
      else if(elementTarget.attachEvent) elementTarget.attachEvent("on" + eventType, functionHandler);
      else elementTarget["on" + eventType] = functionHandler;
    } catch(e) { }
  },

  checkAgree: function() {
    return (document.cookie.indexOf(COOKIE.cookieName + "=ok") != -1);
  },

  setAgree: function() {
    var d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = COOKIE.cookieName + "=ok; path=/; expires=" + d.toGMTString();
    document.getElementById("cookieAgreementBar").style.display = "none";
    document.body.style.marginTop = COOKIE.pageOriginalMarginTop;
  },

  GAtrigger: function(id) {
    if(document.cookie.indexOf("cookieFirstPage=ok") == -1) {
      COOKIE.addEvent(window, "scroll", function() { COOKIE.GAactivate(id); });
      setTimeout(function() { COOKIE.GAactivate(id); }, 15000);
    } else {
      COOKIE.GAactivate(id);
    }
  },

  GAactivate: function(id) {
    if(!COOKIE.GAstatus) {
      COOKIE.GAstatus = true;
      ga("create", id, "auto");
      ga("send", "pageview");
    }
  },

  init: function() {
    if(!document.getElementById) return;
    if(!COOKIE.checkAgree()) {
      var cookieDiv = document.createElement("div");
      cookieDiv.id = "cookieAgreementBar";
      cookieDiv.style.cssText = "position:fixed; top:0; width:100%; margin:0; line-height:" + COOKIE.topBarHeight + "px; text-align:center; background-color:" + COOKIE.topBarColor + "; color:white; font-family:sans-serif; font-size:12px; z-index:100;";
      cookieDiv.innerHTML = COOKIE.infoText + "<a href='" + COOKIE.readMore.link + "' style='margin:0 0 0 20px; color:#AAA; text-decoration:underline; font-size:9px;'>" + COOKIE.readMore.text + "</a>" + "<input type='button' value=\"" + COOKIE.agreeButton + "\" onclick='COOKIE.setAgree();' style='background-color:#5BB75B; color:#FFF; font-weight:bold; border:none; padding:5px; cursor:pointer; font-wize:12px; margin:0 0 0 20px; display:inline; float:none; font-size:12px; width:auto; min-width:0;' />";
      document.body.appendChild(cookieDiv);
      COOKIE.pageOriginalMarginTop = document.body.style.marginTop;
      document.body.style.marginTop = cookieDiv.clientHeight + "px";
    }
    var d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = COOKIE.cookieFirstPageName + "=ok; path=/; expires=" + d.toGMTString();
  }
}

COOKIE.addEvent(window, "load", COOKIE.init);