<section class="container-catalogo">
    <h2 style="text-align:center; margin-bottom:20px;">Productos Disponibles</h2>

    {{if telefonos}}
        <div class="catalogo-grid">
            {{foreach telefonos}}
                <div class="producto-card">
                    <div class="producto-imagen">
                        <img src="{{imagen}}" alt="{{nombre}}" width="150">
                    </div>
                    <div class="producto-detalles">
                        <div class="producto-nombre">{{nombre}}</div>
                        <div class="producto-descripcion">{{descripcion}}</div>
                        <div class="producto-precio">Precio: L{{precio}}</div>
                        <div class="producto-disponibilidad">Disponible: {{disponibilidad}}</div>
                        <button class="btn-carrito" onclick="agregarAlCarrito({{id}}, '{{nombre}}')">
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
            {{endfor telefonos}}
        </div>
    {{endif telefonos}}
</section>

<script>
function agregarAlCarrito(id, nombre) {
    alert('✓ ' + nombre + ' agregado al carrito (ID: ' + id + ')');
}
</script>

<style>
.container-catalogo {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.catalogo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

/* Tarjeta con borde neón */
.producto-card {
    background: #fff;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s, border 0.3s;
    border: 2px solid rgb(255, 255, 255); /* borde neón */
}

.producto-card:hover {
    transform: translateY(-5px);
    border-color: rgb(25, 13, 80); /* mantiene neón al hover */
}

.producto-imagen img {
    max-width: 100%;
    border-radius: 6px;
}

.producto-detalles {
    width: 100%;
    text-align: center;
    margin-top: 10px;
}

.producto-nombre {
    font-weight: bold;
    font-size: 1.1em;
    margin-bottom: 5px;
    color: #222;
}

.producto-descripcion {
    font-size: 0.9em;
    margin-bottom: 8px;
    color: #555;
}

.producto-precio {
    font-size: 1em;
    margin-bottom: 5px;
    color: #222;
}

.producto-disponibilidad {
    font-size: 0.9em;
    margin-bottom: 10px;
    color: green;
    font-weight: bold;
}

.btn-carrito {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-carrito:hover {
    background-color: #0e2946;
}
</style>
