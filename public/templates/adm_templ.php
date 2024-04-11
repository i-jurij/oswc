<!doctype html>
<?php

use App\Lib\Registry;

 $db = (!empty($data['page_db_data']['0'])) ?  $data['page_db_data']['0'] : null; ?>
<html lang="<?php echo $a = (isset($db['html_lang']) and !empty($db['html_lang'])) ? htmlspecialchars($db['html_lang']) : 'ru'; ?>">
<head>
  <meta charset="<?php echo $b = (isset($db['charset']) and !empty($db['charset'])) ? htmlspecialchars($db['charset']) : 'utf-8' ; ?>">
  <meta name="referrer" content="no-referrer">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">

  <title>
    <?php
      echo $c = (isset($db['page_title']) and !empty($db['page_title'])) ? htmlspecialchars($db['page_title']) : 'Title of page';
    ?>
  </title>
  <meta name="description" content="<?php echo $d = (isset($db['page_meta_description']) and !empty($db['page_meta_description'])) ? htmlspecialchars($db['page_meta_description']) : 'Description of page'; ?>">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
  <META NAME="Robots" CONTENT="<?php echo $f = (isset($db['robots']) and !empty($db['robots'])) ? htmlspecialchars($db['robots']) : 'NOINDEX, NOFOLLOW'; ?>">
  <meta name="author" content="I-Jurij">
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT.DS.'public'.DS.'css'.DS.'first'.DS.'normalize.css'; ?>" />
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT.DS.'public'.DS.'css'.DS.'first'.DS.'style.css'; ?>" />
  <link rel="icon" href="<?php echo URLROOT.DS; ?>public/imgs/key.png" />
</head>
<body>
  <div class="wrapper">
    <!-- <header class="stickyheader flex"></header> -->
    <div class="main ">
      <section class="main_section">
        <div class="flex flex_top">
          <div style="margin: 0 1rem 1rem 1rem; width: 100%; text-align:right;">
							<span style="margin: 0 1rem 0 0; color: blanchedalmond;">Здравствуйте, <b><?php echo $_SESSION['user_name']; ?></b></span>
							<a class="buttons" href="<?php echo URLROOT; ?>/adm/exit/">Выход</a>
					</div>
          <div class="content stickyheader">
            <!-- <h2><?php //echo $c = (isset($db['page_h1']) and !empty($db['page_h1'])) ? htmlspecialchars($db['page_h1']) : 'H1 of page';?></h2> -->
            <p class="nav"><?php echo menu($data); ?></p>
          </div>
          <?php
            if (!empty($data[0]['page_content'])) {
              print htmlspecialchars($data[0]['page_content']);
            }
            if ( (new SplFileInfo($content_view))->isReadable() ) {
              include $content_view;
            } elseif (is_string($content_view)) {
              print $content_view;
            }
          ?>
        </div>
      </section>
    </div>
  </div>

  <script type="text/javascript" src="<?php echo URLROOT.DS.'public'.DS.'js'.DS.'adm'.DS.'adm.js'; ?>"></script>
  <!-- <script type="text/jsx" src="public/js/fancybox.umd.js"></script> -->
  </body>
</html>
