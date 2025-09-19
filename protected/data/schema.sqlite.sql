CREATE TABLE products (
    id  INTEGER NOT NULL PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    description VARCHAR(128) DEFAULT '',
    price REAL NOT NULL,
    stock INTEGER NOT NULL
);

INSERT INTO products (id, name, description, price, stock)
VALUES ('1','test','testando', 10.80, 1);