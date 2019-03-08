use language_flashcards;

INSERT INTO `language` (`id`, `name`) VALUES
    (1, 'Spanish'),
    (2, 'French');

INSERT INTO `category` (`id`, `name`) VALUES
    (1, 'Noun'),
    (2, 'Verb'),
    (3, 'Adjective'),
    (4, 'Adverb'),
    (5, 'Pronoun'),
    (6, 'Preposition'),
    (7, 'Conjunction'),
    (8, 'Determiner'),
    (9, 'Exclamation'),
    (10, 'Expression'); 

INSERT INTO `vocabulary` (`id`, `word_a`, `word_b`, `language_a`, `language_b`, `level`) VALUES
    (1, 'tôt /to/', 'temprano\r\n', 2, 1, 2), 
    (2, 'en retard /ʀ(ə)taʀ/', 'tarde, con retraso\r\n', 2, 1, 2), 
    (3, 'avant (de) /avɑ̃/', 'antes (de)\r\n', 2, 1, 2), 
    (4, 'après /apʀɛ/', 'después\r\n', 2, 1, 1),
    (5, 'tricoter', 'tricoté\r\n', 2, 1, 1),
    (6, 'manger', 'mangé\r\n', 2, 1, 1),
    (7, 'ronflir', 'ronfli\r\n', 2, 1, 1);

INSERT INTO `user` (`id`, `name`, `email`, `native_language`) VALUES
    (1, 'Enrique', 'test@test.com', 1),
    (2, 'Patri', 'test@test.com', 1);

INSERT INTO `vocabulary_category` (`id`,`vocabulary_id`, `category_id`) VALUES
    (1, 1, 1),
    (2, 2, 1),
    (3, 3, 1),
    (4, 4, 1),
    (5, 5, 2),
    (6, 6, 2),
    (7, 7, 2);

INSERT INTO `user_language` (`id`, `user_id`, `language_id`) VALUES
    (1, 1, 1),
    (2, 2, 1);

INSERT INTO `guess` (`user_id`, `vocabulary_id`, `a2b_ok`, `a2b_ko`, `b2a_ok`, `b2a_ko`) VALUES
    (1, 1, 0, 0, 0, 0),
    (1, 2, 0, 0, 0, 0),
    (1, 3, 0, 0, 0, 0),
    (1, 4, 0, 0, 0, 0),
    (2, 1, 0, 0, 0, 0),
    (2, 2, 0, 0, 0, 0),
    (2, 3, 0, 0, 0, 0),
    (2, 4, 0, 0, 0, 0);


