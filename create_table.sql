use fileshare;

create table
    if not exists categories (
        id int primary key AUTO_INCREMENT,
        name varchar(256) not null unique,
        description text not null,
        links int default 0 not null,
        total_downloads bigint default 0 not null
    );

create table
    if not exists users (
        id int primary key AUTO_INCREMENT,
        username varchar(256) not null unique,
        email varchar(256) not null unique,
        password varchar(256) not null,
        salt varbinary(256) not null,
        confirmed boolean default 0,
        banned boolean default 0
    );

create table
    if not exists files (
        id int primary key AUTO_INCREMENT,
        name varchar(256) not null unique,
        description text not null,
        category_id int not null,
        downloads int default 0 not null,
        votes int default 0 not null,
        date timestamp not null,
        rating int not null default 0,
        link varchar(2048),
        user_id int not null,
        constraint `files_user_foreign_key` foreign key (user_id) references users (id) on delete cascade on update restrict,
        constraint `files_category_foreign_key` foreign key (category_id) references categories (id) on delete cascade on update restrict
    );

create table
    if not exists admins (
        id int primary key AUTO_INCREMENT,
        username varchar(256) not null unique,
        email varchar(256) not null unique,
        password varchar(256) not null,
        salt varbinary(256) not null,
        confirmed boolean default 0,
        banned boolean default 0,
        edit_downloads boolean default 0,
        delete_downloads boolean default 0,
        block_users boolean default 0,
        block_admins boolean default 0
    );
create table
    if not exists complaints (
        id int primary key AUTO_INCREMENT,
        header varchar(256) not null,
        text text not null,
        email varchar(256) not null,
        admin_id int,
        state varchar(64) not null,
        file_id int not null,
        constraint `files_file_foreign_key` foreign key (file_id) references files (id) on delete cascade on update restrict,
        constraint `files_admins_foreign_key` foreign key (admin_id) references admins (id) on delete cascade on update restrict
    );

create view if not exists file_view as (select files.id as id, category_id, files.name as filename, link, files.description as description , rating, date, categories.links, downloads, users.id as author_id, users.username as author_name, categories.name as category_name from files inner join categories on files.category_id = categories.id inner join users on files.user_id = users.id);

DROP TRIGGER IF EXISTS after_insert_files;

-- Change the delimiter to handle semicolons within the trigger
DELIMITER //

-- Create the trigger
CREATE TRIGGER after_insert_files
AFTER INSERT
ON files FOR EACH ROW

BEGIN
    DECLARE category_links INT;

    -- Get the current value of links in the corresponding category
    SELECT links INTO category_links
    FROM categories
    WHERE id = NEW.category_id;

    -- Update the links value in the categories table
    UPDATE categories
    SET links = category_links + 1
    WHERE id = NEW.category_id;
END //

-- Reset the delimiter to the default
DELIMITER ;

-- Change the delimiter to handle semicolons within the trigger
DELIMITER //

DROP TRIGGER IF EXISTS before_delete_files;
-- Create the trigger
CREATE TRIGGER before_delete_files
BEFORE DELETE
ON files FOR EACH ROW

BEGIN
    DECLARE category_links INT;

    -- Get the current value of links in the corresponding category
    SELECT links INTO category_links
    FROM categories
    WHERE id = NEW.category_id;

    -- Update the links value in the categories table
    UPDATE categories
    SET links = category_links - 1
    WHERE id = NEW.category_id;
END //

-- Reset the delimiter to the default
DELIMITER ;