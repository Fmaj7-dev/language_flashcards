
create DATABASE language_flashcards;
use language_flashcards;

CREATE TABLE `language` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `category` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word_a` text COLLATE utf8_bin NOT NULL,
  `word_b` text COLLATE utf8_bin NOT NULL,
  `language_a` smallint NOT NULL,
  `language_b` smallint NOT NULL,
  `level` smallint NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY(`language_a`) REFERENCES language(`id`),
  FOREIGN KEY(`language_b`) REFERENCES language(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `vocabulary_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vocabulary_id` int(11) NOT NULL,
  `category_id` smallint NOT NULL,
  FOREIGN KEY(`vocabulary_id`) REFERENCES vocabulary(`id`),
  FOREIGN KEY(`category_id`) REFERENCES category(`id`),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_bin NOT NULL,
  `email` text COLLATE utf8_bin NOT NULL,
  `native_language` smallint NOT NULL,
  FOREIGN KEY(`native_language`) REFERENCES language(`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `user_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `language_id` smallint NOT NULL,
  FOREIGN KEY(`user_id`) REFERENCES user(`id`),
  FOREIGN KEY(`language_id`) REFERENCES language(`id`),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `guess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vocabulary_id` int(11) NOT NULL,
  `a2b_ok` int(11) NOT NULL,
  `a2b_ko` int(11) NOT NULL,
  `b2a_ok` int(11) NOT NULL,
  `b2a_ko` int(11) NOT NULL,
  FOREIGN KEY(`user_id`) REFERENCES user(`id`),
  FOREIGN KEY(`vocabulary_id`) REFERENCES vocabulary(`id`),
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

