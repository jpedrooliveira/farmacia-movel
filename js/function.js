function showModal() {
  var modal = document.querySelector('ons-modal');
  modal.show();
}

window.fn = {};

window.fn.open = function() {
	var menu = document.getElementById('menu');
	menu.open();
};

window.fn.load = function(page) {
	var content = document.getElementById('content');
	var menu = document.getElementById('menu');
	content.load(page)
	.then(menu.close.bind(menu));
};

var showTemplateDialog = function(id, html) {
	var dialog = document.getElementById(id);
	if (dialog) {
		dialog.show();
	} else {
		ons.createElement(html, { append: true })
		.then(function(dialog) {
			dialog.show();
		});
	}
};

	var hideDialog = function(id) {
		document.getElementById(id).hide();
	};

	function verificarCode(nencomenda){
		var code = $("#code" + nencomenda).val();
		alert(code);
		$.ajax({
			url: "php/confirmOrder.php",
			type: "POST",
			dataType: "json",
			data: { 
				code: code, 
				nencomenda: nencomenda
			},
			success: function (result) {
				var message = result.status;
				ons.notification.toast(message, {
					timeout: 4000
				});
				if(message == "Codigo correto. Encomenda confirmada com sucesso.") {
					setTimeout(location.reload.bind(location), 4000);
				}
				else {
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
			}
		});
	}

	function initMap(postalCodes) {
		var latlng = new google.maps.LatLng(41.452270, -8.273510);
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 7,
			center: latlng,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				position: google.maps.ControlPosition.TOP_CENTER
			},
			zoomControl: true,
			zoomControlOptions: {
				position: google.maps.ControlPosition.LEFT_CENTER
			},
			scaleControl: true,
			streetViewControl: true,
			streetViewControlOptions: {
				position: google.maps.ControlPosition.LEFT_TOP
			},
			fullscreenControl: true
		});

		var infowindow = new google.maps.InfoWindow();
		var geocoder = new google.maps.Geocoder();
		var bounds = new google.maps.LatLngBounds();
		var marker, i;

		for (i = 0; i < postalCodes.length; i++) {
			codeAddress(postalCodes[i]);
		}

		function codeAddress(location) {
			var currentCoordinates = "";
			if ("geolocation" in navigator){
				navigator.geolocation.getCurrentPosition(function(position){ 
					var currentLatitude = position.coords.latitude;
					var currentLongitude = position.coords.longitude;
					currentCoordinates = new google.maps.LatLng(currentLatitude, currentLongitude);
					var armazemCoordinates = new google.maps.LatLng(41.452437, -8.285135);

					var imageMarkerCurrent = 'https://image.ibb.co/coAHEH/Webp_net_resizeimage.png';
					var markerCurrentPosition = new google.maps.Marker({
						position: currentCoordinates,
						animation: google.maps.Animation.DROP,
						map: map,
						icon: imageMarkerCurrent,
						title: 'Você está aqui'
					});

					var markerArmazemPosition = new google.maps.Marker({
						position: armazemCoordinates,
						animation: google.maps.Animation.DROP,
						map: map,
						icon: "https://image.ibb.co/ghPEBx/Webp_net_resizeimage_2.png",
						title: 'Localização do Armazém'
					});

					var infoWindowArmazem = new google.maps.InfoWindow({
						content: '<a href="http://maps.google.com/maps?saddr=' + currentCoordinates + '&daddr=' + armazemCoordinates + '">Ir para o Armazém</a>'
					});

					google.maps.event.addListener( markerArmazemPosition, 'click', function() {
						infoWindowArmazem.open( map, markerArmazemPosition );
					});

				});
			}
			geocoder.geocode({
				'address': location['zip_code']
			}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						animation: google.maps.Animation.DROP,
						position: results[0].geometry.location
					});
					bounds.extend(marker.getPosition());
					map.fitBounds(bounds);
					google.maps.event.addListener(marker, 'click', (function(marker, location) {
						return function() {
							contentInfoWindow = '<div id="content" style="width: 250px; height: 100%;">'+
							'<h1 id="firstHeading" class="firstHeading">Informações</h1><hr>'+
							'<div id="bodyContent" style="margin-top: 19px;">'+ 
							'<div style="float:left; width:20%;"></div>' +
							'<div style="float:left; width:80%;margin-top: -19px;"><p><a href="http://maps.google.com/maps?saddr=' + currentCoordinates + '&daddr=' + location['zip_code'] + '">' + location['address'] + '</a></p></div>'+
							'<div style="float:left; width:80%;margin-top: -19px;"><p><b>Utente: </b>' + location['user'] + '</p></div>'+
							'<div style="float:left; width:80%;margin-top: -19px;"><p><b>E-mail: </b>' + location['email'] + '</p></div>';

							if (location['telephone'] == "Não possui") {
								contentInfoWindow += '<div style="float:left; width:80%;margin-top: -19px;"><p><b>Contacto Fixo: </b>' + location['telephone'] + '</p></div>';
							}
							else {
								contentInfoWindow += '<div style="float:left; width:80%;margin-top: -19px;"><p><b>Contacto Fixo: </b><a href=tel:' + location['telephone'] + '>' + location['telephone'] + '</a></p></div>';
							}

							if(location['mobile'] == "Não possui") {
								contentInfoWindow += '<div style="float:left; width:80%;margin-top: -19px;"><p><b>Contacto Móvel: </b>' + location['mobile'] + '</a></p></div>';
							}
							else {
								contentInfoWindow += '<div style="float:left; width:80%;margin-top: -19px;"><p><b>Contacto Móvel: </b><a href="https://api.whatsapp.com/send?phone=' + location['mobile'] + '">' + location['mobile'] + '</a></p></div>';
							}

							contentInfoWindow += '</div>'+
							'</div>';

							infowindow.setContent(contentInfoWindow);
							infowindow.open(map, marker);
						};
					})(marker, location));
				}
			});
		}
	}

	function getUtente(id, token) {
		var dadosUtente = "";
		$.ajax({
			url: "https://my.jasminsoftware.com/api/210675/210675-0015/salesCore/customerParties/" + id,
			type: 'GET',
			async: false,
			headers: { 
				"Authorization": 'Bearer ' + token 
			},
			success: function(data) {
				dadosUtente = data;
			}
		});
		return dadosUtente;
	}

	function getProductsStock(token) {
		var produtos = "";
		$.ajax({
			url: "https://my.jasminsoftware.com/api/210675/210675-0015/materialsCore/materialsItems",
			type: 'GET',
			async: false,
			headers: { 
				"Authorization": 'Bearer ' + token 
			},
			success: function(data) {
				produtos = data;
			}
		});
		return produtos;
	}

	document.addEventListener('prechange', function(event) {
		document.querySelector('ons-toolbar .center')
		.innerHTML = event.tabItem.getAttribute('label');
	});