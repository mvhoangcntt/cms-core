<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  <?php if (!empty($SEO)): ?>
    <title><?php echo isset($SEO['meta_title']) ? $SEO['meta_title'] : ''; ?></title>
    <meta name="description" content="<?php echo isset($SEO['meta_description']) ? $SEO['meta_description'] : ''; ?>"/>
    <meta name="keywords" content="<?php echo isset($SEO['meta_keyword']) ? $SEO['meta_keyword'] : ''; ?>"/>
    <!--Meta Facebook Page Other-->
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="<?php echo isset($SEO['meta_title']) ? $SEO['meta_title'] : ''; ?>"/>
    <meta property="og:description"
          content="<?php echo isset($SEO['meta_description']) ? $SEO['meta_description'] : ''; ?>"/>
    <meta property="og:image" content="<?php echo isset($SEO['image']) ? $SEO['image'] : ''; ?>"/>
    <meta property="og:url" content="<?php echo isset($SEO['url']) ? $SEO['url'] : base_url(); ?>"/>
    <!--Meta Facebook Page Other-->
    <link rel="canonical" href="<?php echo isset($SEO['url']) ? $SEO['url'] : base_url(); ?>"/>
  <?php else: ?>
    <title><?php echo $this->settings['title'] . ' - ' . $this->settings['name']; ?></title>
    <meta name="description"
          content="<?php echo isset($this->settings['meta_desc']) ? $this->settings['meta_desc'] : ''; ?>"/>
    <meta name="keywords"
          content="<?php echo isset($this->settings['meta_keyword']) ? $this->settings['meta_keyword'] : ''; ?>"/>
    <!--Meta Facebook Homepage-->
    <meta property="og:type" content="website"/>
    <meta property="og:title"
          content="<?php echo isset($this->settings['title']) ? $this->settings['title'] . ' | ' . $this->settings['name'] : ''; ?>"/>
    <meta property="og:description"
          content="<?php echo isset($this->settings['meta_desc']) ? $this->settings['meta_desc'] : ''; ?>"/>
    <meta property="og:image"
          content="<?php echo isset($this->settings['logo']) ? getImageThumb($this->settings['logo'], 400, 200) : ''; ?>"/>
    <meta property="og:url" content="<?php echo base_url(); ?>"/>
    <!--Meta Facebook Homepage-->
    <link rel="canonical" href="<?php echo base_url(); ?>"/>
  <?php endif; ?>

    <?php $asset_css[] = 'bootstrap.min.css'; ?>
    <?php $asset_css[] = '../fonts/font-awesome/css/font-awesome.min.css'; ?>
    <?php $asset_css[] = '../fonts/elegantIcon/elegantIcon.css'; ?>
    <?php $asset_css[] = 'animate.css'; ?>
    <?php $asset_css[] = 'owl.carousel.min.css'; ?>
    <?php $asset_css[] = 'main.css'; ?>

  <?php

    minifyCSS($asset_css, $this->templates_assets, true, false);
  ?>
  <link rel="icon"
        href="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 32, 32) : base_url("/public/favicon.ico"); ?>"
        sizes="32x32">
  <link rel="icon"
        href="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 192, 192) : base_url("/public/favicon.ico"); ?>"
        sizes="192x192">
  <link rel="apple-touch-icon-precomposed"
        href="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 180, 180) : base_url("/public/favicon.ico"); ?>">
  <meta name="msapplication-TileImage"
        content="<?php echo !empty($this->settings['favicon']) ? getImageThumb($this->settings['favicon'], 270, 270) : base_url("/public/favicon.ico"); ?>">
  <script>
    var urlCurrentMenu = window.location.href,
      urlCurrent = window.location.href,
      base_url = '<?php echo base_url(); ?>',
      media_url = '<?php echo MEDIA_URL . '/'; ?>',
      video_url = '<?php echo MEDIA_URL . 'video/'; ?>',
      currency_code = '<?php echo $this->session->currency_code; ?>',
      csrf_cookie_name = '<?php echo $this->config->item('csrf_cookie_name') ?>',
      csrf_token_name = '<?php echo $this->security->get_csrf_token_name() ?>',
      language = {},
      lang = {},
      csrf_token_hash = '<?php echo $this->security->get_csrf_hash() ?>';
    var page_style = '<?php echo !empty($oneItem->style) ? $oneItem->style : "" ?>';
  </script>

</head>
<body >
<div class="wrap">
    <?php
      $this->load->view($this->template_path . '_header');
      echo !empty($main_content) ? $main_content : '';
      $this->load->view($this->template_path . '_footer');
    ?>
</div>
<?php $asset_js[] = 'js/jquery.js'; ?>



<?php $this->minify->js($asset_js);
echo $this->minify->deploy_js(); ?>

<script>
    $(function(){
        Product.init();
    });
</script>
<?php echo !empty($this->settings['script_head']) ? $this->settings['script_head'] : '' ?>

<script type="text/javascript">
  $(document).ready(function () {
    toastr.options.escapeHtml = true;
    toastr.options.closeButton = true;
    toastr.options.positionClass = "toast-top-right";
    toastr.options.timeOut = 5000;
    toastr.options.showMethod = 'fadeIn';
    toastr.options.hideMethod = 'fadeOut';
    <?php if(!empty($this->session->flashdata('message'))): ?>
    toastr.<?php echo $this->session->flashdata('type'); ?>('<?php echo trim(strip_tags($this->session->flashdata('message'))); ?>');
    <?php
    unset($_SESSION['message']);
    endif;
    ?>
    setTimeout(sdkInit(), 1000);
  });

  function sdkInit() {
    (function (d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s);
      js.id = id;
      js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.10";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  }
</script>
<?php echo !empty($this->settings['script_body']) ? $this->settings['script_body'] : '' ?>
</body>
</html>