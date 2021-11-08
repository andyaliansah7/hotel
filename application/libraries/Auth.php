<?php
  /**
   * Auth Library utk validasi Login
   *
   *@author Elsa Damayanti <elsadamayanti98@gmail.com>
   */
  class Auth {
      /**
       * Property
       */
      var $ci = null;
      
      /**
       * Constructor Library
       */
      public function __construct()
      {
          $this->ci =& get_instance();
      }
      /**
      * Check autorisasi menggunakan session yg didapat dari process login
      */
     public function check_auth()
     {
         // jika session userdata user id tidak ada
         // dilanjutkan kehalaman dashboard dan session yg ada dihapus
         if(!$this->ci->session->userdata('user_id'))
         {
             //$this->ci->session->sess_destroy();

             $message = "Silahkan login.";
             $this->ci->session->set_flashdata('notifikasi_login', $message);
             
             redirect('loginweb');
         }
     }


  }
?>