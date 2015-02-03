<?php 


  $instanciaCript = new Cripty;

  

   if ( isset( $_GET['id'] ) || $encript == $_GET['id'] ) {
   	  define("ENCRYPTION_KEY", $current_user->user_email);
		
	    // echo $decrypted = $instanciaCript->decrypt($_GET['id'], ENCRYPTION_KEY);
   	  			//$decrypted = $dinstanciaCript->ecrypt($_GET['id'] , ENCRYPTION_KEY);
   
      } else {
      //include( 'pagina-antes-de-enviar.php' );
    }




?> 