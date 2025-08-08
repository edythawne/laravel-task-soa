

## Tareas con Laravel


### Base de datos en PostgreSQL

Para usar esta aplicacion es necesario crear en Postgres una base de datos con este usuario y contraseña

```
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=task_db
DB_USERNAME=task_user
DB_PASSWORD=qwertyui


-- Creación de base de datos
CREATE DATABASE task_db;

-- Creacion de un usuario
CREATE USER task_user WITH PASSWORD 'qwertyui';

-- Permisos del usuario task_user sobre la base de datos task_db
GRANT ALL PRIVILEGES ON DATABASE task_db TO task_user;

```

### Montar base de datos

```

ALTER DATABASE task_db SET timezone TO 'America/Mexico_City';
SET TIME ZONE 'America/Mexico_City';
SELECT now();

CREATE SCHEMA IF NOT EXISTS public;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "unaccent";

/**
DROP SCHEMA IF EXISTS catalog CASCADE;
DROP SCHEMA IF EXISTS app CASCADE;
DROP SCHEMA IF EXISTS authentication CASCADE;
**/

-- ------------------------------------------------------------------------------------
-- Schema: Authentication
-- Description : Esquema relacionado a la autenticación del usuario
-- ------------------------------------------------------------------------------------

CREATE SCHEMA IF NOT EXISTS authentication;


CREATE TABLE IF NOT EXISTS authentication.user_has_roles (
    user_id BIGINT NOT NULL,
    role_id INT NOT NULL,
    type VARCHAR(300) NOT NULL,
    UNIQUE(role_id, user_id)
);


CREATE TABLE IF NOT EXISTS authentication.permissions (
    id BIGSERIAL NOT NULL,
    name VARCHAR(300) NOT NULL,
    guard_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    UNIQUE(name)
);


CREATE TABLE IF NOT EXISTS authentication.roles (
    id SERIAL NOT NULL,
    name VARCHAR(255) NOT NULL,
    name_regex VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);


-- DROP TABLE authentication.role_has_permissions;
CREATE TABLE IF NOT EXISTS  authentication.role_has_permissions (
    role_id INT NOT NULL,
    permission_id BIGINT NOT NULL,
    PRIMARY KEY(permission_id, role_id)
);



CREATE TABLE IF NOT EXISTS authentication.session(
    id VARCHAR(255) not null primary key,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL ,
    last_activity INTEGER NOT NULL
);

CREATE INDEX sessions_user_id_index ON authentication.session(user_id);
CREATE INDEX sessions_last_activity_index ON authentication.session(last_activity);


CREATE TABLE IF NOT EXISTS personal_access_tokens (
    id bigserial NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigserial NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    primary key (id)
);


CREATE TABLE IF NOT EXISTS authentication.user(
    id BIGSERIAL NOT NULL,
    uuid UUID DEFAULT uuid_generate_v4(),
    email VARCHAR(250) NOT NULL,
    name VARCHAR(300) NOT NULL,
    password TEXT NOT NULL,
    first_surname VARCHAR(300) NULL,
    second_surname VARCHAR(300) NULL,
    phone VARCHAR(18) NULL,
    active BOOLEAN DEFAULT TRUE NOT NULL,
    created_by VARCHAR(150) NOT NULL DEFAULT 'sys',
    updated_by VARCHAR(150) NOT NULL DEFAULT 'sys',
    deleted_by VARCHAR(150) NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITHOUT TIME ZONE NULL,
    PRIMARY KEY(id),
    UNIQUE(email),
    UNIQUE(uuid)
);


-- ------------------------------------------------------------------------------------
-- Schema: Catalogo
-- Description : S/D
-- ------------------------------------------------------------------------------------

CREATE SCHEMA IF NOT EXISTS catalog;


CREATE TABLE IF NOT EXISTS catalog.status(
    id SERIAL NOT NULL,
    name VARCHAR(300) NOT NULL,
    active BOOLEAN DEFAULT TRUE NOT NULL,
    created_by VARCHAR(150) NOT NULL DEFAULT 'sys',
    updated_by VARCHAR(150) NULL,
    deleted_by VARCHAR(150) NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITHOUT TIME ZONE NULL,
    PRIMARY KEY(id)
);



-- ------------------------------------------------------------------------------------
-- Schema: App
-- Description : S/D
-- ------------------------------------------------------------------------------------

CREATE SCHEMA IF NOT EXISTS app;


CREATE TABLE IF NOT EXISTS app.task(
    id SERIAL NOT NULL,
    status_id INT NOT NULL,
    name_regex VARCHAR(200) NOT NULL UNIQUE,
    name VARCHAR(250) NOT NULL,
    description TEXT NULL,
    active BOOLEAN DEFAULT TRUE NOT NULL,
    created_by VARCHAR(150) NOT NULL DEFAULT 'sys',
    updated_by VARCHAR(150) NULL,
    deleted_by VARCHAR(150) NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITHOUT TIME ZONE NULL,
    PRIMARY KEY(id)
);


-- ------------------------------------------------------------------------------------
-- Schema: Creación de foraneas
-- Description : S/D
-- ------------------------------------------------------------------------------------


ALTER TABLE authentication.roles  ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);
ALTER TABLE authentication.role_has_permissions ADD FOREIGN KEY (permission_id) REFERENCES authentication.permissions(id) ON DELETE NO ACTION;
ALTER TABLE authentication.role_has_permissions ADD FOREIGN KEY (role_id) REFERENCES authentication.roles(id) ON DELETE NO ACTION;
ALTER TABLE authentication.user_has_roles ADD FOREIGN KEY (role_id) REFERENCES authentication.roles(id) ON DELETE NO ACTION;
ALTER TABLE authentication.user_has_roles ADD FOREIGN KEY (user_id) REFERENCES authentication.user(id) ON DELETE NO ACTION;

ALTER TABLE authentication.session ADD FOREIGN KEY (user_id) REFERENCES authentication.user(id) ON DELETE NO ACTION;
-- ALTER TABLE personal_access_tokens ADD FOREIGN KEY (user_id) REFERENCES authentication.user(id) ON DELETE NO ACTION;

ALTER TABLE app.task ADD FOREIGN KEY (status_id) REFERENCES catalog.status(id) ON DELETE NO ACTION;

-- --------------------------------------------------------



insert into authentication.user (id, uuid, email, name, first_surname, second_surname, phone, active, password)
values  (1, '24d2add1-737a-4f0c-a774-70c29d6e34f4', 'dummy_u1@testing.com', 'David', 'De Paz ', 'Ramirez', '113 - 344 - 5566', true, '7f06341de5e8d16322430c03b9c31c45d1c9ff6f949f8f10a970dffc6061fa9a'),
        (2, '8f79ae15-a0ff-489a-a3fe-92b785c466c3', 'dummy_u2@testing.com', 'Sebastian Federico', 'Montero', 'Monroy', '113 - 344 - 5566', true, '7f06341de5e8d16322430c03b9c31c45d1c9ff6f949f8f10a970dffc6061fa9a'),
        (3, '887ced03-6d15-4855-b0ea-aae8744baa50', 'dummy_u3@testing.com', 'Eduardo', 'Ramires', 'Perez', '113 - 344 - 5566', true, '7f06341de5e8d16322430c03b9c31c45d1c9ff6f949f8f10a970dffc6061fa9a'),
        (4, 'b099859b-4e4e-486d-8115-8365a76c04dd', 'dummy_u4@testing.com', 'Ricardo Adrian', 'Cuesta', 'Garcia', '113 - 344 - 5566', true, '7f06341de5e8d16322430c03b9c31c45d1c9ff6f949f8f10a970dffc6061fa9a'),
        (5, 'fbd3247f-18f1-4800-a3d0-8fe887027590', 'dummy_u5@testing.com', 'Francisco', 'Franco', 'Gómez', '113 - 344 - 5566', true, '7f06341de5e8d16322430c03b9c31c45d1c9ff6f949f8f10a970dffc6061fa9a');


INSERT INTO authentication.roles(id, name, name_regex, guard_name)
VALUES (1, 'Administrador', 'administrador', 'web'),
       (2, 'Operador', 'operador', 'web');


insert into authentication.user_has_roles (user_id, role_id, type)
values  (1, 1, 'App'),
        (2, 2, 'App'),
        (3, 2, 'App'),
        (4, 1, 'App'),
        (5, 2, 'App');


insert into catalog.status (id, name, active)
values  (1, 'Pendiente', true),
        (2, 'En progreso', true),
        (3, 'Completada', true);


insert into app.task (id, status_id, name_regex, name, description, active)
values  (1, 1, 'crear_proyecto', 'Crear proyecto', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', true),
        (2, 2, 'crear_base_de_datos', 'Crear base de datos', 'Fusce ac magna id nisi dignissim consectetur in non orci.', true),
        (3, 3, 'crear_datos_genericos', 'Crear datos genericos', 'Nam ullamcorper massa malesuada erat pharetra, ac condimentum elit varius.', true),
        (4, 1, 'configuracion_de_env', 'Configuracion de .env', 'Praesent hendrerit elit quis condimentum laoreet.', true);


SELECT setval('authentication.user_id_seq', (SELECT MAX(id) FROM authentication.user) + 1);
SELECT setval('authentication.roles_id_seq', (SELECT MAX(id) FROM authentication.roles) + 1);
SELECT setval('catalog.status_id_seq', (SELECT MAX(id) FROM catalog.status) + 1);
SELECT setval('app.task_id_seq', (SELECT MAX(id) FROM app.task) + 1);


-- Permisos a los esquemas al usuario vinculado a la base
GRANT USAGE ON SCHEMA authentication TO task_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA authentication TO task_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA authentication GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO task_user;

GRANT USAGE ON SCHEMA public TO task_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA public TO task_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO task_user;

GRANT USAGE ON SCHEMA catalog TO task_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA catalog TO task_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA catalog GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO task_user;

GRANT USAGE ON SCHEMA app TO task_user;
GRANT SELECT, INSERT, UPDATE, DELETE ON ALL TABLES IN SCHEMA app TO task_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA app GRANT SELECT, INSERT, UPDATE, DELETE ON TABLES TO task_user;

GRANT USAGE, SELECT, UPDATE ON SEQUENCE personal_access_tokens_id_seq TO task_user;
GRANT USAGE, SELECT, UPDATE ON SEQUENCE authentication.user_id_seq TO task_user;
GRANT USAGE, SELECT, UPDATE ON SEQUENCE authentication.roles_id_seq TO task_user;
GRANT USAGE, SELECT, UPDATE ON SEQUENCE catalog.status_id_seq TO task_user;
GRANT USAGE, SELECT, UPDATE ON SEQUENCE app.task_id_seq TO task_user;



```


### Ejecutar proyecto de laravel

Se require tener instalado PHP 8.2 o superior así como las extensiones de PHP para postgres sin olvidar composer.

```
composer update
php artisan serve
```


### Api

El siguiente proyecto expone los siguientes endpoint


-- Creacion de un usuario


```
curl --location 'http://127.0.0.1:8000/api/app/auth/register' \
--header 'Content-Type: application/json' \
--data-raw '{
"name" : "Test Laravel",
"first_surname" : "Larevel",
"second_surname" : "Php",
"phone" : null,
"email" : "laravel@test.com",
"password" : "im]%k}U66^7~"
}'
```


-- Login con generacion de token no JTW


```
curl --location 'http://127.0.0.1:8000/api/app/auth/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email" :"dummy_u1@testing.com",
    "password" : "im]%k}U66^7~"
}'
```


-- Creacion de una tarea (Requiere token de tipo bearer)

```
curl --location 'http://127.0.0.1:8000/api/app/task/store' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer 6|KtG2CRYEzXnpzdeVQDhd8Ibd5Ijcu0V7l9ZtaAX49f38e976' \
--data '{
    "status_key": 2,
    "name": "Generacion de endpoint",
    "description": "Pruebas con postman en laravel"
}'
```


-- Modificacion de una tarea (Requiere token de tipo bearer)

```
curl --location --request PUT 'http://127.0.0.1:8000/api/app/task/update' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer 6|KtG2CRYEzXnpzdeVQDhd8Ibd5Ijcu0V7l9ZtaAX49f38e976' \
--data '{
    "status_key": 3,
    "name": "Generacion de endpoint",
    "description": "Pruebas con postman en laravel"
}'
```

-- Eliminacion logica de una tarea (require id a eliminar)


```
curl --location --request DELETE 'http://127.0.0.1:8000/api/app/task/delete/7' \
--header 'Authorization: Bearer 6|KtG2CRYEzXnpzdeVQDhd8Ibd5Ijcu0V7l9ZtaAX49f38e976'

```


-- Obtener todas las tareas con filtro

```

curl --location 'http://127.0.0.1:8000/api/app/task/index?search=base' \
--header 'Authorization: Bearer 6|KtG2CRYEzXnpzdeVQDhd8Ibd5Ijcu0V7l9ZtaAX49f38e976'

```



-- Obtener todas las tareas sin filtro

```

curl --location 'http://127.0.0.1:8000/api/app/task/index' \
--header 'Authorization: Bearer 6|KtG2CRYEzXnpzdeVQDhd8Ibd5Ijcu0V7l9ZtaAX49f38e976'

```


-- IMPORTANTE
Se debe agregar en los endpoint que se requiere el token de autorizacion
