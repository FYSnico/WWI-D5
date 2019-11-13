<?php include 'components/header.php';?>
<div class="container">
    <form id="contact-form" class="bg-white p-3 rounded shadow" method="post" action="#" role="form">
        <div class="">
            <h1>Contact Ons</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor eligendi et exercitationem illo, ipsa laborum, magnam maxime modi perspiciatis, praesentium quibusdam recusandae saepe ut! Dolorem error expedita inventore iusto odio.</p>
        </div>
        <br>
        <div class="controls">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="form_name">Voornaam *</label>
                        <input id="form_name" type="text" name="name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="form_lastname">Achternaam *</label>
                        <input id="form_lastname" type="text" name="surname" class="form-control" placeholder="Please enter your lastname *" required="required" data-error="Lastname is required.">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="form_email">Email *</label>
                        <input id="form_email" type="email" name="email" class="form-control" placeholder="Please enter your email *" required="required" data-error="Valid email is required.">
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="form_need">Specificeer uw behoefte *</label>
                        <select id="form_need" name="need" class="form-control" required="required" data-error="Please specify your need.">
                            <option value=""></option>
                            <option value="">Offerte aanvragen</option>
                            <option value="">Verzoek om bestelstatus</option>
                            <option value="">Vraag een kopie van een factuur aan</option>
                            <option value="">Anders</option>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="form_message">Bericht *</label>
                        <textarea id="form_message" name="message" class="form-control" placeholder="" rows="4" required="required" data-error="Please, leave us a message."></textarea>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-12 mb-1">
                    <input type="submit" class="btn btn-outline-danger btn-send" value="Verzenden">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-muted">De met een (<strong class="text-danger">*</strong>) gemarkeerde velden moeten worden ingevuld.</p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'components/footer.php';?>