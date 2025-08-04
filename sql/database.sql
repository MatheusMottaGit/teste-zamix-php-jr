create database xtock_db;

use xtock_db;

create table users (
  id int primary key auto_increment,
  name varchar(255),
  email varchar(255) not null unique,
  password varchar(255) not null
);

create table requests (
  id int primary key auto_increment,
  user_id int not null,
  request_date date not null,
  description varchar(255),
  foreign key (user_id) references users(id)
);

create table products (
  id int primary key auto_increment,
  name varchar(100),
  sale_price float not null,
  cost_price float,
  type enum ('simple', 'compound') not null
);

create table stocks (
  id int primary key auto_increment,
  product_id int not null,
  product_quantity int,
  foreign key (product_id) references products (id)
);

create table request_items (
  id int primary key auto_increment,
  request_id int not null,
  product_id int not null,
  items_quantity int not null,
  foreign key (request_id) references requests (id),
  foreign key (product_id) references products (id)
);

create table product_composes (
  id int primary key auto_increment,
  simple_product_id int not null,
  compound_product_id int not null,
  simple_product_quantity int,

  foreign key (simple_product_id) references products (id),
  foreign key (compound_product_id) references products (id)
);

create table stock_movements (
  id int primary key auto_increment,
  product_id int not null,
  request_id int not null,
  type enum ('in', 'out'),
  quantity int,
  movement_date date not null,
  cost_price float not null,

  foreign key (product_id) references products(id),
  foreign key (request_id) references requests(id)
);