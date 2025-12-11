<style>
    /* --- Estilos Generales --- */
    .cart-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        padding: 40px 20px;
        color: #333;
    }

    .cart-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr; /* 2 partes productos, 1 parte resumen */
        gap: 30px;
    }

    h1 { margin-bottom: 20px; font-size: 1.8rem; color: #2c3e50; }

    /* --- Lista de Productos --- */
    .cart-items {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 20px;
    }

    .cart-header {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
        font-weight: bold;
        color: #7f8c8d;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #eee;
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
        border: 1px solid #eee;
    }

    .product-details h3 {
        margin: 0 0 5px 0;
        font-size: 1rem;
        color: #2c3e50;
    }

    .product-details small { color: #95a5a6; }

    .price, .total { font-weight: 600; font-size: 1rem; }
    .total { color: #27ae60; }

    .btn-delete {
        background: none;
        border: none;
        color: #e74c3c;
        cursor: pointer;
        font-weight: bold;
        font-size: 0.9rem;
        transition: color 0.2s;
        text-decoration: underline;
    }
    .btn-delete:hover { color: #c0392b; }

    /* --- Resumen de Orden (Derecha) --- */
    .order-summary {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        border-top: 2px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
        font-size: 1.3rem;
        font-weight: 800;
        color: #2c3e50;
        display: flex;
        justify-content: space-between;
    }

    .btn-checkout {
        display: block;
        width: 100%;
        background-color: #0070ba; /* Azul PayPal */
        color: white;
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
            <h1>🛒 Tu Carrito</h1>

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
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </div>
                    </div>

                    <div style="text-align:center;">
                        <span style="background:#eee; padding: 5px 12px; border-radius:15px;">
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

            {{endif hasItems}}

            {{ifnot hasItems}}
                <div class="empty-cart">
                    <h2>Tu carrito está vacío 😔</h2>
                    <p>¡Corre a ver nuestros teléfonos!</p>
                    <a href="index.php" style="color: #0070ba; text-decoration: underline;">Ir al inicio</a>
                </div>
            {{endifnot hasItems}}
        </section>

        {{if hasItems}}
        <aside class="order-summary">
            <h2>Resumen</h2>
            
            <div class="summary-row">
                <span>Subtotal</span>
                <span>L. {{cartTotal}}</span>
            </div>
            <div class="summary-row">
                <span>Envío</span>
                <span style="color: green;">Gratis</span>
            </div>
            <div class="summary-row">
                <span>Impuestos</span>
                <span>Incluidos</span>
            </div>

            <div class="summary-total">
                <span>Total a Pagar</span>
                <span>L. {{cartTotal}}</span>
            </div>

            <form action="index.php?page=Checkout_Checkout" method="post">
                <button type="submit" class="btn-checkout">
                    Pagar con PayPal
                </button>
            </form>

            <div style="margin-top: 15px; text-align: center; font-size: 0.8rem; color: #aaa;">
                🔒 Pago 100% Seguro con SSL
            </div>
        </aside>
        {{endif hasItems}}
    </div>
</div>

<div style="text-align:center; margin-top: 20px; opacity: 0.7;">
    <p>🧪 Zona de Pruebas (Solo desarrollo)</p>
    <form action="index.php?page=Checkout_Cart" method="post" style="display:inline;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="productId" value="1">
        <input type="hidden" name="price" value="1200.00">
        <button>+ iPhone 15</button>
    </form>
    <form action="index.php?page=Checkout_Cart" method="post" style="display:inline;">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="productId" value="2">
        <input type="hidden" name="price" value="1100.00">
        <button>+ Samsung S24</button>
    </form>
</div>