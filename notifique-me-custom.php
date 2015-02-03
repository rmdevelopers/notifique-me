<?php

/**
 * Plugin Name: Notifique-me - PIPA Estoque
 * Plugin URI: pipa.digital
 * Description: Plugin para estoques zerados
 * Author: Rafael Mariano
 * Author URI: rdeveloper.mmusica.kinghost.net
 * Version: 1.0
 * Text Domain: Notifique-me
 *
 * Copyright: (c) 2015-2016 Pipa digital (rafaelmp.web@gmail.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @author    lol
 * @copyright Copyright (c) 2014-2015, Rafael Mariano
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Checando se WooCommerce está ativo
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
 


/**
 * Checando se há o arquivo notifique-me-custom.php e nt_rota.php na pasta do plugin
 **/
if ( file_exists( WP_PLUGIN_DIR . '/notifiqueme/notifique-me-custom.php' ) )
  include_once( WP_PLUGIN_DIR . '/notifiqueme/notifique-me-custom.php' );

if ( file_exists( WP_PLUGIN_DIR . '/notifiqueme/includes/nt_rota.php' ) )
  include_once( WP_PLUGIN_DIR . '/notifiqueme/includes/nt_rota.php' );

/**
 * Instalando a tabela do plugin no wordpress
 **/
function table_install_notifique_me() {
    global $wpdb, $notifique_me, $product;

    $table_name = $wpdb->prefix . "notifique_me";
    if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

        $sql = "
                    CREATE TABLE IF NOT EXISTS " . $table_name . "  (
                        id INT NOT NULL AUTO_INCREMENT ,
                        id_user VARCHAR(250) NULL,
                        user_name VARCHAR(250) NULL ,
                        user_email VARCHAR(250) NULL ,
                        id_pedido_produto INT NULL ,
                        name_pedido_produto VARCHAR(250) NULL,
                        data DATE NULL,
                        url VARCHAR(500) NULL,
                        status INT NULL,
                        PRIMARY KEY (id) )
                    DEFAULT CHARACTER SET = utf8
                    COLLATE = utf8_general_ci;
                ";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        add_option("notifique_me", $notifique_me);
    }
}
register_activation_hook(__FILE__,'table_install_notifique_me');

/**
 * Includes, classes e outros
 **/
plugin_arquivo('classes/nt_class_cripyt.php');
plugin_arquivo('classes/nt_class_Abstract.php');
plugin_arquivo('classes/nt_class_admin.php');
plugin_arquivo('classes/nt_class_admin.php');
plugin_arquivo('enviando/nt_shortcode.php');


  if ( is_admin() ) {


     /**
     *  Instanciando notifique_me_admin singleton
     **/

     $admin = notifique_me_admin::get_object();


     /**
     * Adicionando estilo
     **/
      add_action('admin_enqueue_scripts', 'my_styles');
        function my_styles() {
          //bootstrap
            //wp_register_style( 'bootstrap.min.css', plugins_url('notifiqueme/view/css/bootstrap.min.css'));
           // wp_enqueue_style( 'bootstrap.min.css' );
          //modern-business
            //wp_register_style( 'modern-business.css', plugins_url('notifiqueme/view/css/modern-business.css'));
            //wp_enqueue_style( 'modern-business.css' );
          //...
        }

  }




if ( is_multisite() ) {
 // require_once( WP_PLUGIN_DIR . '/theme-my-login/includes/class-theme-my-login-ms-signup.php' );

  // Instantiate Theme_My_Login_MS_Signup singleton
  //Theme_My_Login_MS_Signup::get_object();
}

/**
 * Verifica se há estoque ou não
 */
function envy_stock_catalog() {
    global $product;

    if ( $product->is_in_stock() ) { // há estoque
       
    	//quantidade de produtos
       // echo '<div class="stock" >' . $product->get_stock_quantity() . __( ' in stock', 'envy' ) . '</div>';
   


    } else { // não há no estoque

    	    echo $product->get_stock_quantity();


        	if ( is_user_logged_in() ) { // se estiver logado


              //////obtendo o status do usuário atual/////
               global $wpdb;
               //obtendo o id do usuário atual           
               $user_id = get_current_user_id();
               $table_name = $wpdb->prefix.'notifique_me';
               $tabela = $wpdb->get_results("SELECT * FROM $table_name WHERE id_user = $user_id");
         

              foreach($tabela as $b1){
                      $view1 .= $b1->status;
              }


              if($view1 < 1): //Ainda não pediu notificação deste produto
        		

            //obtendo o usuário atual
        	    $current_user = wp_get_current_user();

        	    //obtendo a URL da loja
        	    $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );

        	    //crypt DESNECESSÁRIO
              //$instanciaCript = new Cripty;

              //define("ENCRYPTION_KEY", $current_user->user_email);
              
              //NÃO É SEGURO! É MELHOR GERAR UM TOKEN!
              /*$nome = $instanciaCript->encrypt_url($current_user->user_firstname);
              $email = $instanciaCript->encrypt_url($current_user->user_email);*/
        	  	//Texto ou botão direcionando para a página notificacao
       
              //Obtendo SKU do produto - DESNECESSÁRIO
              $sku_Produto = $product->sku;
           
              $token = md5(uniqid(mt_rand(), true));
              echo '<a href="'. get_site_url().'/notificacao?id='.get_the_ID().'">Notifique-me!</a>';
       
              endif;  //FIM da verificação do staus
        	
        	} else {
        	    echo '<a href="'. site_url('wp-login.php') .'">Notifique-me!</a>';
        	}   


    }
}

add_action( 'woocommerce_after_shop_loop_item_title', 'envy_stock_catalog' );


if (!class_exists('notificacao_Plugin'))
{
  class notificacao_Plugin
  {
    public $_name;
    public $page_title;
    public $page_name;
    public $page_id;

    public function __construct()
    {
      $this->_name      = 'notificacao';
      $this->page_title = 'Notificação';
      $this->page_name  = $this->_name;
      $this->page_id    = '0';

      register_activation_hook(__FILE__, array($this, 'activate'));
      register_deactivation_hook(__FILE__, array($this, 'deactivate'));
      register_uninstall_hook(__FILE__, array($this, 'uninstall'));

      add_filter('parse_query', array($this, 'query_parser'));
      add_filter('the_posts', array($this, 'page_filter'));
    }

    public function activate()
    {
      global $wpdb;      

      delete_option($this->_name.'_page_title');
      add_option($this->_name.'_page_title', $this->page_title, '', 'yes');

      delete_option($this->_name.'_page_name');
      add_option($this->_name.'_page_name', $this->page_name, '', 'yes');

      delete_option($this->_name.'_page_id');
      add_option($this->_name.'_page_id', $this->page_id, '', 'yes');

      $the_page = get_page_by_title($this->page_title);

      if (!$the_page)
      {
        // Create post object
        $_p = array();
        $_p['post_title']     = $this->page_title;
        $_p['post_content']   = "Página gerada automaticamente. Caso há exclua, desative e reative o plugin: Notifique-me - PIPA Estoque.";
        $_p['post_status']    = 'publish';
        $_p['post_type']      = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status']    = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncatrgorised'

        // Insert the post into the database
        $this->page_id = wp_insert_post($_p);
      }
      else
      {
        // the plugin may have been previously active and the page may just be trashed...
        $this->page_id = $the_page->ID;

        //make sure the page is not trashed...
        $the_page->post_status = 'publish';
        $this->page_id = wp_update_post($the_page);
      }

      delete_option($this->_name.'_page_id');
      add_option($this->_name.'_page_id', $this->page_id);
    }

    public function deactivate()
    {
      $this->deletePage();
      $this->deleteOptions();
    }

    public function uninstall()
    {
      $this->deletePage(true);
      $this->deleteOptions();
    }



    public function query_parser($q)
    {
      if(!empty($q->query_vars['page_id']) AND (intval($q->query_vars['page_id']) == $this->page_id ))
      {
        $q->set($this->_name.'_page_is_called', true);
      }
      elseif(isset($q->query_vars['pagename']) AND (($q->query_vars['pagename'] == $this->page_name) OR ($_pos_found = strpos($q->query_vars['pagename'],$this->page_name.'/') === 0)))
      {
        $q->set($this->_name.'_page_is_called', true);
      }
      else
      {
        $q->set($this->_name.'_page_is_called', false);
      }
    }

    function page_filter($posts)
    {
      global $wp_query;

      if($wp_query->get($this->_name.'_page_is_called'))
      {
        $posts[0]->post_title = __('Notificação');
        //$posts[0]->post_content = '';
      }
      return $posts;
    }

    private function deletePage($hard = false)
    {
      global $wpdb;

      $id = get_option($this->_name.'_page_id');
      if($id && $hard == true)
        wp_delete_post($id, true);
      elseif($id && $hard == false)
        wp_delete_post($id);
    }

    private function deleteOptions()
    {
      delete_option($this->_name.'_page_title');
      delete_option($this->_name.'_page_name');
      delete_option($this->_name.'_page_id');
    }
  }
}
$notificacao = new notificacao_Plugin();

}



