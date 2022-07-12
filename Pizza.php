<?php

/* *************************************************************************
 * Welcome to the Mendoza Corporation, PIZZA PIZZA code exercise.  We'd like
 * to get to know your skillset and style.  We have this "application",
 * designed to let a user create a list of the toppings they want on their pizza.
 * We store this list on the backend in case the user leaves the application
 * and returns to the application at a later time.  Normally this would be done
 * in a database, but for this exercise we are using a PHP session.
 *
 * There is one problem -- it doesn't allow the user to remove a topping if
 * they change their mind.  We'd like you to add that functionality to the
 * application.  We would prefer you stick to AJAX so that the imaginary part
 * of the application interface does not have to be reloaded.  Aside from that,
 * you have complete creative freedom.
 *
 * So, this is your time to shine!  Show us who you are and what you can do.
 * Use images, css, javascipt that you would like. Make us say WOW!!!
 *
 * If you have any questions, need any help or explanation of any of the
 * code below, please don't hesitate to contact daniela@unilinktransportation.com
 * *************************************************************************
 */
 function getSession(){
   $id = 0;
   $result = array();
    if(isset($_SESSION['pizzas'])){
      foreach($_SESSION['pizzas'] as $value => $top ){
        $id = $top['id'];
      }
      $id++;
    }
    else{
      $_SESSION['pizzas'] = array();
    }
    return $id;
 }

	if (!isset($_GET['action'])) {
		$_GET['action'] = '';
	}

	session_start();

  if($_GET['action'] == 'vaciarSession'){
    session_unset();
    echo 'OK';
  }

	else if($_GET['action'] == 'getPizzasSession'){
    getSession();
  }
  
  else if($_GET['action'] == 'getPendingPizza'){
    if (isset($_SESSION['pizzas'])) {
      echo json_encode($_SESSION['pizzas']);
      exit;
    }
    else{
      echo 'No Pizzas';
    }
	}

  else if($_GET['action'] == 'createPizza'){
    $id = getSession();
    if (isset($_GET['size'])) {
      if (isset($_GET['price'])) {
        if (isset($_GET['status'])) {
          $newPizza = array('id'=>$id,'size'=>$_GET['size'],'price' => $_GET['price'],'toppings'=>$_GET['toppings'],'status'=>$_GET['status']);
        }
      }
      else{
        echo 'No Price';
      }
    }
    else{
      echo 'No Size';
    }
    array_push($_SESSION['pizzas'], $newPizza);
    echo $id;

  }

  else if($_GET['action'] == 'deletePizza'){
    if (isset($_GET['id'])) {
      $arr = array();
        foreach ( $_SESSION['pizzas'] as $key => $value) {

            if($value['id'] != $_GET['id']){
              array_push($arr,$value);
            }            
        }
        $_SESSION['pizzas'] = $arr;
        echo json_encode($arr);
    }
    else{
      echo 'no se mando nada';
    }
	}

	 else if($_GET['action'] == 'updateToppings') {
		$result = array();
		$result['errormsg'] = '';
		$result['success'] = 0;

      if (isset($_GET['id'])) {
        if (isset($_SESSION['pizzas'])) {
            $arr = array();
            foreach ( $_SESSION['pizzas'] as $key => $value) {

                if($value['id'] == $_GET['id']){
                  $value['toppings'] = $_GET['topping'];
                  $value['status'] = $_GET['status'];
                }
                array_push($arr,$value);
            }
            $_SESSION['pizzas'] = $arr;
            echo json_encode($arr);

          }
        else{
          	$result['errormsg'] = 'No Pizza';
        }
      }
  }
  
  else if($_GET['action'] == 'payPizza'){
    
      if (isset($_SESSION['pizzas'])) {
        $arr = array();
        foreach ( $_SESSION['pizzas'] as $key => $value) {

            if($value['id'] == $_GET['id']){
              $value['status'] = $_GET['status'];
            }
            array_push($arr,$value);
        }
        $_SESSION['pizzas'] = $arr;
        echo json_encode($arr);
      }
      else{
        echo 'no se mando nada';
      }    
  }

	else {
		//printForms();
	}
