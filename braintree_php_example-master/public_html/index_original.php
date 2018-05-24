<!DOCTYPE html>
<html>
<head>
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsenui.css">
	<link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsen-css-components.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	<link href="../../onsen-css-theme/theme.css" rel="stylesheet">
	<script src="https://unpkg.com/onsenui/js/onsenui.min.js"></script>
	<script src="../../js/function.js?87"></script>
	<script src="https://js.braintreegateway.com/web/dropin/1.10.0/js/dropin.min.js"></script>
	<style type="text/css">
	.profile-pic {
		width: 270px;
		border-radius: 4px;
	}
	.profile-pic > img {
		display: block;
		max-width: 100%;
	}

	.not-active {
		cursor: default;
		text-decoration: none;
		color: white;
	}
</style>

</head>
<body>
	<ons-navigator swipeable  id="myNavigator" page="container.html"></ons-navigator>
	<template id="container.html">
		<ons-page>
			<ons-splitter>
				<ons-splitter-side id="menu" side="left" width="270px" collapse>
					<ons-page>
						<ons-list>
							<div class="profile-pic"><img src="https://image.ibb.co/fupFnS/Imagem1.png"/></div>  
							<ons-list-item onclick="fn.load('home.html')" tappable>
								<ons-icon class="list-item__icon" fixed-width="" icon="home"></ons-icon>
								PÁGINA INICIAL
							</ons-list-item>
							<ons-list-item  onclick="fn.load('page1.html')" tappable>
								<ons-icon class="list-item__icon" fixed-width="" icon="people-carry"></ons-icon>
								CONFIRMAÇÃO ENTREGAS
							</ons-list-item>
							<ons-list-item  onclick="fn.load('map.html')" tappable>
								<ons-icon class="list-item__icon" fixed-width="" icon="map-marker-alt"></ons-icon>
								MAPA ENTREGAS
							</ons-list-item>
							<ons-list-item  onclick="fn.load('stock.html')" tappable>
								<ons-icon class="list-item__icon" fixed-width="" icon="pills"></ons-icon>
								STOCK DISPONÍVEL
							</ons-list-item>
						</ons-list>
					</ons-page>
				</ons-splitter-side>
				<ons-splitter-content id="content" page="home.html"></ons-splitter-content>
			</ons-splitter>
		</ons-page>
	</template>
	<template id="home.html">
		<ons-page>
			<ons-toolbar>
				<div class="left">
					<ons-toolbar-button onclick="fn.open()">
						<ons-icon icon="ion-ios-drag"></ons-icon>
					</ons-toolbar-button>
				</div>
				<div class="center" text-align="center">
					FARMÁCIA MÓVEL
				</div>
			</ons-toolbar>
			<img src="https://image.ibb.co/djSRBn/rawpixel_com_600792_unsplash2_esticado.jpg" style="width: 100%; z-index:-1; position:absolute; height: 100%;"/>
			<p style="text-align: center; opacity: 0.6; padding-top: 20px;">
				<br>
				<div align="center" style="opacity: 0.6";>
					<ons-card style= " width: 85%; text-align:center; ">
						<b>
							Bem-vindo caro Colaborador!
						</b>
						<p>
							<font size= "1";>
								Nesta plataforma pode consultar todas as Entregas que terá que efetuar, bem como o respetivo estado atual. 
								<p>
									Para proceder à confirmação de uma entrega, terá de inserir o código fornecido pelo cliente.
								</p> 
							</font>
						</p>
					</ons-card>
				</div>
			</p>
		</ons-page>
	</template>
	<template id="page1.html">
		<ons-page id="page1">
			<ons-toolbar>
				<div class="left">
					<ons-toolbar-button onclick="fn.open()">
						<ons-icon icon="ion-ios-drag"></ons-icon>
					</ons-toolbar-button>
				</div>
				<div class="center">
					CONFIRMAÇÃO ENTREGAS
				</div>
			</ons-toolbar>
			<p style="text-align:center; margin-top: 10px; width: 100%;">
			</p>
			<div id=encomendas>
				<div id="encomendas2">
					<?php
					include_once "../../php/acess_token.php";
					$contents= consume_api("https://farmacia-movel2.herokuapp.com/api/getJasminToken");
					$token = $contents['jasmin_token'];
					?>
					<script>
						var postal_codes = new Array();
						$( document ).ready( function popularEncomendas(){
							var todayDate = new Date();
							var yearTodayDate = todayDate.getFullYear();
							var dayTodayDate = todayDate.getDate();
							var monthTodayDate = todayDate.getMonth();
							var token = <?php echo json_encode($token);?>;
							$.ajax({
								url: "https://my.jasminsoftware.com/api/210675/210675-0015/sales/orders/",
								type: 'GET',
								headers: { 
									"Authorization": 'Bearer ' + token 
								},
								success: function(data) {
									data.forEach(function(element){
										var noteToRecipient = element.noteToRecipient;
										if(noteToRecipient != null) {
											if(noteToRecipient.length >=7) {
												var entrega = noteToRecipient.substring(7, 17);
											}
											else {
											}
											if(!noteToRecipient.includes(entrega)) {
												var documentLines = element.documentLines;
												var deliveryDate = documentLines[0]['deliveryDate'];
												var deliveryDateConvert = new Date(deliveryDate);
												var yearDeliveryDate = deliveryDateConvert.getFullYear();
												var dayDeliveryDate = deliveryDateConvert.getDate();
												var monthDeliveryDate = deliveryDateConvert.getMonth();
												if(yearDeliveryDate == yearTodayDate && monthDeliveryDate == monthTodayDate && dayDeliveryDate == dayTodayDate) {
													var nencomenda = element.seriesNumber;
													var morada = element.unloadingPointAddress;
													var nomeUtente = element.buyerCustomerPartyName;
													var montante = element.payableAmount.amount;
													var codigo = element.noteToRecipient; 

													var foo, targetElement  = document.getElementById('encomendas');
													foo = document.createElement('div');
													foo.innerHTML ="<ons-card><div class='title'> <font size='3.5'> <b>ENCOMENDA DE " + nomeUtente.toUpperCase() + "</b> </font></div><div class='content'><b>Morada: </b>" + morada + "<br><b>Utente: </b>" + nomeUtente + "<br><b>Montate a pagar: </b>" + montante + " €<br><b>Data de Entrega: </b>" + deliveryDateConvert.toLocaleDateString() + "<br><b>Código: </b>" + codigo +"</div><br><div class='center'><div style='text-align: center; padding: 10px;'><p><ons-button modifier='large' onclick="+" showTemplateDialog(" + "'" + "my-dialog" + nencomenda + "'" + "," + "'" + "dialog" + nencomenda + ".html" + "'" + ")>CONFIRMAR ENTREGA</ons-button><p></p><ons-button modifier='large'><a class='not-active' href='index.php?valor=" + montante + "'>EFETUAR PAGAMENTO</a></ons-button></p></div></div></ons-card><div style='margin-top: 5%; margin-bottom: 5%;'><hr width='90%'></div>";
													targetElement.appendChild(foo);

													var foo2, targetElement2  = document.getElementById('encomendas2');
													foo2 = document.createElement('div');
													foo2.innerHTML ="<template id='dialog" + nencomenda + ".html'><ons-dialog id='my-dialog" + nencomenda + "'><div style='text-align: center; padding: 10px;'><p><iframe id='iframe" + nencomenda + "' src=video.html frameBorder='0' width='200' height='200'></iframe></p><p><ons-input id='code" + nencomenda + "' modifier='underbar' placeholder='Código de Verificação' float minlength='0' maxlength='6' required></ons-input></p><p><ons-button style='margin-right: 5%;' onclick=" + "'" + "verificarCode(" + nencomenda + ")'" + ">Validar</ons-button><ons-button style='margin-left: 5%;' onclick=" + "hideDialog(" + "'" + "my-dialog" + nencomenda + "'" + ")" + ">Fechar</ons-button></p></div></ons-dialog></template>";
													targetElement2.appendChild(foo2);

													var b;
													if (typeof window.addEventListener != 'undefined') {
														window.addEventListener('message', function(e) {
															b = e.data[1];
															if(b != "e") {
																document.getElementById("code" + nencomenda).value = "" + b;
															}
															//alert(b);
														}, false);
													} else if (typeof window.attachEvent != 'undefined') {
														window.attachEvent('onmessage', function(e) {
														});
													}

												}
												else {
												}
											}
											else  {
											}
										}
									})
								}
							});
						});
					</script>
				</div>
			</div>
			<br/>
		</ons-page>
	</template>
	<template id="map.html">
		<ons-page>
			<ons-toolbar>
				<div class="left">
					<ons-toolbar-button onclick="fn.open()">
						<ons-icon icon="ion-ios-drag"></ons-icon>
					</ons-toolbar-button>
				</div>
				<div class="center" text-align="center">
					MAPA ENTREGAS
				</div>
			</ons-toolbar>
			<div id="map" style="width:100%; height:100%;">
				<?php
				include_once "../../php/acess_token.php";
				$contents= consume_api("https://farmacia-movel2.herokuapp.com/api/getJasminToken");
				$token = $contents['jasmin_token'];
				?>
				<script>
					var postal_codes = new Array();
					$( document ).ready( function popularMapa(){
						var todayDate = new Date();
						var yearTodayDate = todayDate.getFullYear();
						var dayTodayDate = todayDate.getDate();
						var monthTodayDate = todayDate.getMonth();
						var token = <?php echo json_encode($token);?>;
						$.ajax({
							url: "https://my.jasminsoftware.com/api/210675/210675-0015/sales/orders/",
							type: 'GET',
							headers: { 
								"Authorization": 'Bearer ' + token 
							},
							success: function(data) {
								data.forEach(function(element){
									var noteToRecipient = element.noteToRecipient;
									if(noteToRecipient != null) {
										if(noteToRecipient.length >=7) {
											var entrega = noteToRecipient.substring(7, 17);
										}
										else {
										}
										if(!noteToRecipient.includes(entrega)) {
											var documentLines = element.documentLines;
											var deliveryDate = documentLines[0]['deliveryDate'];
											var deliveryDateConvert = new Date(deliveryDate);
											var yearDeliveryDate = deliveryDateConvert.getFullYear();
											var dayDeliveryDate = deliveryDateConvert.getDate();
											var monthDeliveryDate = deliveryDateConvert.getMonth();
											if(yearDeliveryDate == yearTodayDate && monthDeliveryDate == monthTodayDate && dayDeliveryDate == dayTodayDate) {
												var utenteId = element.buyerCustomerPartyId;
												var dadosUtente = getUtente(utenteId, token);
												var mobile = dadosUtente['mobile'];
												var telephone = dadosUtente['telephone'];
												var electronicMail = dadosUtente['electronicMail'];
												if(mobile == null) {
													mobile = "Não possui";
												}
												if(telephone == null) {
													telephone = "Não possui";
												}
												if(electronicMail == null) {
													electronicMail = "Não possui";
												}
												postal_codes.push({
													"zip_code": element.unloadingPostalZone, 
													"address": element.unloadingPointAddress, 
													"user": element.buyerCustomerPartyName, 
													"email": electronicMail.replace(";", ""),
													"mobile": mobile,
													"telephone": telephone
												});
											}
											else {
											}
										}
										else  {
										}
									}
								})
								initMap(postal_codes);
							}
						});
					});
				</script>
				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDE_6xZsfnwiFtjIHGDFUVdJXv_s8PcQQ" type="text/javascript"></script>
			</div>
		</ons-page>
	</template>
	<template id="stock.html">
		<ons-page>
			<ons-toolbar>
				<div class="left">
					<ons-toolbar-button onclick="fn.open()">
						<ons-icon icon="ion-ios-drag"></ons-icon>
					</ons-toolbar-button>
				</div>
				<div class="center" text-align="center">
					MEDICAMENTOS SEM STOCK
				</div>
			</ons-toolbar>
			<ons-tabbar swipeable position="auto">
				<ons-tab page="tab1.html" label="MEDICAMENTOS SEM STOCK" icon="cube" active>
				</ons-tab>
				<ons-tab page="tab2.html" label="MEDICAMENTOS COM STOCK" icon="cubes">
				</ons-tab>
			</ons-tabbar>
		</ons-page>
	</template>

	<template id="tab1.html">
		<ons-page id="Tab1">
			<?php
			include_once "../../php/acess_token.php";
			$contents= consume_api("https://farmacia-movel2.herokuapp.com/api/getJasminToken");
			$token = $contents['jasmin_token'];
			?>
			<script>
				var token = <?php echo json_encode($token);?>;
				var produtos = getProductsStock(token);
				produtos.forEach(function(element) {
					var warehousesArray = element.materialsItemWarehouses;
					var medicine = element.description;
					for (var i = 0 ; i < warehousesArray.length ; i++) {
						warehouse = warehousesArray[i].warehouse;
						stockBalance = warehousesArray[i].stockBalance;
						if (warehouse == "FARMACIA_MOVEL") {
							if (stockBalance == 0) {
								var foo, targetElement  = document.getElementById('produtos_sem_stock');
								foo = document.createElement('ons-list');
								foo.innerHTML = "<ons-list-item><font size='10'>" + stockBalance + "</font><p>&nbsp;&nbsp;&nbsp;&nbsp;" + medicine.toUpperCase() + "</p></ons-list-item>";
								targetElement.appendChild(foo);
							}
							else {
							}
						}
					}
				});
			</script>
			<div id="produtos_sem_stock">
			</div>
		</ons-page>
	</template>

	<template id="tab2.html">
		<ons-page id="Tab2">
			<?php
			include_once "../../php/acess_token.php";
			$contents= consume_api("https://farmacia-movel2.herokuapp.com/api/getJasminToken");
			$token = $contents['jasmin_token'];
			?>
			<script>
				var token = <?php echo json_encode($token);?>;
				var produtos = getProductsStock(token);
				produtos.forEach(function(element) {
					var warehousesArray = element.materialsItemWarehouses;
					var medicine = element.description;
					for (var i = 0 ; i < warehousesArray.length ; i++) {
						warehouse = warehousesArray[i].warehouse;
						stockBalance = warehousesArray[i].stockBalance;
						if (warehouse == "FARMACIA_MOVEL") {
							if (stockBalance > 0) {
								console.log(medicine + ": não é zero (" + stockBalance + ")");
								var foo, targetElement  = document.getElementById('produtos_com_stock');
								foo = document.createElement('ons-list');
								foo.innerHTML = "<ons-list-item><font size='10'>" + stockBalance + "</font><p>&nbsp;&nbsp;&nbsp;&nbsp;" + medicine.toUpperCase() + "</p></ons-list-item>";
								targetElement.appendChild(foo);
							}
							else {
							}
						}
					}
				});
			</script>
			<div id="produtos_com_stock">
			</div>
		</ons-page>
	</template>
</body>
</html>
