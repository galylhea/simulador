<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Simulación de crédito</title>
	<link rel="stylesheet" href="assets/styles.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert/css/sweetalert2.css">
</head>
	<header>
		<h1>Simulación de crédito</h1>
    </header>
    <div class="main-content">
        <form class="form-validation" method="post" action="#">
			<div class="form-title-row">
				<h1>Ingrese los datos a continuación</h1>
			</div>
            <div class="form-row form-input-name-row">
                <label>
                    <span>Valor del préstamo</span>
                    <input type="text" name="txtValor" id="txtValor" maxlength="12" placeholder="0">
                </label>
            </div>
            <div class="form-row">
                <label>
                    <span>Plazo</span>
                    <select name="txtPlazo" id="txtPlazo">
                        <option value="0">Seleccione...</option>
                        <option value="12">12</option>
                        <option value="24">24</option>
                        <option value="36">36</option>
                        <option value="48">48</option>
                        <option value="60">60</option>
                    </select>
                </label>
            </div>
			<div class="form-row form-input-name-row">
                <label>
                    <span>Gradiente</span>
                    <input type="text" name="txtGradiente" id="txtGradiente" placeholder="0" maxlength="8" disabled>
                </label>
            </div>
            <div class="form-row text-center">
                <label class="form-checkbox">
                    <span>Vencida</span>
                    <input type="checkbox" id="chk-1" class="tipo" name="chk-1" checked disabled value="1">
                </label>
				<label class="form-checkbox">
                    <span>Anticipada</span>
                    <input type="checkbox" id="chk-2" class="tipo" name="chk-2" value="2">
                </label>
				<label class="form-checkbox">
                    <span>Gradiente aritmético</span>
                    <input type="checkbox" id="chk-3" class="tipo" name="chk-3" value="3">
                </label>
            </div>
            <div class="form-row text-center">
                <button type="button" id="btn-simular" onclick="simular()">Simular</button>
            </div>
        </form>
		<div class="form-validation hidden" id="resultado">
			<div class="form-title-row">
				<h1>Resultado de la simulación</h1>
			</div>
			<div id="contenedor">
				<table cellspacing="0">
					<thead>
						<tr>
							<th>
						  NAME</th>
						  <th>
						  NAME</th>
						  <th>
						  NAME</th>
						  <th>
						  NAME</th>
						 </tr>
					</thead>
					<tbody>                                              
						<tr>
							<td class="text-left">Caren Rials</td>
							<td>35</td>
							<td>2013-04-12</td>
							<td class="bg-blue">$445.500</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="assets/sweetalert/js/sweetalert2.all.js"></script>
    <script type="text/javascript" src="assets/funciones.js"></script>
</body>
</html>
