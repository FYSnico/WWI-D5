</body>
<footer id="" class="bg-dark text-white">
    <div class="bekendeMerken">
        <img src="https://upload.wikimedia.org/wikipedia/commons/e/e9/IDEAL_Logo.png">
        <img src="https://img.stackshare.io/service/6683/Ia0vTZ_a_400x400.jpg">
    </div>
    <div class="container text-center p-2">
        <small>Copyright &copy; <?php echo date("Y"); ?> - WWI-Webshop</small>
        <?php
        //Database connecties sluiten aan het einde van de pagina
        if (isset($mysqli)) {
            mysqli_close($mysqli);
        }

        if (isset($pdo)) {
            $pdo = null;
        }
        ?>
    </div>
</footer>
<script>
    $('a[href="' + this.location.pathname + '"]').parents('li,ul').addClass('active');
</script>
</html>