</body>
<footer id="" class="bg-dark text-white">
    <div class="container text-center p-2">
        <small>Copyright &copy; <?php echo date("Y"); ?> -  WWI-Webshop</small>
        <?php
        if (isset($mysqli)){
            mysqli_close($mysqli);
        }

        if (isset($pdo)){
            $pdo = null;
        }
        ?>
    </div>
</footer>
<script>
    $('a[href="' + this.location.pathname + '"]').parents('li,ul').addClass('active');
</script>
</html>