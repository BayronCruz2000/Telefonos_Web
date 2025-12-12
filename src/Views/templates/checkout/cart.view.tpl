<style>
    /* --- Estilos Generales --- */
    .cart-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #354f52;
        padding: 40px 20px;
        color: #0070ba;
    }

    .cart-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
 /* 2 partes productos, 1 parte resumen */
        gap: 30px;
    }

    h1 { margin-bottom: 20px; font-size: 1.8rem; color: #cad2c5; }

    /* --- Lista de Productos --- */
    .cart-items {
        background: #2f3e46;
        border-radius: 8px;
        box-shadow: 0 2px 10px #cad2c5;
        padding: 20px;
    }

    .cart-header {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr;
        padding-bottom: 15px;
        border-bottom: 2px solid #cad2c5;
        font-weight: bold;
        color: #cad2c5;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #cad2c5;
    }

    .cart-item:last-child { border-bottom: none; }

    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .product-info img {
        width: 70px;
        height: 70px;
        object-fit: contain; /* Para que la foto no se deforme */
        border-radius: 6px;
        border: 1px solid #cad2c5;
    }

    .product-details h3 {
        margin: 0 0 5px 0;
        font-size: 1rem;
        color: #cad2c5;
    }

    .product-details small { color: #cad2c5; }

    .price, .total { font-weight: 600; font-size: 1rem; }
    .total { color: #cad2c5; }

    .btn-delete {
        background: none;
        border: none;
        color: #0070ba;
        cursor: pointer;
        font-weight: bold;
        font-size: 0.9rem;
        transition: color 0.2s;
        text-decoration: underline;
    }
    .btn-delete:hover { color: #0070ba; }

    /* --- Resumen de Orden (Derecha) --- */
    .order-summary {
        background: #cad2c5;
        border-radius: 8px;
        box-shadow: 0 2px 10px #2f3e46;
        padding: 25px;
        height: fit-content;
        position: sticky;
        top: 20px; /* Se queda fijo al bajar */
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .summary-total {
        border-top: 2px solid #cad2c5;
        padding-top: 15px;
        margin-top: 15px;
        font-size: 1.3rem;
        font-weight: 800;
        color: #0070ba;
        display: flex;
        justify-content: space-between;
    }

    .btn-checkout {
        display: block;
        width: 100%;
        background-color: #0070ba; /* Azul PayPal */
        color: #cad2c5;
        text-align: center;
        padding: 15px;
        border-radius: 50px; /* Redondo estilo moderno */
        text-decoration: none;
        font-weight: bold;
        font-size: 1.1rem;
        margin-top: 20px;
        transition: background 0.3s, transform 0.1s;
        border: none;
    }

    .btn-checkout:hover { background-color: #005ea6; transform: translateY(-1px); }

    .empty-cart {
        text-align: center;
        padding: 40px;
        color: #7f8c8d;
    }

    /* --- Mobile --- */
    @media (max-width: 768px) {
        .cart-container { grid-template-columns: 1fr; }
        .cart-header { display: none; } /* Ocultamos cabecera en móvil */
        .cart-item {
            grid-template-columns: 1fr;
            gap: 15px;
            text-align: center;
        }
        .product-info { flex-direction: column; }
    }
</style>

<div class="cart-wrapper">
    <div class="cart-container">
        
        <section class="cart-items">
            <h1>&#9734; Carrito &#9734;</h1>

            {{if hasItems}}
                <div class="cart-header">
                    <div>Producto</div>
                    <div style="text-align:center;">Cantidad</div>
                    <div style="text-align:right;">Precio</div>
                    <div style="text-align:right;">Total</div>
                </div>

                {{foreach items}}
                <div class="cart-item">
                    <div class="product-info">
                        <img src="{{productImgUrl}}" alt="{{productName}}">
                        <div class="product-details">
                            <h3>{{productName}}</h3>
                            <form action="index.php?page=Checkout_Cart" method="post">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="productId" value="{{productid}}">
                                <button type="submit" class="btn-delete">Eliminar lote</button>
                            </form>
                        </div>
                    </div>

                    <div style="text-align:center;">
                        <span style="background:#cad2c5; padding: 5px 12px; border-radius:15px;">
                            x {{crrctd}}
                        </span>
                    </div>

                    <div class="price" style="text-align:right;">
                        L. {{crrprc}}
                    </div>

                    <div class="total" style="text-align:right;">
                        L. {{total}}
                    </div>
                </div>
                {{endfor items}}

                <form action="index.php?page=Checkout_Pago" method="post">
                <button type="submit" class="btn-checkout">
                    Pagar
                </button>
                </form>


            {{endif hasItems}}

            {{ifnot hasItems}}
                <div class="empty-cart">
                    <h2>Tu carrito está vacío 😔</h2>
                    <p>¡Corre a ver nuestros teléfonos!</p>
                    <a href="http://localhost/TELEFONOS_WEB/index.php?page=Sec_Login" style="color: #cad2c5; text-decoration: underline;">Salir</a>
                </div>
            {{endifnot hasItems}}
        </section>
</div>

