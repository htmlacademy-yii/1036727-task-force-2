--
-- База данных: taskforce
--

CREATE DATABASE IF NOT EXISTS taskforce
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE taskforce;

-- --------------------------------------------------------

--
-- Структура таблицы city
--

CREATE TABLE city (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(128) NOT NULL,
	latitude FLOAT NOT NULL,
	longitude FLOAT NOT NULL
);

-- --------------------------------------------------------

--
-- Структура таблицы user
--

CREATE TABLE user (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	email VARCHAR(128) NOT NULL UNIQUE,
	name VARCHAR(128) NOT NULL,
	password VARCHAR(255) NOT NULL,
	city_id INT UNSIGNED NOT NULL,
	is_executor BOOLEAN NOT NULL DEFAULT 1,
	last_online TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
	FOREIGN KEY (city_id) REFERENCES city(id),
	FULLTEXT INDEX user_ft_search(name)
);

-- --------------------------------------------------------

--
-- Структура таблицы user_profile
--

CREATE TABLE user_profile (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	address VARCHAR(128) NULL,
	birthday TIMESTAMP NULL,
	about VARCHAR(128) NULL,
	avatar_path VARCHAR(128) NULL UNIQUE,

	phone VARCHAR(11) NULL UNIQUE,
	skype VARCHAR(128) NULL UNIQUE,
	messenger VARCHAR(64) NULL UNIQUE,

	new_message BOOLEAN NOT NULL DEFAULT 0,
	activities BOOLEAN NOT NULL DEFAULT 0,
	new_review BOOLEAN NOT NULL DEFAULT 0,

	show_contacts BOOLEAN NOT NULL DEFAULT 1,
	show_profile BOOLEAN NOT NULL DEFAULT 1,

	failed_task_count INT NOT NULL DEFAULT 0,
	user_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user(id),
	UNIQUE INDEX user_profile(id, user_id)
);

-- --------------------------------------------------------

--
-- Структура таблицы category
--

CREATE TABLE category (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(64) NOT NULL,
	inner_name VARCHAR(64) NOT NULL
);

-- --------------------------------------------------------

--
-- Структура таблицы user_category
--

CREATE TABLE user_category (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	category_id INT UNSIGNED NOT NULL,
	user_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (category_id) REFERENCES category(id),
	FOREIGN KEY (user_id) REFERENCES user(id)
);

-- --------------------------------------------------------

--
-- Структура таблицы photo_of_work
--

CREATE TABLE photo_of_work (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	path VARCHAR(128) NOT NULL UNIQUE,
	profile_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (profile_id) REFERENCES user_profile(id)
);

-- --------------------------------------------------------

--
-- Структура таблицы task_status
--

CREATE TABLE task_status (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(64) NOT NULL,
	inner_name VARCHAR(64) NOT NULL
);

-- --------------------------------------------------------

--
-- Структура таблицы task
--

CREATE TABLE task (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	name VARCHAR(128) NOT NULL,
	description VARCHAR(255) NOT NULL,
	budget INT UNSIGNED NULL,
	expire TIMESTAMP NULL,

	address VARCHAR(128) NULL,
	latitude FLOAT NULL,
	longitude FLOAT NULL,
	city_id INT UNSIGNED NULL,

	status_id INT UNSIGNED NOT NULL DEFAULT 1,
	category_id INT UNSIGNED NOT NULL,
	executor_id INT UNSIGNED NOT NULL,
	customer_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (city_id) REFERENCES city(id),
	FOREIGN KEY (status_id) REFERENCES task_status(id),
	FOREIGN KEY (category_id) REFERENCES category(id),
	FOREIGN KEY (executor_id) REFERENCES user(id),
	FOREIGN KEY (customer_id) REFERENCES user(id),
	FULLTEXT INDEX task_ft_search(name)
);

-- --------------------------------------------------------

--
-- Структура таблицы task_file
--

CREATE TABLE task_file (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	path VARCHAR(128) NOT NULL UNIQUE,
	task_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (task_id) REFERENCES task(id)
);

-- --------------------------------------------------------

--
-- Структура таблицы review
--

CREATE TABLE review (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	rate INT UNSIGNED NOT NULL,
	comment VARCHAR(255) NOT NULL,
	user_id INT UNSIGNED NOT NULL,
	author_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (user_id) REFERENCES user(id),
	FOREIGN KEY (author_id) REFERENCES user(id)
);

-- --------------------------------------------------------

--
-- Структура таблицы reply
--

CREATE TABLE reply (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	price INT UNSIGNED NULL,
	comment VARCHAR(255) NULL,
	task_id INT UNSIGNED NOT NULL,
	author_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (task_id) REFERENCES task(id),
	FOREIGN KEY (author_id) REFERENCES user(id)
);

-- --------------------------------------------------------

--
-- Структура таблицы message
--

CREATE TABLE message (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	dt_add TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	content VARCHAR(255) NOT NULL,
	read_status BOOLEAN NOT NULL DEFAULT 0,
	sender_id INT UNSIGNED NOT NULL,
	recipient_id INT UNSIGNED NOT NULL,
	FOREIGN KEY (sender_id) REFERENCES user(id),
	FOREIGN KEY (recipient_id) REFERENCES user(id)
);

-- --------------------------------------------------------
