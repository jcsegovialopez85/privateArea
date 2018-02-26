<div id="login">
	<div class="container-fluid">
        <div class="row">
		    <div class="container">
			    <div class="row">
                    <div class="col-xs-12 col-md-4 col-md-push-4">
                        <h1 class="text-center">Login</h1>
                        <div class="message">
                            <?php $this->flashSession->output() ?>					
                        </div>
                        <div class="col-xs-12 text-center">
                            <form action="/index/login" method="post">
                                <div class="form-group">
                                    <label for="login">Usuario</label>
                                    <input type="text" class="form-control" id="login" name="login" placeholder="Usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                </div>
                                <div class="container-button">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                                <input type="hidden" name="<?php echo $this->security->getTokenKey() ?>" value="<?php echo $this->security->getToken() ?>"/>					
                            </form>
                        </div>	
                    </div>
                </div> 
			</div> 
		</div> 
	</div>  
</div>	  

