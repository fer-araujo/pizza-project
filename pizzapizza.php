<!DOCTYPE html>
	<html>
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Pizza Pizza</title>
			<script src="jquery.min.js"></script>
			<link rel="stylesheet" type="text/css" href="style.css">
			<script src="https://use.fontawesome.com/5e671f520e.js"></script>
			<script src="https://kit.fontawesome.com/ebcc633257.js" crossorigin="anonymous"></script>

			<script>
				var arrS = [];
				var arrM = [];
				var arrL = [];
				var arrO = [];

				$(document).ready(function() {

					//obtener pizzas pendientes
					$('#btnStart').on('click', function () { 
						getPendingPizza();						
					});					

					//cerrar
					$('#close').on('click', function () { 
						$('#overlay, #overlay-back').fadeOut(500); 
					});

					//guardar pizza
					$('.btn-next').on('click', function () { 
						$('.card').fadeOut(1); 
						$('#cardOrder').css('display','flex'); 
						selectToppings();
					});

					//Orden
					$('.btn-order').on('click', function () {						
						$('#overlay, #overlay-back').fadeOut(100); 
						$('#cardOrder').css('display','none'); 
						$('.card').fadeIn(500); 						
					});

					//Small
					$('#btnPizzaS').on('click', function () { 
						var arrtemp = [];
						arrO.push(...arrS);
						arrL.length = 0;
						arrM.length = 0;
						arrS.length = 0;

						clearToppings();

						$('input:checkbox.order').each(function(){
							arrtemp.push($(this).attr('name'));
						});

						for (let i = 0; i < arrO.length; i++) {
							const el = arrO[i];							
							const checkbox = arrtemp;
							const condition = $.inArray(arrO[i], arrtemp);

							if ( condition != -1) {							
								$('input[name="'+el+'"].order').prop('checked', true);
							}							
						}
						createPizza('small',100.00,arrO);
						arrO.length = 0;
						$("#order-data").html('Small pizza <br> Price - $100.00');
						$("#order-data").attr('price','100.00');
						$("#order-data").attr('size','Small');
						
						
					});	
					
					//Medium
					$('#btnPizzaM').on('click', function () { 
						var arrtemp = [];
						arrO.push(...arrM);
						arrL.length = 0;
						arrM.length = 0;
						arrS.length = 0;


						clearToppings();
						
						$('input:checkbox.order').each(function(){
							arrtemp.push($(this).attr('name'));
						});

						for (let i = 0; i < arrO.length; i++) {
							const el = arrO[i];							
							const checkbox = arrtemp;
							const condition = $.inArray(arrO[i], arrtemp);

							if ( condition != -1) {							
								$('input[name="'+el+'"].order').prop('checked', true);
							}							
						}
						createPizza('Medium',150.00,arrO);
						arrO.length = 0;
						$("#order-data").html('Medium pizza <br> Price - $150.00');
						$("#order-data").attr('price','150.00');
						$("#order-data").attr('size','Medium');
					});	

					//Large
					$('#btnPizzaL').on('click', function () { 
						var arrtemp = [];
						arrO.push(...arrL);
						arrL.length = 0;
						arrM.length = 0;
						arrS.length = 0;

						clearToppings();
						
						$('input:checkbox.order').each(function(){
							arrtemp.push($(this).attr('name'));
						});

						for (let i = 0; i < arrO.length; i++) {
							const el = arrO[i];							
							const checkbox = arrtemp;
							const condition = $.inArray(arrO[i], arrtemp);

							if ( condition != -1) {							
								$('input[name="'+el+'"].order').prop('checked', true);
							}							
						}
						createPizza('Large',175.00,arrO);
						arrO.length = 0;
						$("#order-data").html('Large pizza <br> Price - $175.00');
						$("#order-data").attr('price','175.00');
						$("#order-data").attr('size','Large');
					});	
					
					//confirmar orden
					$('#btnOrder').on('click', function() {
						var arrToppings = [];
						var idOrder = $("#idPizza").val();

						$('input:checkbox:checked.order').each(function(){
							arrToppings.push($(this).attr('name'));
						});

						updateToppings(idOrder,arrToppings);
									
					});

					$('#check').on('click', function(){
						if($(this).prop('checked') == true){
							$('.sidebar-wrapper').css('right','0');
							getPizzas();
						}
						else{
							$('.sidebar-wrapper').css('right','-100%');
						}
					});
					var d = new Date();
					document.getElementById("date").innerHTML = d.toDateString();
				});

				$(document).on("keydown", function (e) {
					// capture the enter key and call submitTopping() if pressed
					var key = e.which || e.keyCode || e.charCode;
					if (key === 13) {
						// updateToppings();
					}
				});

				function clearToppings(){	
					
					$('input[type="checkbox"].small').each(function(){
						$(this).prop('checked', false);
					});
					
					$('input[type="checkbox"].medium').each(function(){
						$(this).prop('checked', false);
					});
					
					$('input[type="checkbox"].large').each(function(){
						$(this).prop('checked', false);
					});
				}

				function selectToppings(){	
					
					$('input:checkbox:checked.small').each(function(){
						arrS.push($(this).attr('name'));
					});
					
					$('input:checkbox:checked.medium').each(function(){
						arrM.push($(this).attr('name'));
					});
					
					$('input:checkbox:checked.large').each(function(){
						arrL.push($(this).attr('name'));
					});
				}

				function createPizza(size,price,toppings){
					var tempT = ['no toppings'];
					var dataT = [];
					if(toppings.length == 0){
						dataT.push(...tempT);
					}
					else{
						dataT.push(...toppings); 
					}
					
					$.ajax({
						
						url: './Pizza.php?action=createPizza',
						data:{
							size:size,
							price:price,
							toppings:dataT,
							status:'pendiente'
						},
						
						success: function(result){
							if(result != 'No Price'){
								if(result != 'No Size'){
									$('#idPizza').val(result);
								}
								else{
									console.log(result);
								}
							}
							else{
								console.log(result);
							}
						}
					});
				}

				function deletePizza(id){
					$.ajax({
						url: './Pizza.php?action=deletePizza',
						data: {
							id: id
						},
						success: function(result){
							getPizzas();
						}
					});
				}

				function payPizza(id){
					$.ajax({
						url: './Pizza.php?action=payPizza',
						data: {
							id: id,
							status: 'pagado'
						},
						success: function(result){
							getPizzas();
						}
					});
				}

				function limpiar(){
					$.ajax({
						url: './Pizza.php?action=vaciarSession',
						success: function(result){
							if(result == 'OK'){
								console.log("Sesion vaciada");
							}
							else{
								console.log(result);
							}
						}
					});
				}

				function getPizzas(){
					$.ajax({
						url: './Pizza.php?action=getPendingPizza',
						success: function(result) {
							var html = '';
							var toppingsM = []
							if (result != ''){
								if (result == 'No Pizzas'){
								}
								else{
									json = jQuery.parseJSON(result);
									
									var arrtemp = [];
									$('input:checkbox.order').each(function(){
										arrtemp.push($(this).attr('name'));
									});

									$('input:checkbox.order').each(function(){
										$(this).prop('checked', false);
									});	
									var pizzas = 1;
									for (let i = 0; i < json.length; i++) {
										const element = json[i]['status'];
										if(element == 'confirmada'){	

											const toppings = json[i]['toppings'];
											console.log(toppings);
											for (let j = 0; j < toppings.length; j++) {
												const tops = toppings[j];
												
												const checkbox = arrtemp;
												
												const condition = $.inArray(tops, arrtemp);

												if ( condition != -1) {							
													toppingsM.push(tops);
												}										
											}											
											 html = html +
														'<li>'+
															'<a href="#">'+
																'<span>'+
																	'<i class="fas fa-pizza-slice"></i>&nbsp;&nbsp;'+
																	'<h3>Pizza #'+pizzas+'</h3>'+
																'</span>'+
																'<p>Size </p><span class="text-menu">'+ json[i]['size']+'</span>'+
																'<p>Toppings </p><span class="text-menu"> '+ toppingsM +'</span>'+
																'<p>Price </p><span class="text-menu">$'+ json[i]['price']+'</span>'+
																'<p>'+
																	'<span href="#" class="btn-delete" id="btnDelete-'+json[i]['id']+'" onclick="deletePizza('+json[i]['id']+');">Delete</span>'+
																	'<span href="#" class="btn-pay" id="btnPay-'+json[i]['id']+'" onclick="payPizza('+json[i]['id']+');">Pay</span>'+
																'</p>'+
															'</a>'+
														'</li>';
											pizzas++;																																	
										}
										toppingsM.length = 0;
									}
									
									$('#menu').html(html);
									
									if(pizzas == 0){
									}									
								}																
							}
							else{
								console.log('error -' + result);
							}

						},
						error: function() {
							showError('Error Reaching pizzapizza.php');
						}
					});
				}
				
				function getPendingPizza(){
					$.ajax({
						url: './Pizza.php?action=getPendingPizza',
						success: function(result) {

							if (result != ''){
								if (result == 'No Pizzas'){
									$('#overlay, #overlay-back').fadeIn(500); 
								}
								else{
									json = jQuery.parseJSON(result);
									
									var arrtemp = [];
									$('input:checkbox.order').each(function(){
										arrtemp.push($(this).attr('name'));
									});
									$('input:checkbox.order').each(function(){
										$(this).prop('checked', false);
									});	
									var pendientes = 0;
									for (let i = 0; i < json.length; i++) {
										const element = json[i]['status'];
										if(element == 'pendiente'){																			
											const toppings = json[i]['toppings'];
											for (let j = 0; j < toppings.length; j++) {
												const tops = toppings[j];
												
												const checkbox = arrtemp;
												
												const condition = $.inArray(tops, arrtemp);

												if ( condition != -1) {							
													$('input[name="'+tops+'"].order').prop('checked', true);
												}										
											}
											$('#overlay, #overlay-back').fadeIn(500); 
											$('.card').fadeOut(1); 
											$('#cardOrder').css('display','flex'); 	
											$('#idPizza').val(json[i]['id']);
											pendientes++;																																	
										}
									}

									if(pendientes == 0){
										$('#overlay, #overlay-back').fadeIn(500);
									}									
								}																
							}
							else{
								console.log('error -' + result);
							}
						},
						error: function() {
							showError('Error Reaching pizzapizza.php');
						}
					});
				}
				
				function updateToppings(id,toppings) {
					var tempT = ['no toppings'];
					var dataT = [];
					if(toppings.length == 0){
						dataT.push(...tempT);
					}
					else{
						dataT.push(...toppings); 
					}
					$.ajax({
						url: './Pizza.php?action=updateToppings',
						data: {
							topping: dataT,
							id: id,
							status: 'confirmada'
						},
						success: function(result) {
							$('input[type="checkbox"].order').each(function(){
								$(this).prop('checked',false);
							});							 
							getPizzas();						
						},
						error: function() {
							showError('Error Reaching pizzapizza.php');
						}
					});
				}

				//funcion para obtener los datos de ordenes confirmadas y pagadas
				function getToppings() {
					$.ajax({
						url: './Pizza.php?action=getToppings',
						success: function(result) {
							try {
								json = jQuery.parseJSON(result);
							} catch (e) {
								showError("Invalid JSON returned from server: " + result);
								return;
							}
							if (json["success"] === "0") {
								showError(json["errormsg"]);
							} else {
								if (Object.keys(json["toppings"]).length > 0) {
									$("#listToppings").empty();
									$.each(json["toppings"],function(key,value) {
										//aqui no seria append sino html con la lista actualizada de toppings
										$("#listToppings").html("<li data-toppingid='" + key + "'>" + value + "</li>");
									});
								} else {
									$("#divToppings").html("");
								}
							}
						},
						error: function() {
							showError('Error Reaching Server');
						}
					});
				}

				function showError(message) {
					// alert("ERROR: " + message);
					console.log("ERROR: " + message);
					// $("#listToppings").html(message);
				}
			</script>

			<style type="text/css">


				#listToppings li {
					border: 1px solid #F2F2F2;
					list-style-type: none;
				}
			</style>
		</head>
		<body>
			
			<header>
				<a href="#" class="logo"><img src="logotipo.png" alt="logo"/></a>
			</header>
			<section class="banner" id="banner">
				<div class="content">
					<h2>Always choosing the best!</h2>
					<p>Select and create your pizza!</p>
					<a href="#" class="btn" id="btnStart">Let's Start!</a>
				</div>
				<div id="overlay-back"></div> 
				<div id="overlay" class="container">					
					<div class="card" id="small">
						<span></span>
						<div class="card-image">
						</div>
						<div class="card-text">
							<span class="title">Let's build your pizza!</span>
							<h2>
								Small Size
							</h2>
							<p>
								<p>Price: $100.00</p>
								<div class="container-buttons">
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Pepperoni" >
											<span>Pepperoni</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Extra cheese">
											<span>Extra cheese</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Mushrooms">
											<span>Mushrooms</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Pineapple" >
											<span>Pineapple</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Bacon">
											<span>Bacon</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Black olives">
											<span>Black olives</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Sausage">
											<span>Sausage</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Chicken">
											<span>Chicken</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Ham">
											<span>Ham</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping small" name="Meat">
											<span>Meat</span>
										</label>
									</div>	
								</div>
							</p>
						</div>
						<div class="card-button">				
							<a href="#" class="btn-next" id="btnPizzaS"><i class="fas fa-angle-right"></i></a>
						</div>
					</div>

					<div class="card" id="medium">
						<span></span>
						<div class="card-image">
						</div>
						<div class="card-text">
							<span class="title">Let's build your pizza!</span>
							<h2>
								Medium Size
							</h2>
							<p>
								<p>Price: $150.00</p>
								<div class="container-buttons">
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Pepperoni">
											<span>Pepperoni</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Extra cheese">
											<span>Extra cheese</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Mushrooms">
											<span>Mushrooms</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Pineapple">
											<span>Pineapple</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Bacon">
											<span>Bacon</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Black olives">
											<span>Black olives</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Sausage">
											<span>Sausage</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Chicken">
											<span>Chicken</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Ham">
											<span>Ham</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping medium" name="Meat">
											<span>Meat</span>
										</label>
									</div>	
								</div>
							</p>
						</div>
						<div class="card-button">				
							<a href="#" class="btn-next" id="btnPizzaM"><i class="fas fa-angle-right"></i></a>
						</div>
					</div>

					<div class="card" id="large">
						<span></span>
						<div class="card-image">
						</div>
						<div class="card-text">
							<span class="title">Let's build your pizza!</span>
							<h2>
								Large Size
							</h2>
							<p>
								<p>Price: $175.00</p>
								<div class="container-buttons">
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Pepperoni">
											<span>Pepperoni</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Extra cheese">
											<span>Extra cheese</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Mushrooms">
											<span>Mushrooms</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Pineapple">
											<span>Pineapple</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Bacon">
											<span>Bacon</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Black olives">
											<span>Black olives</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Sausage">
											<span>Sausage</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Chicken">
											<span>Chicken</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Ham">
											<span>Ham</span>
										</label>
									</div>
									<div>
										<label>
											<input type="checkbox" class="topping large" name="Meat">
											<span>Meat</span>
										</label>
									</div>	
								</div>
							</p>
						</div>
						<div class="card-button">				
							<a href="#" class="btn-next" id="btnPizzaL"><i class="fas fa-angle-right"></i></a>
						</div>
					</div>
					
					<div class="card-order" id="cardOrder">
						<div class="card-order-img">
							<img src="pizza_cards.png">
						</div>
						<div class="card-order-info">
							<input id="idPizza" value="0" hidden>
							<div class="card-order-date">
								<span id="date"></span>
							</div>
							<h1 class="card-order-title">Order Confirmation</h1>
							<p id="order-data" class="card-order-data" price="150.00" size="Large">
								Large pizza <br>
								Price - $150.00
							</p>
							<div class="container-buttons">
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Pepperoni">
										<span>Pepperoni</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Extra cheese">
										<span>Extra cheese</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Mushrooms">
										<span>Mushrooms</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Pineapple">
										<span>Pineapple</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Bacon">
										<span>Bacon</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Black olives">
										<span>Black olives</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Sausage">
										<span>Sausage</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Chicken">
										<span>Chicken</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Ham">
										<span>Ham</span>
									</label>
								</div>
								<div>
									<label>
										<input type="checkbox" class="topping order" name="Meat">
										<span>Meat</span>
									</label>
								</div>	
							</div>
								<div class="card-order-button">				
									<a href="#" class="btn-order" id="btnOrder">Order Pizza!</a>
								</div>
						</div>
					</div>
					<a href="#" class="close" id="close"></a>
				</div> 

				<div class="container-side">
					<div class="icon-side">
						<input type="checkbox" id="check">
						<label for="check" class="open-bar"><i class="fas fa-shopping-cart"></i></label>	
					</div>
					<div class="sidebar-wrapper">
						<div class="sidebar">
							<div class="avatar-wrapper">
								<label for="check" class="close-bar"><i class="fas fa-times"></i></label>
								<p class="title-bar"> Your Order!</p>
							</div>
							<nav>
								<ul class="menu" id="menu">
									<li>
										<a href="#">
											<span>
												<i class="fas fa-pizza-slice"></i>&nbsp;&nbsp;
												<h3>Order #1</h3>
											</span>											
											<p>Size</p>
											<p>Toppings</p>
											<p>Price</p>
											<p>																						
												<span href="#" class="btn-delete" >Delete</span>
												<span href="#" class="btn-pay" >Pay</span>													
											</p>
																			
										</a>
									</li>
								</ul>
							</nav>
						</div>
					</div>					
				</div>	
			</section>	
		</body>
	</html>
