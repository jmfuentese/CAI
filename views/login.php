<div class="section">
    <main>
        <center>
            <!--<img class="responsive-img" style="width: 150px;" src="views/img/anigif.gif" />-->
            <div class="section"></div>

            <h5 class="">Inicia sesión</h5>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 row"
                     style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                    <form class="col s12" method="post">
                        <div class='row'>
                            <div class='col s12'>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <i class="material-icons prefix">account_circle</i>
                                <input class='validate' type='text' name='user' id='user' autofocus required/>
                                <label for='user'>Usuario</label>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='input-field col s12'>
                                <i class="material-icons prefix">lock</i>
                                <input class='validate' type='password' name='password' id='password' required/>
                                <label for='password'>Contraseña</label>
                            </div>
                        </div>

                        <br/>
                        <center>
                            <div class='row'>
                                <button type='submit' name='btn_login'
                                        class='col s12 btn btn-large waves-effect indigo'>Entrar
                                </button>
                            </div>
                        </center>
                    </form>
                    <?php $login = new Controller();
                    $login->loginController();
                    ?>
                </div>
            </div>
        </center>

        <div class="section"></div>
        <div class="section"></div>
    </main>
