<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reservasi | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="<?php echo base_url('assets/adminLTE/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminLTE/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/adminLTE/plugins/toastr/toastr.min.css') ?>">
  
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <!-- <img src="<?php echo base_url('assets/images/logo/mb.png'); ?>" style="width: 40%">
    <br> -->
    <b class="text-danger">Sistem</b> Reservasi
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Masuk untuk memulai sesi anda</p>

      <form method="post" action="<?php echo site_url('loginweb/loginprocess'); ?>" >

        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Nama pengguna" required="required">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Kata sandi" required="required">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-danger float-sm-right" style="width:30%">Masuk</button>
          </div>
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


<!-- jQuery -->
<script src="<?php echo base_url('assets/adminLTE/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/adminLTE/dist/js/adminlte.js') ?>"></script>
<!-- Sweetalert -->
<script src="<?php echo base_url('assets/adminLTE/plugins/sweetalert2/sweetalert2.min.js') ?>"></script> 
<!-- Toastr -->
<script src="<?php echo base_url('assets/adminLTE/plugins/toastr/toastr.min.js') ?>"></script>

<?php if($this->session->flashdata('error_login')):?>
  <script type="text/javascript">
      toastr.error('Nama Pengguna atau Sandi Salah, Silahkan coba lagi.');
  </script>
<?php endif; ?>

</body>
</html>
