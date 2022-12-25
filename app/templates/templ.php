<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
  <meta name="description" content="<?php echo $description; ?>">
  <META NAME="keywords" CONTENT="маникюр, ногти, визаж, грим, ресницы, тени, помада, парикмахер, парикмахерская, прическа, стрижка">
  <meta HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
  <meta HTTP-EQUIV="Content-language" CONTENT="ru-RU">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">

  <?php echo $meta_noindex; ?>
  <meta name="author" content="I-Jurij">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/normalize.css" />

<!-- light dark mode. Light is default -->
  <script>
    // If `prefers-color-scheme` is not supported, fall back to light mode.
    // In this case, light.css will be downloaded with `highest` priority.
    if (window.matchMedia('(prefers-color-scheme)').media === 'not all') {
      document.documentElement.style.display = 'none';
      document.head.insertAdjacentHTML(
          'beforeend',
          '<link rel="stylesheet" href="css/light.css" onload="document.documentElement.style.display = ``">'
      );
    }
  </script>
  <link rel="stylesheet" href="css/dark.css" media="(prefers-color-scheme: dark)">
  <link rel="stylesheet" href="css/light.css" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)">
  <link rel="stylesheet" href="css/style.css">

<!-- css for js files 
  <link rel="stylesheet" href="js/fancybox.css" />
  <link rel="stylesheet" href="js/panzoom.css" />
-->
  <link rel="icon" href="imgs/favicon.png" />
  <!-- <link href="mobile.css" rel="stylesheet" media="screen and (max-width: 600px)"> -->
  <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script> -->
  <!--<script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>  -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
  <div class="wrapper">
    <header class="he stickyheader flex">
      <div class="he_soz_tlf flex">
        <div class="he_soz">
          <a href="tg://resolve?domain=<?php echo $telegram; ?>" title="Telegram" class="he_soz-tg" target="_blank" rel="noopener"></a>
          <a href="https://vk.com/<?php echo $vk; ?>" title="Вконтакте" class="he_soz-vk" target="_blank" rel="noopener"></a>
        </div>

        <div class="he_tlf">
            <a href="tel:<?php echo $tlf1; ?>"><?php echo $tlf1; ?></a>
            <!-- <a href="tel:<?php //echo $tlf2; ?>"><?php //echo $tlf2; ?></a> -->
        </div>
      </div>
      <!--
      <div class="he_adres">
        <a class="he_adres_a" href="99-karta">г. Севастополь, ул. Такаято, д.№, офис №</a>
      </div>
      -->
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
          <div class="he_soz_tlf flex">
            <div class="he_soz">
              <a href="tg://resolve?domain=<?php echo $telegram; ?>" title="Telegram" class="he_soz-tg" target="_blank" rel="noopener"></a>
              <a href="https://vk.com/<?php echo $vk; ?>" title="Вконтакте" class="he_soz-vk" target="_blank" rel="noopener"></a>
            </div>

            <div class="he_tlf">
                <a href="tel:<?php echo $tlf1; ?>"><?php echo $tlf1; ?></a>
                <!-- <a href="tel:<?php //echo $tlf2; ?>"><?php //echo $tlf2; ?></a> -->
            </div>
          </div>
          <!--
          <div class="he_adres">
            <a class="he_adres_a" href="99-karta">г. Севастополь, ул. Такаято, д.№, офис №</a>
          </div>
          -->

        <div class="foot_div">
          <?php echo "2022 - " . date('Y') . PHP_EOL; ?>
        </div>

        <div class="foot_div adm">
          <a href="<?php echo $adm ?>" class=""><?php echo $adm ?></a>
        </div>

    </footer>

  </div>
  <script src="js/fancybox.umd.js"></script>
  <script>
  //  JavaScript will go here
</script>
</body>
</html>
