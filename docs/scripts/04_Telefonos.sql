CREATE TABLE
    `products` (
        `productId` int(11) NOT NULL AUTO_INCREMENT,
        `productName` varchar(255) NOT NULL,
        `productDescription` text NOT NULL,
        `productPrice` decimal(10, 2) NOT NULL,
        `productImgUrl` varchar(255) NOT NULL,
        `productStock` int(11) NOT NULL DEFAULT 0,
        `productStatus` char(3) NOT NULL,
        PRIMARY KEY (`productId`)
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8mb4;

CREATE TABLE
    `carretilla` (
        `usercod` BIGINT(10) NOT NULL,
        `productId` int(11) NOT NULL,
        `crrctd` INT(5) NOT NULL,
        `crrprc` DECIMAL(12, 2) NOT NULL,
        `crrfching` DATETIME NOT NULL,
        PRIMARY KEY (`usercod`, `productId`),
        INDEX `productId_idx` (`productId` ASC),
        CONSTRAINT `carretilla_user_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `carretilla_prd_key` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );

CREATE TABLE
    `carretillaanon` (
        `anoncod` varchar(128) NOT NULL,
        `productId` bigint(18) NOT NULL,
        `crrctd` int(5) NOT NULL,
        `crrprc` decimal(12, 2) NOT NULL,
        `crrfching` datetime NOT NULL,
        PRIMARY KEY (`anoncod`, `productId`),
        KEY `productId_idx` (`productId`),
        CONSTRAINT `carretillaanon_prd_key` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );

    CREATE TABLE carretilla_anon (
  usercod VARCHAR(128) NOT NULL,
  productid INT(11) NOT NULL,
  crrctd INT(11) NOT NULL DEFAULT 1,
  crrprc DECIMAL(10,2) NOT NULL,
  crrfching DATETIME NOT NULL,
  PRIMARY KEY (usercod, productid),

  KEY idx_carretilla_anon_user (usercod),
  KEY idx_carretilla_anon_prod (productid),

  CONSTRAINT fk_carretilla_products 
    FOREIGN KEY (productid) 
    REFERENCES products(productId)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE products
ADD COLUMN productImgUrl VARCHAR(255) DEFAULT NULL;

UPDATE products
SET productImgUrl = productImage;

INSERT INTO products (productName, productDescription, productPrice, productImgUrl, productStock, productStatus)
VALUES
('iPhone 15', 'Último modelo de Apple', 999.00, 'public/imgs/hero/I1.png', 10, 'ACT'),
('Samsung Galaxy S23', 'Alta gama de Samsung', 899.00, 'public/imgs/hero/S1.png', 15, 'ACT'),
('Xiaomi Mi 13', 'Potente y económico', 699.00, 'public/imgs/hero/X1.png', 20, 'ACT'),
('Google Pixel 8', 'Experiencia Android pura', 799.00, 'public/imgs/hero/G1.jpg', 8, 'ACT'),
('OnePlus 12', 'Rendimiento extremo', 749.00, 'public/imgs/hero/O1.png', 12, 'ACT'),
('Motorola Edge 40', 'Equilibrio perfecto', 650.00, 'public/imgs/hero/M1.png', 7, 'ACT'),
('Sony Xperia 1 V', 'Pantalla 4K HDR', 999.00, 'public/imgs/hero/SX1.png', 5, 'ACT'),
('Huawei P60', 'Fotografía profesional', 850.00, 'public/imgs/hero/HW1.png', 9, 'ACT'),
('Nokia X30', 'Resistente y económico', 499.00, 'public/imgs/hero/N1.png', 11, 'ACT'),
('Asus Zenfone 10', 'Compacto y potente', 720.00, 'public/imgs/hero/AZ1.jpg', 6, 'ACT');

CREATE TABLE IF NOT EXISTS transacciones (
    transaccion_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id VARCHAR(100) NOT NULL, 
    order_id VARCHAR(50) UNIQUE NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'completada', 'cancelada') DEFAULT 'pendiente',
    metodo_pago VARCHAR(50) DEFAULT 'paypal',
    detalles TEXT,
    productos_json TEXT NOT NULL,
    INDEX idx_usuario (usuario_id),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP Table transacciones


INSERT INTO transacciones (usuario_id, order_id, total, estado, metodo_pago, detalles, productos_json) VALUES
('user_1', 'ORDER-001', 1200.00, 'completada', 'paypal', 'Compra de 1 iPhone 15', '[{"nombre":"iPhone 15","cantidad":1,"precio":1200.00,"subtotal":1200.00}]'),
('user_2', 'ORDER-002', 2000.00, 'completada', 'paypal', 'Compra de 2 Samsung Galaxy S24', '[{"nombre":"Samsung Galaxy S24","cantidad":2,"precio":1000.00,"subtotal":2000.00}]'),
('user_3', 'ORDER-003', 800.00, 'completada', 'paypal', 'Compra de 1 Pixel 9', '[{"nombre":"Pixel 9","cantidad":1,"precio":800.00,"subtotal":800.00}]');