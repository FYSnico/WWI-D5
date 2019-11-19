<?php 
include('components/header.php');
include("components/config.php");
?>
<div class="container">
    <div class="card shadow">
        <div class="row">
            <aside class="col-sm-5 border-right">
                <article class="gallery-wrap"> 
                    <div class="img-big-wrap">
                        <div> <a href="#"><img src="https://picsum.photos/470/400"></a></div>
                    </div>
                </article>
            </aside>
            <aside class="col-sm-7">
                <article class="card-body p-5">
                <h3 class="title mb-3">USB missile launcher (Green)</h3>
                <p class="price-detail-wrap"> 
                    <dl class="param param-inline">
                            <dt>Categorie: USB</dt>
                    </dl>
                    <span class="price h3 text-warning"> 
                        <span class="currency">€</span><span class="num">37.38</span>
                    </span> 
                </p>
                <hr>
                <div class="row">
                    <div class="col-sm-5">
                        <dl class="param param-inline">
                            <dt>Voorraad: 10</dt>
                        </dl>
                    </div>
                </div>
                <hr>
                <a href="#" class="btn btn-lg btn-outline-primary text-uppercase"> <i class="fas fa-shopping-cart"></i> Toevoegen </a>
            </aside>
        </div>
    </div>
</div>
<br><br>
<?php include('components/footer.php') ?>