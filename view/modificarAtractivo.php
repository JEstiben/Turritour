<?php
include '../public/header.php';
?>

<?php
    include '../business/atractivoBusiness.php';
    if(session_status() != 2){
        session_start();
    }
    if(isset($_SESSION['Usuario'])){
        $atractivoBusiness = new atractivoBusiness();
        $atractivo = $atractivoBusiness->obtenerAtractivoId($_POST["idAtractivo"]);
    }
?>

<!-- Contenido -->
<div class="about">
	<div class="container">
		<?php
            if(session_status() != 2){
                session_start();
            }
            if(isset($_SESSION['Usuario'])){
        ?>
        <div class="col-md-offset-1 col-md-10" style="background: #8492A6; border-radius: 2em;">
			<div class="col-md-offset-1 col-md-10">
                <form method="post" id="formulario" enctype="multipart/form-data">
    				<div class="col-md-12" style="text-align: center;">
    					<h2>Modificar Atractivo Tur&iacute;stico</h2>
    				</div>
    				<div class="col-md-offset-3 col-md-6">
    					<h2>
                        	<div id="map"></div>
                        	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1JuYmoq83Om5mLz0qyg_k1viClteC2NU&callback=initMap"></script>
                    	</h2>
    				</div>
    				
    				<div class="col-md-12" style="text-align: center;">
    					<div class="col-md-3" style="text-align: center;">
    							<label>Atractivo</label>
    							<?php
                                    echo '<input type="hidden" name="idAtractivo" id="idAtractivo" value="'.$_POST["idAtractivo"].'" />';
                                    echo '<input class="form-control" type="text" name="atractivo" id="atractivo" value="'.$atractivo->getNombreAtractivo().'" disabled>';
                                ?>
    							<label>Latitud</label>
    							<input class="form-control" type="text" name="latitud" id="latitud" disabled>
    							<label>Longitud</label>
    							<input class="form-control" type="text" name="longitud" id="longitud" disabled>
    					</div>
    					<div class="col-md-offset-1 col-md-4" style="text-align: center;">
    						<label>Tipo Camino</label>
                            <select id="tipo_camino" name="tipo_camino" class="form-control">
                                <?php
                                echo $atractivo->getTipoCaminoAtractivo();
                                    if($atractivo->getTipoCaminoAtractivo() == "Asfalto"){
                                ?>
                                    <option selected="true" value="Asfalto">Asfalto</option>
                                    <option value="Piedra">Piedra</option>
                                    <option value="Tierra">Tierra</option>
                                <?php
                                    }else if($atractivo->getTipoCaminoAtractivo() == "Piedra"){
                                ?>
                                    <option value="Asfalto">Asfalto</option>
                                    <option selected="true" value="Piedra">Piedra</option>
                                    <option value="Tierra">Tierra</option>
                                <?php
                                    }else if($atractivo->getTipoCaminoAtractivo() == "Tierra"){
                                ?>
                                    <option value="Asfalto">Asfalto</option>
                                    <option value="Piedra">Piedra</option>
                                    <option selected="true" value="Tierra">Tierra</option>
                                <?php
                                    }
                                ?>
                            </select>
    						<label>Imagen</label>
    						<div class="fileUpload btn btn-success" style="text-align: center;">
    						    <span>Subir imagen</span>
    						    <input type="file" name="imagen" id="imagen" class="upload" accept="image/jpeg"/>
    						</div>
    						<label>Link video</label>
    						<?php
                                echo '<input class="form-control" type="text" name="video" id="video" value="'.$atractivo->getVideoAtractivo().'">'
    						?>
                            <input type="button" value="Modificar" name="modifica" id="modifica" onclick="modificar();" class="btn btn-success"/>
    					</div>
    					<div class="col-md-offset-1 col-md-3" style="text-align: center;">
    						<label>Descripci&oacute;n</label>
                            <?php
                                echo '<textarea class="form-control" rows="7" name="descripcion" id="descripcion">'.$atractivo->getDescripcionAtractivo().'</textarea>'
                            ?>
    					</div>
    				</div>
                </form>
			</div>
		</div>
        <?php
            }else{
        ?>
        <div class="col-md-offset-1 col-md-10" style="background: #8492A6; border-radius: 2em;">
            <div class="col-md-offset-1 col-md-10">
                <div class="col-md-offset-3 col-md-6" style="text-align: center;">
                    <img style="max-width: 60%;" src="../images/error.png">
                </div>
                <div class="col-md-12" style="text-align: center;">
                    <h2>Error al cargar la p&aacute;gina.</h2>
                </div>
            </div>
        </div>
        <?php
            }
        ?>
	</div>
</div>
<!-- Contenido -->
<?php
include '../public/footer.php';
?>
<script type="text/javascript">
	$(document).ready(function () {
        initMap();
    });

	function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 9.9062623, lng: -83.68420470000001},
            zoom: 15
        });
        var geocoder = new google.maps.Geocoder();
        if(document.getElementById('atractivo').value != ""){
        	geocodeAddress(geocoder, map);
        }
    }

	function geocodeAddress(geocoder, resultsMap) {
        var atractivo = document.getElementById('atractivo').value;

        geocoder.geocode({'address': atractivo}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });            
            document.getElementById("latitud").value = results[0].geometry.location.lat();
            document.getElementById("longitud").value = results[0].geometry.location.lng();

          } else {
            alert('La localización no fue satisfactoria por la siguiente razón: ' + status);
          }
        });
    }

    function modificar() {
      	var latitud = document.getElementById("latitud").value;
        var longitud = document.getElementById("longitud").value;

        if (latitud != "" && longitud != ""){
            var imagen = document.getElementById("imagen").value;

            if(imagen != ""){
                var video = document.getElementById("video").value;

                if(video != ""){
                    var descripcion = document.getElementById("descripcion").value;

                    if(descripcion != ""){
                        var formData = new FormData(document.getElementById("formulario"));
                        formData.append("modificarAtractivo", "modificarAtractivo");
                        formData.append("atractivo", document.getElementById("atractivo").value);
                        formData.append("latitud", document.getElementById("latitud").value);
                        formData.append("longitud", document.getElementById("longitud").value);
                        $.ajax({
                            url: '../business/atractivoAction.php',
                            type: "POST",
                            dataType: "html",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false
                        })
                        .done(function(data){
                            if(data == "true"){
                                mostrarMensaje("success", "Éxito al modificar el atractivo.");
                            }else if(data == "false"){
                                mostrarMensaje("error", "Error al modificar el atractivo.");
                            }else if(data == "error"){
                                mostrarMensaje("error", "El formato de la imagen es incorrecto.");
                            }//if-else
                        });
                    }else{
                        mostrarMensaje("error", "Debe agregar una descripcion para el atractivo.");
                    }//if-else
                }else{
                    mostrarMensaje("error", "Debe agregar una dirección para el video del atractivo.");
                }//if-else
            }else{
                mostrarMensaje("error", "Debe cargar una imagen.");
            }//if-else
        }else{
            mostrarMensaje("error", "Debe cargar el atractivo.");
        }//if-else
    }//modificar

    function mostrarMensaje(estado,mensaje){
        if(estado === "success"){
            reset();
            alertify.success(mensaje);
            return false;
        }else if(estado === "error"){
            reset();
            alertify.error(mensaje);
            return false;
        }//if-else
    }//mostrarMensaje

    /*FUNCION QUE LIMPIA EL ESPACIO PARA COLOCAR LAS NOTIFICACIONES*/
    function reset () {
        $("#toggleCSS").attr("href", "../js/alertify.default.css");
        alertify.set({
            labels : {
                ok     : "OK",
                cancel : "Cancel"
            },
            delay : 5000,
            buttonReverse : false,
            buttonFocus   : "ok"
        });
    }//reset
</script>