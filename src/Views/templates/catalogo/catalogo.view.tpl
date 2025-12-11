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
                        
                        <!-- FORMULARIO PARA AGREGAR AL CARRITO -->
                        <form action="index.php?page=Checkout_Cart" method="POST" class="form-carrito">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="productId" value="{{id}}">
                            <input type="hidden" name="price" value="{{precio}}">
                            <input type="number" name="quantity" value="1" min="1" max="{{disponibilidad}}" style="width: 60px; margin-bottom: 8px;">
                            <button type="submit" class="btn-carrito">
                                Agregar al Carrito
                            </button>
                        </form>
                    </div>
                </div>
            {{endfor telefonos}}
        </div>
    {{endif telefonos}}
</section>

<style>
.container-catalogo {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.catalogo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.producto-card {
    background: #fff;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s, border 0.3s;
    border: 2px solid rgb(255, 255, 255);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.producto-card:hover {
    transform: translateY(-5px);
    border-color: rgb(25, 13, 80);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.producto-imagen img {
    max-width: 100%;
    border-radius: 6px;
    height: 150px;
    object-fit: contain;
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
    height: 40px;
    overflow: hidden;
}

.producto-descripcion {
    font-size: 0.9em;
    margin-bottom: 8px;
    color: #555;
    height: 40px;
    overflow: hidden;
}

.producto-precio {
    font-size: 1.1em;
    margin-bottom: 5px;
    color: #222;
    font-weight: bold;
}

.producto-disponibilidad {
    font-size: 0.9em;
    margin-bottom: 10px;
    color: green;
    font-weight: bold;
}

.form-carrito {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.btn-carrito {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.2s;
    width: 100%;
    font-weight: bold;
}

.btn-carrito:hover {
    background-color: #0e2946;
}
/* Para el input de cantidad */
input[type="number"] {
    padding: 5px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>