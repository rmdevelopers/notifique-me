<?php
	header('Content-Type: text/html; charset=utf-8');

	function notifica_cliente() {	 
	global $wpdb, $product;

	//obtendo o data
	ini_set('date.timezone','America/Sao_Paulo');
	$date = date('Y-m-d H:i:s');

	//obtendo o usuário atual
    $current_user = wp_get_current_user();	
    $current_email = wp_get_current_user();	

	//obtendo o usuário atual
    $user_id = get_current_user_id();


	$instanciaCript = new Cripty;   
   

	  	if ( isset( $_GET['id'] )) {

	  		//Obtendo o id do produto pela url
	  		$product_id  = $_GET['id'];
	  		
	  	    //DESNECESSÁRIO buscar o id pelo SKU
	  	    //$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

	  		//Obtendo a url do produto
			$link = get_permalink( $product_id );

	  		//Obtendo o título do produto
			$name_pedido_produto = get_the_title( $product_id );

	  		//Decripitando - DESNECESSÁRIO
		    $decrypted = $instanciaCript->decrypt_url($id);
		


		    //Inserindo os registros do cliente
		    $wpdb->insert( 
		    $wpdb->prefix.'notifique_me', 
		    array( 
		        'id_user' => $user_id, 
		        'user_name' => $current_user->user_firstname, 
		        'user_email' => $current_email->user_email, 
		        'id_pedido_produto' => $product_id, 
		        'name_pedido_produto' => $name_pedido_produto, 
		        'data' => $date,
		        'url' => $link,
		        'status' => 1
		        
		    ), 
		    array( 
		        '%s'
		         
		    ) 
		);



		    //AQUI FICARÁ O SQL PARA SALVAR:
		    /*
			
			id, id_cliente, nome,  email, data, Nome do produto, link do produto e SKU.
			- Salva tudo na tabela: [sufixo]_notifiqueme. Essa tabela deverá ser criada na ativação do plugin.


			- Depois de salvo tudo, no admin, aparecerá o novo cliente no update, somente a quantidade.
			exemplo: produto_nome: 3 solicitações.
			
			- O plugin deverá notificar os clientes(smtp) quando houver ao menos 1 produto adicionado ao stoque.

		    */








	   	  	
	   
	      } else {
	      echo "Falha na requisição!";
	    }

	}

	add_shortcode('notifica_cliente', 'notifica_cliente');






  ?>