<div class="container center-align" style="padding-top: 1px;">
    <br><br>
    <h5 class="">End session</h5>
    <div class="z-depth-1 grey lighten-4 row"
         style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE; width: 70%;">

        <div class="row">
            <form class="col s12" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="registration" type="number" class="validate" name="registration" required>
                        <label for="registration">Registration</label>
                    </div>
                    <button name="endSessionSubmit" class="modal-trigger waves-effect waves-light btn green darken-1"type="submit">OK
                    </button>
                </div>
            </form>
            <?php
            if (isset($_POST["registration"]) && isset($_POST["endSessionSubmit"])) {
                $matricula=$_POST["registration"];
                $r = new Controller();
                $r->endSessionController($matricula);
            }
            ?>
