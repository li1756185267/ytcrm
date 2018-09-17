<?php $this->beginContent('@app/views/layouts/head.php'); ?>

<?php $this->endContent(); ?>

<?php $this->beginContent('@app/views/layouts/head-nav.php'); ?>

<?php $this->endContent(); ?>

<?php $this->beginContent('@app/views/layouts/aside.php'); ?>

    <?=$content?>
<?php $this->endContent(); ?>
    
</body>
</html>
