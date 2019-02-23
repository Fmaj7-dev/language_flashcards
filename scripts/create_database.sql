
create DATABASE language_flashcards;
use language_flashcards;

CREATE TABLE `vocabulary` (
  `id` int(11) NOT NULL,
  `lang_a` text COLLATE utf8_bin NOT NULL,
  `lang_b` text COLLATE utf8_bin NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_bin NOT NULL,
  `email` text COLLATE utf8_bin NOT NULL,
  `native_language` int(11) NOT NULL,
  FOREIGN KEY(`native_language`) REFERENCES languages(`id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `user_language` (
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  FOREIGN KEY(`user_id`) REFERENCES users(`id`),
  FOREIGN KEY(`language_id`) REFERENCES languages(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `guess` (
  `user_id` int(11) NOT NULL,
  `vocabulary_id` int(11) NOT NULL,
  `a2b_ok` int(11) NOT NULL,
  `a2b_ko` int(11) NOT NULL,
  `b2a_ok` int(11) NOT NULL,
  `b2a_ko` int(11) NOT NULL,
  FOREIGN KEY(`user_id`) REFERENCES users(`id`),
  FOREIGN KEY(`vocabulary_id`) REFERENCES vocabulary(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

