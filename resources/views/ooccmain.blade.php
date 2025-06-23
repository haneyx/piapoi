<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="IngHarold">
    <title>ORGANO DESCONCENTRADO</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>

.popup {
    display: none; /* El popup está oculto por defecto */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

.popup-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    width: 300px;
    text-align: center;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}


    </style>
<body>
<div class="todo">
    <div class="container largo">
        <form action="{{ route('ooddhoja') }}" method="POST">
            <div id="tapa">
                <div id="retorna">
                    <button class="verbutton" type="submit">Cambiar EESS</button>
                </div>
                <div id="titulo" style="margin-top: -10px;">{{ session('redx') }} <BR> {{ session('codesx') }}::{{ session('esx') }}</div>
                <div id="ver">
                    <button class="verbutton" type="submit">Ver consolidado EESS</button>
                </div>
            </div>
            <div class="largo">
                <label class="medio1">Seleccione su actividad:</label>
                <label class="medio2">Prioridad:</label>
                <label class="medio3">Acciones</label>
            </div>
            <div class="largo1">
                <div class="cuarto">
                    <select class="sel1" name="" id="" required>
                        <option value="1" selected>I nivel</option>
                    </select>
                    <select class="sel2" name="" id="" required>
                        <option value="1" selected>1</option>
                    </select>
                    <button class="but1" type="submit">Editar</button>
                    <button class="but2" type="submit">Cerrar</button>
                </div>
                <div class="cuarto">
                    <select class="sel1" name="" id="" required>
                        <option value="1" selected>II y III nivel</option>
                    </select>
                    <select class="sel2" name="" id="" required>
                        <option value="1" selected>1</option>
                    </select>
                    <button class="but1" type="submit">Editar</button>
                    <button class="but2" type="submit">Cerrar</button>
                </div>
            </div>

            <div class="largo1">
                <label class="medio1">Defina sus otras actividades:</label>
                <label class="medio2">Prioridad:</label>
                <label class="medio3">Acciones</label>
            </div>
            <div class="largo2">
                <div class="cuarto" id="servicesContainer">
                    <select class="sel1" name="actix" id="actix" required>
                        <option value="0">Seleccione</option>
                        <option value="1" style="font-style:italic">Agregar o quitar</option>
                    </select>
                </div>
            </div>
        </form>
    </div>


<!-- Popup para agregar/quitar servicios -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">X</span>
        <h3>Elija los servicios</h3>
        <ul>
            <li><input type="checkbox" id="service1"> Servicio 1</li>
            <li><input type="checkbox" id="service2"> Servicio 2</li>
            <li><input type="checkbox" id="service3"> Servicio 3</li>
            <li><input type="checkbox" id="service4"> Servicio 4</li>
            <li><input type="checkbox" id="service5"> Servicio 5</li>
            <li><input type="checkbox" id="service6"> Servicio 6</li>
            <li><input type="checkbox" id="service7"> Servicio 7</li>
            <li><input type="checkbox" id="service8"> Servicio 8</li>
            <li><input type="checkbox" id="service9"> Servicio 9</li>
            <li><input type="checkbox" id="service10"> Servicio 10</li>
        </ul>
        <button onclick="addServices()">Agregar Servicios</button>
    </div>
</div>    


</div>

<script>
// Función para abrir el popup cuando se selecciona "Agregar o quitar servicios"
document.getElementById("actix").addEventListener("change", function() {
    if (this.value == "1") {
        document.getElementById("popup").style.display = "flex"; // Mostrar popup
    }
});

// Función para cerrar el popup
function closePopup() {
    document.getElementById("popup").style.display = "none"; // Ocultar popup
}

// Función para agregar los servicios seleccionados en el popup
function addServices() {
    var selectedServices = [];
    for (var i = 1; i <= 10; i++) {
        var checkbox = document.getElementById("service" + i);
        if (checkbox.checked) {
            selectedServices.push("Servicio " + i);
        }
    }

    // Crear un bloque "cuarto" dentro de largo2 para cada servicio seleccionado
    var servicesContainer = document.getElementById("servicesContainer");

    selectedServices.forEach(function(service) {
        var newCuarto = document.createElement("div");
        newCuarto.classList.add("cuarto");
        
        newCuarto.innerHTML = `
            <select class="sel1" name="actix" id="actix" required>
                <option value="0" disabled>Seleccione</option>
                <option value="1">${service} :: Servicio</option>
            </select>
            <select class="sel2" name="" id="" required>
                <option value="0" disabled selected>Seleccione</option>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
            <button class="but1" type="submit">Editar</button>
            <button class="but2" type="submit">Cerrar</button>
        `;

        // Agregar el nuevo bloque "cuarto" al contenedor
        servicesContainer.appendChild(newCuarto);
    });

    // Cerrar el popup
    closePopup();
}



    </script>
</body>
</html>
