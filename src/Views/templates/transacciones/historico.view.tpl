<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Transacciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem 0; margin-bottom: 2rem; border-radius: 0 0 15px 15px; }
        .stat-card { background: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .stat-icon { font-size: 2.5rem; margin-bottom: 1rem; }
        .transaction-card { background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.08); border-left: 4px solid; }
        .transaction-success { border-left-color: #28a745; }
        .transaction-warning { border-left-color: #ffc107; }
        .transaction-danger { border-left-color: #dc3545; }
        .badge { padding: 0.5em 1em; font-size: 0.85em; }
        .empty-state { text-align: center; padding: 4rem 1rem; color: #6c757d; }
        .empty-icon { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
        .product-item { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-bottom: 1px solid #eee; }
        .product-item:last-child { border-bottom: none; }
        .pagination-container { margin-top: 2rem; }
        .user-info-badge { font-size: 0.85rem; color: #6c757d; margin-left: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5"><i class="fas fa-history"></i> Histórico de Transacciones</h1>
                    <p class="lead">Revisa todas tus compras y transacciones realizadas</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="index.php?page=Catalogo_Catalogo" class="btn btn-light"><i class="fas fa-home"></i> Catálogo</a>
                    <a href="index.php?page=Checkout_Cart" class="btn btn-outline-light"><i class="fas fa-shopping-cart"></i> Carrito</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <?php if (!empty($usuarios)): ?>
        <div class="mb-4">
            <div class="card p-3 shadow-sm">
                <h5 class="mb-3"><i class="fas fa-users"></i> Seleccionar Usuario</h5>
                <form method="GET" action="">
                    <input type="hidden" name="page" value="Transacciones_Historico">
                    <div class="row align-items-end">
                        <div class="col-md-10">
                            <label class="form-label">Usuario:</label>
                            <select name="user" class="form-select" id="userSelect">
                                <?php foreach($usuarios as $usr): ?>
                                    <option value="<?php echo htmlspecialchars($usr['usuario_id']); ?>"
                                            <?php echo ($usr['usuario_id'] == $usuario_id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($usr['usuario_id']); ?>
                                        <span class="user-info-badge">
                                            (<?php echo $usr['total_compras']; ?> compras - Última: <?php echo date('d/m/Y', strtotime($usr['ultima_compra'])); ?>)
                                        </span>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Total de usuarios con transacciones: <strong><?php echo count($usuarios); ?></strong>
                            </small>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Ver
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.getElementById('userSelect').addEventListener('change', function() {
                this.form.submit();
            });
        </script>
        <?php endif; ?>

        <?php if (!empty($usuarios) && $usuario_id): ?>
        <div class="alert alert-info mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i> 
                        Viendo transacciones de: <strong><?php echo htmlspecialchars($usuario_id); ?></strong>
                    </h5>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-primary">
                        <?php echo $total_transacciones; ?> transacciones encontradas
                    </span>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-primary"><i class="fas fa-shopping-bag"></i></div>
                    <h3 class="mb-0"><?php echo $total_transacciones; ?></h3>
                    <p class="text-muted mb-0">Transacciones</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-success"><i class="fas fa-check-circle"></i></div>
                    <h3 class="mb-0"><?php echo $completadas; ?></h3>
                    <p class="text-muted mb-0">Completadas</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-warning"><i class="fas fa-clock"></i></div>
                    <h3 class="mb-0"><?php echo $pendientes; ?></h3>
                    <p class="text-muted mb-0">Pendientes</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-danger"><i class="fas fa-times-circle"></i></div>
                    <h3 class="mb-0"><?php echo $canceladas; ?></h3>
                    <p class="text-muted mb-0">Canceladas</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="stat-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">Total Gastado</h4>
                            <p class="text-muted mb-0">Suma de todas las transacciones completadas</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h2 class="mb-0 text-success">L. <?php echo $total_gastado; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3"><i class="fas fa-receipt"></i> Transacciones Recientes</h3>

        <?php if ($tiene_transacciones): ?>
            <?php foreach($transacciones as $trans): ?>
            <div class="transaction-card transaction-<?php echo $trans['estado_clase']; ?>">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-<?php echo $trans['estado_clase']; ?>">
                                    <?php echo $trans['estado_texto']; ?>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-1">Orden: <?php echo htmlspecialchars($trans['order_id']); ?></h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar"></i> <?php echo $trans['fecha_completa']; ?> |
                                    <i class="fas fa-credit-card"></i> <?php echo htmlspecialchars($trans['metodo_pago']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <h4 class="mb-0 text-success">L. <?php echo $trans['total']; ?></h4>
                        <small class="text-muted"><?php echo $trans['cantidad_productos']; ?> producto(s)</small>
                    </div>
                </div>

                <?php if (!empty($trans['detalles'])): ?>
                <div class="mt-3">
                    <p class="mb-1"><strong>Detalles:</strong> <?php echo htmlspecialchars($trans['detalles']); ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($trans['productos'])): ?>
                <div class="mt-3">
                    <h6><i class="fas fa-box"></i> Productos comprados:</h6>
                    <div class="mt-2">
                        <?php foreach($trans['productos'] as $prod): ?>
                        <div class="product-item">
                            <div>
                                <strong><?php echo htmlspecialchars($prod['nombre']); ?></strong>
                                <div class="text-muted small">Cantidad: <?php echo $prod['cantidad']; ?></div>
                            </div>
                            <div class="text-end">
                                <div>L. <?php echo number_format($prod['precio'], 2); ?> c/u</div>
                                <div class="text-success"><strong>L. <?php echo number_format($prod['subtotal'], 2); ?></strong></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <?php if ($mostrar_paginacion): ?>
            <div class="pagination-container">
                <nav aria-label="Paginación">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=Transacciones_Historico&pagina=<?php echo $pagina_actual-1; ?>&user=<?php echo $usuario_id; ?>">
                                <i class="fas fa-chevron-left"></i> Anterior
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                            <?php if ($i == $pagina_actual): ?>
                                <li class="page-item active"><span class="page-link"><?php echo $i; ?></span></li>
                            <?php else: ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=Transacciones_Historico&pagina=<?php echo $i; ?>&user=<?php echo $usuario_id; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=Transacciones_Historico&pagina=<?php echo $pagina_actual+1; ?>&user=<?php echo $usuario_id; ?>">
                                Siguiente <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
                <h2>No hay transacciones registradas</h2>
                <p class="lead">Este usuario aún no ha realizado ninguna compra en la tienda.</p>
                <p class="text-muted mb-4">Cuando realice compras, aparecerán aquí automáticamente.</p>
                <a href="index.php?page=Catalogo_Catalogo" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Ir al Catálogo
                </a>
            </div>
        <?php endif; ?>

        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                <h2>No hay usuarios con transacciones</h2>
                <p class="lead">Aún no se han registrado transacciones en el sistema.</p>
                <a href="index.php?page=Catalogo_Catalogo" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Comenzar a comprar
                </a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="mt-5 py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Historial de Transacciones</h5>
                    <p class="mb-0">Sistema de seguimiento de compras</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0"><small>© <?php echo date('Y'); ?> Tu Tienda. Todos los derechos reservados.</small></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>