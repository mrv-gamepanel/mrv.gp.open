/* Detect link and set active on this menu item */
function dLink() {
	var link 	= window.location.pathname;
	var final 	= link.replace("/", "");
	return final;
}
function addActOnMenuitem() {
	$('#l_'+dLink()).addClass('active');
}
// addActOnMenuitem();
// Fix bag.. (Function for go to custom URL)
function goTo(url) {
	if (url == '') {
		return false;
	} else {
		document.location.href = url;
	}
}
/* Show Admin password */
function showAdmPw(elm, admID) {
	// Toggle class (Show/Hide)
	$('#showAdmPw_'+admID+' b').fadeToggle(0);
	$('#showAdmPw_'+admID+' i').toggle(0);
	// Change text (Show/Hide)
	$(elm).html() == 'show' ? $(elm).html('hide') : $(elm).html('show');
}
/* New Admin Select Type */
function newAdmSelectType(elem) {
	var Type = $(elem).val();
	if (Type == 'custom') {
		$('#customPerms').fadeIn(300);
	} else {
		$('#customPerms').hide(0);
	}
}
/* Action by Server */
function actionServer(srvID, Action) {
	if (srvID == '' || isNaN(srvID)) {
		return false;
	} else {
		// Create ajax form
	    let formData = new FormData();
	    formData.append('srvID', srvID);
	    formData.append('action', Action);
	    // Send ajax request
	    $.ajax({
	        url        : '/process?actionServer',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	// console.log(r);
	      		location.reload();
	        	return false;
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
	      			setTimeout(function() {
	      				location.reload();
	      			}, 2500);
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
/* Ads Color */
function adsTextColor(TeamColor) {
	if (TeamColor == '') {
		return false;
	} else {
		// Defualt
		var adsBtnss = document.getElementsByClassName('adsBtnss');
		for (var i = 0; i < adsBtnss.length; ++i) {
			$(adsBtnss[i]).removeClass('active');
		}
		// Set Active class on click
		$('#ads_'+TeamColor).addClass('active');
		// Save color
		$('#adsColor').attr('value', TeamColor);
		// Set color
		setColor();
	}
}
/* Ads Text */
function adsTxt() {
	var adsText = $('#adsText').val();
	if (adsText == '') {
		return false;
	} else {
		// Print
		$('#adsTxt').html('[Nick]: '+adsText);
	}
}
/* Change Color */
function setColor(color) {
	if (color == '') {
		return false;
	} else {
		// Get color
		var getColor = $('#adsColor').attr('value');
		$('#adsTxt').removeClass('all');
		$('#adsTxt').removeClass('ct');
		$('#adsTxt').removeClass('tt');
		$('#adsTxt').addClass(getColor);
	}
}
/* Open Ticket */
function openTicket() {
	// Create ajax form
    let formData = new FormData(document.querySelector('#openTicketForm'));
    // Send ajax request
    $.ajax({
        url        : '/process?openTicket',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function (r) {
        	var res = JSON.parse(r);
      		if (res[0] == 'success') {
      			setTimeout(function() {
      				location.reload();
      			}, 2000);
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
/* AnswOnTicket */
function AnswOnTicket() {
	// Create ajax form
    let formData = new FormData(document.querySelector('#AnswOnTicket'));
    // Send ajax request
    $.ajax({
        url        : '/process?AnswOnTicket',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function (r) {
        	var res = JSON.parse(r);
      		if (res[0] == 'success') {
      			setTimeout(function() {
      				location.reload();
      			}, 2000);
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
/* change Map btn */
function chMapName() {
	// Hide map
	$('#mpName').hide(0);
	// Show map input
	$('#mapInput').show(0);
	// Hide Edit btn 
	$('#mapEditBtn').hide(0);
	// Show Save Btn
	$('#mapSaveBtn').show(0);
}
/* Save Map Name */
function saveMapName(serverID) {
	if (serverID == '' || isNaN(serverID)) {
		return false;
	} else {
		var mapName = document.getElementById('mapInput').value;
		// Create ajax form
	    let formData = new FormData();
	    formData.append('serverID', serverID);
	    formData.append('mapName', mapName);
	    // Send ajax request
	    $.ajax({
	        url        : '/process?editMapOnSrv',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	var res = JSON.parse(r);
	      		if (res[0] == 'success') {
	      			setTimeout(function() {
	      				location.reload();
	      			}, 2000);
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
/* Show Ftp Pw */
function showFtpPw(serverID) {
	$('#ftpChangePW').hide(0);
	/////////////////////////
	if (serverID == '' || isNaN(serverID)) {
		return false;
	} else {
		// Create ajax form
	    let formData = new FormData();
	    formData.append('serverID', serverID);
	    // Send ajax request
	    $.ajax({
	        url        : '/process?showFtpPw',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function (r) {
	        	var res = JSON.parse(r);
	      		if (res[0] == 'error') {
	      			var pC = prompt(res[1]);
	      			// Create ajax form
				    let formData = new FormData();
				    formData.append('pC', pC);
				    // Send ajax request
				    $.ajax({
				        url        : '/process?EnpC',
				        type       : 'POST',
				        contentType: false,
				        cache      : false,
				        processData: false,
				        data       : formData,
				        success    : function (r) {
				        	var resp = JSON.parse(r);
				      		if (resp[0] == 'success') {
				      			showFtpPw(serverID);
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
	      		} else if (res[0] == 'success') {
	      			$('#ftpPwShow').hide();
	      			$('#ftpPwIs').html(res[1]);
	      			// Show buttn for change ftp password;
	      			$('#ftpChangePW').show(0);
	      		} else {
	      			// Alert
					$.toast({
					    heading: 'Information',
					    text: res[1],
					    icon: res[0],
					    position: 'bottom-left',
					});
			   		return false;
	      		}
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* Order server */
function newOrder() {
	// Create ajax form
    let formData = new FormData(document.querySelector('#orderGameServer'));
    // Send ajax request
    $.ajax({
        url        : '/process?orderServer',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function(r) {
        	var res = JSON.parse(r);
      		if (res[0] == 'success') {
      			setTimeout(function() {
      				location.reload();
      			}, 2000);
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
/* Demo login */
function demoLogin() {
	// Create ajax form
    let formData = new FormData();
    formData.append('Email', 'demo@mrv-hosting.com');
    formData.append('Password', 'demo');
    // Send ajax request
    $.ajax({
        url        : '/login?log',
        type       : 'POST',
        contentType: false,
        cache      : false,
        processData: false,
        data       : formData,
        success    : function(r) {
        	location.reload();
        },
        error: function (err) {
        	return false;
        }
    });
}
/* Change Ftp password */
function changeFTPpw(srvID) {
	if (srvID == '' || isNaN(srvID)) {
		return false;
	} else {
		// Create ajax form
	    let formData = new FormData();
	    formData.append('srvID', srvID);
	    // Send ajax request
	    $.ajax({
	        url        : '/process?changeFTPpw',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function(r) {
		        setTimeout(function() {
		        	var res = JSON.parse(r);
		        	if (res[0] == 'success') {
		        		$('#ftpChangePW').hide(); $('#ftpPwShow').hide();
		      			$('#ftpPwIs').html(res[2]);
		      		}
		      		// Alert
					$.toast({
					    heading: 'Information',
					    text: res[1],
					    icon: res[0],
					    position: 'bottom-left',
					});
		   			return false;
		        }, 1500);
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* Server settings */
function srvSettingsModal(o, v, t) {
	if (o == '') {
		return false;
	} else {
		if (t == 'show') {
			// Print selected Type
			$('#sI_').html('').html('<span style="text-transform:none;">Change:</span> <span style="color:#4b8cdc;">[ '+o+' ]</span>');
			$('#sf_Key').html('').html(o);
			// Update Type
			$('#sf_Type').val('').val(o);
			// Update Value
			$('#sf_lVal').val('').val(v);
			// Modal show;
			$('#ServerSettings').modal('show');
		} else {
			// Print selected Type
			$('#sI_').html(''); $('#sf_Key').html('');
			// Update Type
			$('#sf_Type').val('');
			// Update Value
			$('#sf_lVal').val('');
			// Modal hide;
			$('#ServerSettings').modal('hide');
		}
	}
}
/* Save Server Settings Form */
function serverSettingsForm(s) {
	if (isNaN(s) || s == '') {
		return false;
	} else {
		$('#sf_Btn').hide();
		$('#sf_loadMsg').show();
		// Create ajax form
	    let formData = new FormData(document.querySelector('#serverSettingsForm'));
	    formData.append('serverID', s);
	    // Send ajax request
	    $.ajax({
	        url        : '/process?saveServerSettings',
	        type       : 'POST',
	        contentType: false,
	        cache      : false,
	        processData: false,
	        data       : formData,
	        success    : function(r) {
		        var res = JSON.parse(r);
		        // Alert
				$.toast({
				    heading: 'Information',
				    text: res[1],
				    icon: res[0],
				    position: 'bottom-left',
				});
				setTimeout(function() {
					location.reload();
				}, 1000);
	   			return false;
	        },
	        error: function (err) {
	        	return false;
	        }
	    });
	}
}
/* Install plugin */
function installPlugin(s, p) {
	// plugin
	if (isNaN(s) || s == '') {
		return false;
	}
	// server
	if (isNaN(p) || p == '') {
		return false;
	}
	// Go to;
	document.location.href = '/process?installPlugin&serverID='+s+'&pluginID='+p+'';
	return false;
}
/* Remove plugin */
function removePlugin(s, p) {
	// plugin
	if (isNaN(s) || s == '') {
		return false;
	}
	// server
	if (isNaN(p) || p == '') {
		return false;
	}
	// Send request

}
/* Ping Box */
function pingBox(url) {
    int = setInterval(function () {
        let p = new Ping();
        p.ping(url, function (err, data) {
            // Also display error if err is returned.
            if (err !== 'error') {
                //console.log("Ooops, unable to check status");
                document.getElementById('target').innerHTML = '<span class="red">connection error</span>';
            }
            if (data <= 249) {
                document.getElementById('target').innerHTML = '<span class="green">' + data + 'ms</span>';
            } else if (data => 250 && data <= 499) {
                document.getElementById('target').innerHTML = '<span class="amber">' + data + 'ms</span>';
            } else {
                document.getElementById('target').innerHTML = '<span class="red">' + data + 'ms</span>';
            }
        });
    }, 1500);
}
/* Copy IP Address */
function copyToClipboard(i, t) {
	/* Create Element */
	var ip = document.createElement('input');
	ip.setAttribute('id', 'copyIP');
	ip.setAttribute('type', 'text');
	ip.setAttribute('style', 'display:block;');
	ip.setAttribute('value', i);
	/* Select ip */
	document.body.appendChild(ip);
	var cI = document.getElementById('copyIP');
	cI.select();
	cI.setSelectionRange(0, 99999);
	/* Copy ip */
	if (document.execCommand('copy')) {
		$(t).attr('style', 'cursor:pointer;color:green;');
		setTimeout(function() {
			$(t).attr('style', 'cursor:pointer;color:#fff;');
		}, 300);
	}
	/* Remove element */
	ip.remove();
	// Alert
	$.toast({
	    heading: 'Information',
	    text: 'IP address copied',
	    icon: 'success',
	    position: 'bottom-left',
	});
}
// Hide loader
$('.loader').fadeOut(300);