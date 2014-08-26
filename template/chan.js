function declOfNum(number, titles) {  
    cases = [2, 0, 1, 1, 1, 2];  
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
}

function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}



function tred(type,id){
	var req = getXmlHttp()  

	req.onreadystatechange = function() {  
		if (req.readyState == 4) { 
			if(req.status == 200) { 

				if(type == 'spam') {
					$('#tred'+id).fadeTo( "fast" , 0.3);
					$('#tredstatus'+id).html('<a onclick="tred(\'approve\','+id+')" style="cursor: pointer; color: green;">¬осстановить</a>');
				}

				if(type == 'approve') {
					$('#tred'+id).fadeTo( "fast" , 1);
					$('#tredstatus'+id).html('<a onclick="tred(\'spam\','+id+')" style="cursor: pointer; color: red;" class=red>—пам!</a>');
				}
			}
		}
	}

	req.open('GET', 'http://'+document.domain+'/ajax_api/delchan.php?type='+type+'&id=' + id, true);  
	req.send(null);
	$('#tredstatus'+id).html('<img src="http://'+document.domain+'/template/load2.gif">');
}

function update_new()
{
	var req = getXmlHttp() 

	req.onreadystatechange = function() {  
	if (req.readyState == 4) { 
	if(req.status == 200) { 

		if(req.responseText == 0) {
			//document.getElementById('update_status').className = showupdate;
			document.getElementById('update_status').style.background="lightyellow";
			document.getElementById('update_status').innerHTML = "нет новых собщений";
			document.title = "TopSape Chan";

		}
		else {
			//document.getElementById('update_status').className = showupdate;
			document.getElementById('update_status').style.background="lightgreen";
			var text = declOfNum(req.responseText, ['новое сообщение', 'новых сообщени€', 'новых сообщений']);
			document.getElementById('update_status').innerHTML =req.responseText + " " + "<a href='http://topsape.ru/chan/'>"+text + "</a>";
			document.title = "(" + req.responseText + ") TopSape Chan";
		}
	}
	}

	}

	req.open('GET', 'http://'+document.domain+'/ajax_api/chan_new_treds.php?since_id='+since_id, true);  
	req.send(null);
	document.getElementById('update_status').innerHTML = '<img src="http://'+document.domain+'/template/load2.gif">';


	setTimeout("update_new()",60000);// вызывает сам себ€ каждые 100 миллисекунд.
}


function chanlink(id,id_tred) {
	$('#form'+id).show();
    $('#textarea_form_'+id).val($('#textarea_form_'+id).val() + '#' + id_tred + ' ');
	//$('html, body').animate({scrollTop:$('#form'+id).offset().top}, 750);
}

// Create the tooltips only when document ready
 $(document).ready(function()
 {
     // MAKE SURE YOUR SELECTOR MATCHES SOMETHING IN YOUR HTML!!!

     $('a#showtred').each(function() {
     	var tred = $(this).attr('class');
         $(this).qtip({
            content: {
                text: 'Loading...',
                ajax: {
                    url: 'http://'+document.domain+'/ajax_api/show_chan_post.php',
                    type: 'GET', // POST or GET
            		data: {id: tred}, // Data to pass along with your request
            		success: function(data, status) {
                		// Process the data

                		// Set the content manually (required!)
               			this.set('content.text', data);
            		}
                }
            },
            position: {
                viewport: $(window)
            },
            hide: {
        	delay: 1000
    		},
            style: 'qtip-rounded'
         });
     });
 });

$(document).ready(function()
{
	var items2 = $.cookie('chan_hide_treds');

	if(jQuery.type(items2) === "undefined") {
		var items2 = "0,1";
	}
	var items = items2.split(",");

	for ( var i in items ) {
		$('#tred'+items[i]).hide();
		$('#hidetred'+items[i]).prop('checked', true);
	}
});

function hidetred(id) {

	$('#tred'+id).toggle();

	var items2 = $.cookie('chan_hide_treds');

	if(jQuery.type(items2) === "undefined") {
		var items2 = "0,1";
	}

	var items = items2.split(",");

	if($.inArray(String(id), items) == -1) {
		items.push(id);
	} 
	else {
		items.splice( $.inArray(String(id), items), 1 );
	}

	$.cookie('chan_hide_treds' , items, {expires:90} );
}