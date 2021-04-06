/* Detect link and set active on this menu item */
function dLink() {
	/* Url */
	var url_string 		= window.location.href;
	var url 			= new URL(url_string);
	/* Event Day */
	var urlH 	= url.hash;
	urlH 		= urlH.replace('#p=', '');
	urlH 		= urlH.replace('#', '');
	return urlH;
}
function addActOnMenuitem() {
	$('#l_'+dLink()).addClass('active');
}
addActOnMenuitem();
/* Action by Server */
function actionServer(srvID, Action, reload) {
	if (srvID == '' || isNaN(srvID)) {
		return false;
	} else {
		// Create ajax form
	    let formData = new FormData();
	    formData.append('srvID', srvID);
	    formData.append('action', Action);
	    // Send ajax request
	    $.ajax({
	        url        : '/admin/process?actionServer',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	// console.log(r);
	        	if (reload == true) {
	        		location.reload();
	        	} else {
	        		return r;
	        	}
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* Install mod by Server */
function installMod(srvID, modID) {
	if (srvID == '' || isNaN(srvID) && modID == '' || isNaN(modID)) {
		return false;
	} else {
		// Create ajax form
	    let formData = new FormData();
	    formData.append('srvID', srvID);
	    formData.append('modID', modID);
	    // Send ajax request
	    $.ajax({
	        url        : '/process?installMod',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	var res = JSON.parse(r);
	      		if (res[0] == 'success') {
	      			location.reload();
	      		} else {
	      			console.log(r);
	      		}
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* AnswOnTicket */
function AnswOnTicket() {
	// Create ajax form
    let formData = new FormData(document.querySelector('#AnswOnTicket'));
    // Send ajax request
    $.ajax({
        url        : '/admin/process?AnswOnTicket',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function (r) {
        	var res = JSON.parse(r);
      		if (res[0] == 'success') {
      			// location.reload();
      			setTimeout(function() {
      				window.location.href = '/admin/support?t=1'; // Go to all tickets
      			}, 1000);
      		}
      		// Alert
  			$.toast({
			    heading: 'Information',
			    text: res[1],
			    icon: res[0],
			    position: 'bottom-left',
			});
			return false;
        },
        error: function (err) {
        	return false;
        }
    });
}
/* replyOnTicket */
function replyOnTicket(tS) {
	/////////////
	$(tS).fadeOut(300);
	///////////////////////////////
	$('#AnswOnTicket').fadeIn(300);
	//////////////////////
	$('#Message').focus();
}
/* Block Server Action */
function blockServerAction(sID, Action) {
	if (sID == '' || Action == '') {
		return false;
	} else {
		// Create ajax form
	    let formData = new FormData();
	    formData.append('sID', sID);
	    formData.append('Action', Action);
	    // Send ajax request
	    $.ajax({
	        url        : '/admin/process?blockServerAction',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	var res = JSON.parse(r);

	      		if (res[0] == 'success') {
	      			location.reload();
	      			// console.log(res);
	      		} else {
	      			alert(res[1]);
	      		}
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* Remove server */
function removeServer(srvID) {
	if (srvID == '' || isNaN(srvID)) {
		return false;
	} else {
		// Stop server
		actionServer(srvID, 'stop', false);
		// Create ajax form
	    let formData = new FormData();
	    formData.append('srvID', srvID);
	    // Send ajax request
	    $.ajax({
	        url        : '/admin/process?removeServer',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	console.log(r);
	        	var res = JSON.parse(r);
	        	// console.log(res);
	      		if (res[0] == 'success') {
	      			setTimeout(function() {
	      				window.location.href = '/admin/servers#p=servers';
	      			}, 1000);
	      		}
	      		// Alert
	  			$.toast({
				    heading: 'Information',
				    text: res[1],
				    icon: res[0],
				    position: 'bottom-left',
				});
				return false;
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* Get Server Port (Auto) */
function getServerPort() {
	// Create ajax form
    let formData = new FormData(document.querySelector('#newServerForm'));
    // Send ajax request
    $.ajax({
        url        : '/admin/process?getServerPort',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function (r) {
        	var res = JSON.parse(r);
      		if (res[0] == 'success') {
      			// Update port;
      			$('#newServerPort').val('');
      			$('#newServerPort').val(res[2]);
      		}
      		// Alert
  			$.toast({
			    heading: 'Information',
			    text: res[1],
			    icon: res[0],
			    position: 'bottom-left',
			});
			return false;
        },
        error: function (err) {
        	return false;
        }
    });
}
/* Lock ticket */
function LockTicket(tID) {
	if (tID == '' || isNaN(tID)) {
		return false;
	}
	// Create ajax form
    let formData = new FormData();
    formData.append('tID', tID);
    // Send ajax request
    $.ajax({
        url        : '/admin/process?lockTicket',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function (r) {
        	var res = JSON.parse(r);
      		if (res[0] == 'success') {
      			setTimeout(function() {
      				window.location.href = '/admin/support?t=1'; // Go to all tickets
      			}, 1000);
      		}
      		// Alert
  			$.toast({
			    heading: 'Information',
			    text: res[1],
			    icon: res[0],
			    position: 'bottom-left',
			});
			return false;
        },
        error: function (err) {
        	return false;
        }
    });
}
/* Install FDL */
function installFDL(fdlID, isDef='false') {
	if (fdlID == '' || isNaN(fdlID)) {
		return false;
	} else {
		// Create ajax form
	    if (isDef == 'false') {
	    	var formData = new FormData(document.querySelector('#installFDLform'));
	    	formData.append('default', 'false');
	    } else if(isDef == 'true') {
	    	var formData = new FormData();
	    	formData.append('fdlID', fdlID);
	    	formData.append('default', 'true');
	    } else {
	    	return false;
	    }
	    // Send ajax request
	    $.ajax({
	        url        : '/admin/process?installFDL',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	setTimeout(function() {
	        		console.log(r)
	        	}, 10000);
	        	////////////
	        	var res = JSON.parse(r);
	      		// Alert
	  			$.toast({
				    heading: 'Information',
				    text: res[1],
				    icon: res[0],
				    position: 'bottom-left',
				});
				return false;
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}