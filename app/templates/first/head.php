<!doctype html>
<html lang="<?php echo $data['html_lang'] = (isset($data['html_lang']) and !empty($data['html_lang'])) ? $data['html_lang'] : 'ru'; ?>">
<head>
  <meta charset="<?php echo $data['charset'] = (isset($data['charset']) and !empty($data['charset'])) ? $data['charset'] : 'utf-8' ; ?>">
  <title>
    <?php 
      echo $data['page_title'] = (isset($data['page_title']) and !empty($data['page_title'])) ? $data['page_title'] : 'Title of page';
    ?>
  </title>
  <meta name="description" content="<?php echo $data['page_meta_description'] = (isset($data['page_meta_description']) and !empty($data['page_meta_description'])) ? $data['page_meta_description'] : 'Description of page'; ?>">
  <META NAME="keywords" CONTENT="<?php echo $data['page_meta_keywords'] = (isset($data['page_meta_keywords']) and !empty($data['page_meta_keywords'])) ? $data['page_meta_keywords'] : 'Keywords of page'; ?>">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="<?php echo $data['robots'] = (isset($data['robots']) and !empty($data['robots'])) ? $data['robots'] : 'INDEX, FOLLOW'; ?>">
  <meta name="author" content="I-Jurij">
  <link rel="stylesheet" href="public/css/first/normalize.css" />
  <!-- <link rel="stylesheet" href="../../../public/first/css/font-awesome.min.css"> -->

<!-- light dark mode. Light is default -->
  <script>
    // If `prefers-color-scheme` is not supported, fall back to light mode.
    // In this case, light.css will be downloaded with `highest` priority.
    if (window.matchMedia('(prefers-color-scheme)').media === 'not all') {
      document.documentElement.style.display = 'none';
      document.head.insertAdjacentHTML(
          'beforeend',
          '<link rel="stylesheet" href="public/first/css/light.css" onload="document.documentElement.style.display = ``">'
      );
    }
  </script>
  <link rel="stylesheet" href="public/css/first/dark.css" media="(prefers-color-scheme: dark)">
  <link rel="stylesheet" href="public/css/first/light.css" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)">
  <link rel="stylesheet" href="public/css/first/style.css">

<!-- css for js files 
  <link rel="stylesheet" href="first/js/fancybox.css" />
  <link rel="stylesheet" href="first/js/panzoom.css" />
-->
  <link rel="icon" href="public/imgs/first/favicon.png" />
  <!-- <link href="mobile.css" rel="stylesheet" media="screen and (max-width: 600px)"> -->
  <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script> -->
  <!--<script type="text/javascript" src="first/js/jquery-3.6.0.min.js"></script>  -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>