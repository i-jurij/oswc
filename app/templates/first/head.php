<!doctype html>
<!-- <html lang="ru"> -->
<html lang="<?php echo $data['html_lang'] = (isset($data['html_lang']) and !empty($data['html_lang'])) ? $data['html_lang'] : 'ru'; ?>">
<head>
  <!-- <meta charset="utf-8" /> -->
  <meta charset="<?php echo $data['charset'] = (isset($data['charset']) and !empty($data['charset'])) ? $data['charset'] : 'utf-8' ; ?>">
  <title>
    <?php 
      echo $data['title'] = (isset($data['title']) and !empty($data['title'])) ? $data['title'] : 'Title of page';
    ?>
  </title>
  <meta name="description" content="<?php echo $data['description'] = (isset($data['description']) and !empty($data['description'])) ? $data['description'] : 'Description of page'; ?>">
  <!-- <META NAME="keywords" CONTENT="маникюр, ногти, визаж, грим, ресницы, тени, помада, парикмахер, парикмахерская, прическа, стрижка"> -->
  <META NAME="keywords" CONTENT="<?php echo $data['keywords'] = (isset($data['keywords']) and !empty($data['keywords'])) ? $data['keywords'] : 'Keywords of page'; ?>">
  <!--<meta HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8"> -->
  <!-- <meta HTTP-EQUIV="Content-language" CONTENT="ru-RU"> -->
  <!-- <meta HTTP-EQUIV="Content-language" CONTENT="<?php //echo $data['language'] = (isset($data['language']) and !empty($data['language'])) ? $data['language'] : 'ru-RU'; ?>"> -->
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