<style>
    /* --- Estilos Generales --- */
    .cart-wrapper {
        align-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #354f52;
        padding: 40px 20px;
        color: #0070ba;
    }

    .cart-container {
        align-items: center;
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
<style>
    /* Estilos para los botones de acción */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        display: inline-block;
        background-color: #2f3e46;
        color: #cad2c5;
        text-align: center;
        padding: 12px 25px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        font-size: 1rem;
        border: 2px solid #0070ba;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 200px;
        max-width: 300px;
    }
    
    .btn-action:hover {
        background-color: #0070ba;
        color: #cad2c5;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 112, 186, 0.3);
    }
    
    .btn-danger {
        border-color: #cad2c5;
        background-color: #2f3e46;
    }
    
    .btn-danger:hover {
        background-color: #0070ba;
        border-color: #005ea6;
    }
    
    .btn-back {
        border-color: #cad2c5;
        background-color: #2f3e46;
    }
    
    .btn-back:hover {
        background-color: #0070ba;
        border-color: #005ea6;
    }
</style>

<div class="cart-wrapper">
    <div class="cart-container">
        <h1>&#8584; PAGO &#8584;</h1>
<div class="action-buttons" style="display: grid; grid-template-columns: 1fr 1fr;">
    <a href="http://localhost/NW202503MVC-MAIN/index.php?page=Sec_Login" 
       class="btn-action btn-danger" style="color: #cad2c5; justify-self: start;">
       Salir del Sitio
    </a>
    <a href="http://localhost/NW202503MVC-MAIN/index.php?page=Checkout_Cart" 
       class="btn-action btn-back" style="color: #cad2c5; justify-self: end;">
       Volver al Carrito
    </a>
</div>

        {{if hasItems}}
        <aside class="order-summary">
            <h2>Resumen</h2>
            
            <div class="summary-row">
                <span>Subtotal</span>
                <span>L. {{cartTotal}}</span>
            </div>
            <div class="summary-row">
                <span>Envío</span>
                <span style="color: #0070ba;">Gratis</span>
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

            <div style="margin-top: 15px; text-align: center; font-size: 0.8rem; color: #2f3e46;">
                🔒 Pago 100% Seguro con SSL
            </div>
        </aside>
        {{endif hasItems}}
    </div>
</div>


