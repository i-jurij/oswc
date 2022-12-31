<!doctype html>
<html lang="<?php echo $a = (isset($data['html_lang']) and !empty($data['html_lang'])) ? htmlspecialchars($data['html_lang']) : 'ru'; ?>">
<head>
  <meta charset="<?php echo $b = (isset($data['charset']) and !empty($data['charset'])) ? htmlspecialchars($data['charset']) : 'utf-8' ; ?>">
  <meta name="referrer" content="origin-when-cross-origin">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">

  <title>
    <?php 
      echo $c = (isset($data['page_title']) and !empty($data['page_title'])) ? htmlspecialchars($data['page_title']) : 'Title of page';
    ?>
  </title>
  <meta name="description" content="<?php echo $d = (isset($data['page_meta_description']) and !empty($data['page_meta_description'])) ? htmlspecialchars($data['page_meta_description']) : 'Description of page'; ?>">
  <META NAME="keywords" CONTENT="<?php echo $e = (isset($data['page_meta_keywords']) and !empty($data['page_meta_keywords'])) ? htmlspecialchars($data['page_meta_keywords']) : 'Keywords of page'; ?>">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="<?php echo $f = (isset($data['robots']) and !empty($data['robots'])) ? htmlspecialchars($data['robots']) : 'INDEX, FOLLOW'; ?>">
  <meta name="author" content="I-Jurij">
  <!-- <link rel="stylesheet" type="text/css" href="public/css/first/normalize.css" />
  <link rel="stylesheet" type="text/css" href="public/css/first/style.css" charset="utf-8" /> -->
  <?php echo htmlspecialchars($data['css']); ?>

<!-- <link rel="stylesheet" type="text/css" href="../../../public/css/first/font-awesome.min.css"> -->
<!-- <link href="mobile.css" type="text/css" rel="stylesheet" media="screen and (max-width: 600px)"> -->
<!-- css for js files 
  <link rel="stylesheet" type="text/css" href="first/js/fancybox.css" />
  <link rel="stylesheet" type="text/css" href="first/js/panzoom.css" />
-->
  <link rel="icon" href="public/imgs/favicon.png" />
  <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script> -->
  <!--<script type="text/javascript" src="first/js/jquery-3.6.0.min.js"></script>  -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
  <div class="wrapper">
    <header class="he stickyheader flex">
      <?php include 'public/templates/contacts.php'; ?>
    </header>

    <div class="main ">
      <section class="main_section">
        <div class="flex flex_top">

            <?php include $content_view; ?>

        </div>
      </section>
    </div>

    <footer class="foot">
      <div class="foot_div">
          <?php include 'public/templates/contacts.php'; ?>
      </div>
      <div class="foot_div">
        <?php echo "2022 - " . date('Y') . PHP_EOL; ?>
      </div>
    </footer>
  </div>

  <script type="text/jsx" src="public/js/fancybox.umd.js"></script>
  </body>
</html>