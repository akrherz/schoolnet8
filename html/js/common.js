window.name = "basewindow";
function new_window_big(url) {
        link = 
        window.open(url,"Link","toolbar=0,location=0,directories=0,status=0,menubar=no,scrollbars=yes,resizable=yes,width=820,height=620");
} 
function selectWindow() {
 window.open('select.phtml', 'Select', 'toolbar=0,location=0,directories=0,status=0,menubar=no,scrollbars=yes,resizable=yes,width=425,height=700');
}
function help_window(helpitem) {
        link =
       window.open('/info/mfaq.phtml#'+ helpitem, "HELP","toolbar=0,location=0,directories=0,status=0,menubar=no,scrollbars=yes,resizable=yes,width=360,height=440");
}
function new_window(url) {
        link =
       window.open(url,"Link","toolbar=0,location=0,directories=0,status=0,menubar=no,scrollbars=yes,resizable=yes,width=360,height=440");
}

function setLayerDisplay( lyr , b) {
  if ( document.getElementById ) {
    var w = document.getElementById(lyr);
    w.style.display = b;
  }
}

function reverseLayer( lyr, pos ) {
  if ( document.getElementById ) {
    var w = document.getElementById(lyr);
    var i = 0;
    if (w.style.display == "none") { 
       w.style.display = "block";
       i = 1; 
    }
    else { 
      w.style.display = "none"; 
    }

   /* Now we save this setting as a cookie! */
   var results = document.cookie.match ( 'siteconfig=(.*?)(;|$)' );
   var c = "11111111";
   if ( results )
     c = results[1];

   var tokens = c.split('');
   tokens[pos] = i;
   c = tokens.join('');
   set_cookie("siteconfig", c, 2019,1,1);
  }
}

function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
  var cookie_string = name + "=" + escape ( value );

  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }

  if ( path )
        cookie_string += "; path=" + escape ( path );

  if ( domain )
        cookie_string += "; domain=" + escape ( domain );
  
  if ( secure )
        cookie_string += "; secure";
  
  document.cookie = cookie_string;
}
function addToFavorites(urlAddress, pageName) {
 if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)) {
  window.external.AddFavorite(urlAddress,pageName);
  } else if (navigator.appName == "Netscape") {
    window.sidebar.addPanel(pageName,urlAddress,"");
  } else {
    alert("Press CTRL-D (Netscape) or CTRL-T (Opera) to bookmark");
  }

}
