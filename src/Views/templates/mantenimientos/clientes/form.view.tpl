<section class="container">
    <section class="deplth-2">
        <h2>{{modeDsc}}</h2>
    </section>
    {{if hasErrores}}
        <ul class="error">
        {{foreach errores}}
            <li>{{this}}</li>
        {{endfor errores}}
        </ul>
    {{endif hasErrores}}
    <form action="index.php?page=Mantenimientos-Cliente&mode={{mode}}&codigo={{codigo}}" method="POST">
        <div>
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo" value="{{codigo}}" {{codigoReadonly}}/>
            <input type="hidden" name="vlt" value="{{token}}" />
        </div>
        <div>
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{nombre}}" {{readonly}}/>
        </div>
        <div>
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" value="{{direccion}}" {{readonly}} />
        </div>
        <div>
            <label for="telefono">Telefono</label>
            <input type="text" name="telefono" id="telefono" value="{{telefono}}" {{readonly}} />
        </div>
        <div>
            <label for="correo">Correo</label>
            <input type="text" name="correo" id="correo" value="{{correo}}" {{readonly}}/>
        </div>
        <div>
            <label for="estado">Estado</label>
            {{ifnot readonly}}
                <select name="estado" id="estado">
                    <option value="ACT" {{selectedACT}}>Activo</option>
                    <option value="INA" {{selectedINA}}>Inactivo</option>
                </select>
            {{endifnot readonly}}
            {{if readonly}}
                <input type="text" name="estado" id="estado" value="{{estado}}" {{readonly}}/>
            {{endif readonly}}
        </div>
        <div>
            <label for="evaluacion">Evaluación</label>
            <input type="text" name="evaluacion" id="evaluacion" value="{{evaluacion}}" {{readonly}}/>
        </div>
        <div>
            <button id="btnCancelar">Cancelar</button>
            {{ifnot isDisplay}}
                <button id="btnConfirmar" type="submit">Confirmar</button>
            {{endifnot isDisplay}}
        </div>
    </form>
</section>
<script>
    document.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById("btnCancelar").addEventListener("click", (e)=>{
            e.preventDefault();
            e.stopPropagation();
            window.location.assign("index.php?page=Mantenimientos-Clientes");
        })
    });
</script>