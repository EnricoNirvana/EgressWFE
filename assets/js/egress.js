$(function() {  
  $('.error').hide();  
  $(".loginbutton").click(function() {  
    // validate and process form here  
    $('.error').hide();  
    var fname = $("input#fname").val();  
    if (fname == "") {  
      $("label#fname_error").show();  
      $("input#firstname").focus();  
      return false;  
    }  
    var lname = $("input#lname").val();  
    if (lname == "") {  
      $("label#lname_error").show();  
      $("input#lname").focus();  
      return false;  
    }  
    var pw = $("input#pw").val();  
    if (pw == "") {  
      $("label#pw_error").show();  
      $("input#pw").focus();  
      return false;  
    }  
    
    document.onmousemove=positiontip
    
  //  var dataString = 'firstname='+ fname + '&lastname=' + lname + '&password=' + pw;
  //  $.ajax({
  //    type: "POST",
  //    url: "auth/login",
  //    data: dataString,
  //    success: function() {
  //      alert("form should be submitted");
  //    },
  //    error: function() {
  //      $('#login').html("<div id='exmodaldlg'>You're Fucked...</div>");
  //    }
  //    
  //  });

    return true;
  });
  
    $(".acctreqbutton").click(function() {  
    // validate and process form here  
    $('.error').hide();  
    var afname = $("input#afname").val();  
    if (afname == "") {  
      $("label#afname_error").show();  
      $("input#afname").focus();  
      return false;  
    }  
    var alname = $("input#alname").val();  
    if (alname == "") {  
      $("label#alname_error").show();  
      $("input#alname").focus();  
      return false;  
    }  
    var rfname = $("input#rfname").val();  
    if (rfname == "") {  
      $("label#rfname_error").show();  
      $("input#rfname").focus();  
      return false;  
    }  
    var rlname = $("input#rlname").val();  
    if (rlname == "") {  
      $("label#rlname_error").show();  
      $("input#rlname").focus();  
      return false;  
    }  
    var email = $("input#email").val();  
    if (email == "") {  
      $("label#email_error").show();  
      $("input#email").focus();  
      return false;  
    }  

    var pw = $("input#password").val();  
    if (pw == "") {  
      $("label#password_error").show();  
      $("input#password").focus();  
      return false;  
    }  

    var repw = $("input#repassword").val();  
    if (repw == "") {  
      $("label#repassword_error").show();  
      $("input#repassword").focus();  
      return false;  
    }  
    
    if(repw != pw) {  
      $("label#repassword_error").show();  
      $("input#repassword").focus();  
      return false;  
    }
    
    var dataString = 'avfirstname='+ afname + '&avlastname=' + alname + '&rlfirstname='+ rfname + '&rllastname=' + rlname +'&password=' + pw + '&repassword=' + repw + '&email=' + email;
//    alert (dataString);
//    $.ajax({
//      type: "POST",
//      url: "bin/process.php",
//      data: dataString,
//      success: function() {
//        alert ('success');
//        $('#login').html("<div id='message'>Login Complete!</div>");
//      },
//      error: function() {
//        alert ('error');
//        $('#login').html("<div id='exmodaldlg'>You're Fucked...</div>");
//      }
//      
//    });
    return true;
  });    
});

<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/
/*
     Modified by Warin Cascabel to calculate coordinates when over ocean regions.
*/
var offsetxpoint=-20 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
var savex = 0;
var savey = 0;
if (ie||ns6)
{
  var tipobj=document.all? document.all["tooltip"] : document.getElementById? document.getElementById("tooltip") : ""
  var imgobj=document.all? document.all["quickmap"] : document.getElementById? document.getElementById("quickmap") : ""
  var mapobj=document.all? document.all["mapcoords"] : document.getElementById? document.getElementById("mapcoords") : ""

  var mapleft = maptop = 0;
  var obj = imgobj;
  if (obj.offsetParent) do
  {
    mapleft += obj.offsetLeft;
    maptop += obj.offsetTop;
  } while (obj = obj.offsetParent);
}


function ietruebody(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function tip(thetext, thecolor, thewidth){
	if (ns6||ie){
	if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
	if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
	tipobj.innerHTML=thetext
	enabletip=true
	return false
	}
}

function truncate(n)
{
  return Math[n>0?"floor":"ceil"](n);
}

function movetip() {
  if (enabletip)
  {
    var thex = Math.floor((savex-mapleft) / <?php echo $resolution; ?>) + <?php echo $min; ?>;
    var they = <?php echo $max; ?> - (Math.floor((savey-maptop) / <?php echo $resolution; ?>));
    tipobj.innerHTML = thex+","+they;
  }
}



function positiontip(e){
	if (enabletip){
		var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
		var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
		savex = curX;
		savey = curY;

		//Find out how close the mouse is to the corner of the window
		var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
		var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20
		
		var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000
		
		//if the horizontal distance isn't enough to accomodate the width of the context menu
		if (rightedge<tipobj.offsetWidth)
		//move the horizontal position of the menu to the left by it's width
			tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
		else if (curX<leftedge)
			tipobj.style.left="5px"
		else
		//position the horizontal position of the menu where the mouse is positioned
			tipobj.style.left=curX+offsetxpoint+"px"
		
		//same concept with the vertical position
		if (bottomedge<tipobj.offsetHeight)
			tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
		else {
			tipobj.style.top=curY+offsetypoint+"px"
			tipobj.style.visibility="visible"
		}
	}
}

function hidetip(){
	if (ns6||ie){
		enabletip=false
		tipobj.style.visibility="hidden"
		tipobj.style.left="-1000px"
		tipobj.style.backgroundColor=''
		tipobj.style.width=''
	}
}



