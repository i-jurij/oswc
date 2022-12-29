<?php 
  include 'app/templates/first/head.php'; 
  include 'app/templates/first/start_js.php';
?>

  <div class="wrapper">
    <?php include 'app/templates/first/header.php'; ?>

    <div class="main ">
      <section class="main_section">
        <div class="flex flex_top">

            <?php include $content_view; ?>

        </div>
      </section>
    </div>

    <?php include 'app/templates/first/footer.php'; ?>
  </div>

<?php 
  include 'app/templates/first/end_js.php';
  include 'app/templates/first/end_html.php'; 
?>
