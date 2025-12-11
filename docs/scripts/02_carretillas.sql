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

INSERT INTO products (productName, productDescription, productPrice, productImgUrl, productStock, productStatus)
VALUES
('iPhone 15', 'Último modelo de Apple', 999.00, 'public/imgs/hero/I1.png', 10, 'ACT'),
('Samsung Galaxy S23', 'Alta gama de Samsung', 899.00, 'public/imgs/hero/S1.png', 15, 'ACT'),
('Xiaomi Mi 13', 'Potente y económico', 699.00, 'public/imgs/hero/X1.png', 20, 'ACT'),
('Google Pixel 8', 'Experiencia Android pura', 799.00, 'public/imgs/hero/G1.jpg', 8, 'ACT'),
('OnePlus 12', 'Rendimiento extremo', 749.00, 'public/imgs/hero/O1.png', 12, 'ACT'),
('Motorola Edge 40', 'Equilibrio perfecto', 650.00, 'public/imgs/hero/M1.png', 7, 'ACT'),
('Huawei P60', 'Fotografía profesional', 850.00, 'public/imgs/hero/HW1.png', 9, 'ACT'),
('Sony Xperia 1 V', 'Pantalla 4K HDR', 999.00, 'public/imgs/hero/SX1.png', 5, 'ACT'),
('Nokia X30', 'Resistente y económico', 499.00, 'public/imgs/hero/N1.png', 11, 'ACT'),
('Asus Zenfone 10', 'Compacto y potente', 720.00, 'public/imgs/hero/AZ1.jpg', 6, 'ACT');
