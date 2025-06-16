-- Insert new users
INSERT INTO `users` (`username`, `password`, `moderatorstatus`) VALUES
('guitar_master', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('rock_star', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('jazz_cat', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('metal_head', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('blues_king', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('folk_singer', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('classical_maestro', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('pop_princess', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('hip_hop_hero', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('country_crooner', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('electronic_beat', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('indie_artist', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('punk_rocker', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('soul_singer', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('reggae_rhythm', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('funk_master', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('gospel_voice', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('latin_beat', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('world_music', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0),
('jazz_fusion', '$2y$10$vSkMJAOc8Q9jBmUIXIiCC.Dm7P4.OPeW.T2sH/I7.y8VzOlOUKLi2', 0);

-- Insert new songs
INSERT INTO `songs` (`title`, `performer`, `noteentrycount`, `userid`, `status`) VALUES
('Sweet Child O'' Mine', 'Guns N'' Roses', 0, 1, 'approved'),
('Stairway to Heaven', 'Led Zeppelin', 0, 2, 'approved'),
('Hotel California', 'Eagles', 0, 3, 'approved'),
('Smells Like Teen Spirit', 'Nirvana', 0, 4, 'approved'),
('Sweet Home Alabama', 'Lynyrd Skynyrd', 0, 5, 'approved'),
('Wonderwall', 'Oasis', 0, 6, 'approved'),
('Purple Haze', 'Jimi Hendrix', 0, 7, 'approved'),
('Back in Black', 'AC/DC', 0, 8, 'approved'),
('Sweet Caroline', 'Neil Diamond', 0, 9, 'approved'),
('Smoke on the Water', 'Deep Purple', 0, 10, 'approved'),
('Sweet Dreams', 'Eurythmics', 0, 11, 'approved'),
('Sweet Emotion', 'Aerosmith', 0, 12, 'approved'),
('Sweet Jane', 'The Velvet Underground', 0, 13, 'approved'),
('Sweet Leaf', 'Black Sabbath', 0, 14, 'approved'),
('Sweet Melissa', 'The Allman Brothers Band', 0, 15, 'approved'),
('Sweet Virginia', 'The Rolling Stones', 0, 16, 'approved'),
('Sweet Talkin'' Woman', 'Electric Light Orchestra', 0, 17, 'approved'),
('Sweet Surrender', 'Bread', 0, 18, 'approved'),
('Sweet Thing', 'Van Morrison', 0, 19, 'approved'),
('Sweet Young Thing', 'The Monkees', 0, 20, 'approved'),
('Sweet Little Angel', 'B.B. King', 0, 21, 'approved'),
('Sweet Home Chicago', 'Robert Johnson', 0, 22, 'approved'),
('Sweet Georgia Brown', 'Brother Bones', 0, 23, 'approved'),
('Sweet Adeline', 'The Mills Brothers', 0, 24, 'approved'),
('Sweet Lorraine', 'Nat King Cole', 0, 25, 'approved'),
('Sweet Sue', 'Benny Goodman', 0, 26, 'approved'),
('Sweet and Lovely', 'Guy Lombardo', 0, 27, 'approved'),
('Sweet Leilani', 'Bing Crosby', 0, 28, 'approved'),
('Sweet and Low', 'Artie Shaw', 0, 29, 'approved'),
('Sweet Someone', 'Glenn Miller', 0, 30, 'approved'),
('Sweet Eloise', 'The Ink Spots', 0, 31, 'approved'),
('Sweet Dreams', 'Patsy Cline', 0, 32, 'approved'),
('Sweet Nothin''s', 'Brenda Lee', 0, 33, 'approved'),
('Sweet Talk', 'The Drifters', 0, 34, 'approved'),
('Sweet Little Sixteen', 'Chuck Berry', 0, 35, 'approved'),
('Sweet Nothings', 'Connie Francis', 0, 36, 'approved'),
('Sweet Talk', 'The Platters', 0, 37, 'approved'),
('Sweet Talk', 'The Shirelles', 0, 38, 'approved'),
('Sweet Talk', 'The Ronettes', 0, 39, 'approved'),
('Sweet Talk', 'The Crystals', 0, 40, 'approved');

-- Add genres to songs
INSERT INTO `song_genres` (`songid`, `genreid`) VALUES
(30, 1), -- Sweet Child O' Mine - Rock
(31, 1), -- Stairway to Heaven - Rock
(32, 1), -- Hotel California - Rock
(33, 1), -- Smells Like Teen Spirit - Rock
(34, 1), -- Sweet Home Alabama - Rock
(35, 1), -- Wonderwall - Rock
(36, 1), -- Purple Haze - Rock
(37, 1), -- Back in Black - Rock
(38, 2), -- Sweet Caroline - Pop
(39, 1), -- Smoke on the Water - Rock
(40, 2), -- Sweet Dreams - Pop
(41, 1), -- Sweet Emotion - Rock
(42, 1), -- Sweet Jane - Rock
(43, 7), -- Sweet Leaf - Metal
(44, 1), -- Sweet Melissa - Rock
(45, 1), -- Sweet Virginia - Rock
(46, 2), -- Sweet Talkin' Woman - Pop
(47, 2), -- Sweet Surrender - Pop
(48, 2), -- Sweet Thing - Pop
(49, 2), -- Sweet Young Thing - Pop
(50, 9), -- Sweet Little Angel - Blues
(51, 9), -- Sweet Home Chicago - Blues
(52, 3), -- Sweet Georgia Brown - Jazz
(53, 3), -- Sweet Adeline - Jazz
(54, 3), -- Sweet Lorraine - Jazz
(55, 3), -- Sweet Sue - Jazz
(56, 3), -- Sweet and Lovely - Jazz
(57, 3), -- Sweet Leilani - Jazz
(58, 3), -- Sweet and Low - Jazz
(59, 3), -- Sweet Someone - Jazz
(60, 3), -- Sweet Eloise - Jazz
(61, 2), -- Sweet Dreams - Pop
(62, 2), -- Sweet Nothin's - Pop
(63, 2), -- Sweet Talk - Pop
(64, 1), -- Sweet Little Sixteen - Rock
(65, 2), -- Sweet Nothings - Pop
(66, 2), -- Sweet Talk - Pop
(67, 2), -- Sweet Talk - Pop
(68, 2), -- Sweet Talk - Pop
(69, 2); -- Sweet Talk - Pop

-- Insert notations
INSERT INTO `notations` (`title`, `dateadded`, `content`, `songid`, `instrumentid`, `userid`) VALUES
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 30, 1, 1),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 30, 1, 2),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 31, 1, 3),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 31, 1, 4),
('Intro', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 32, 1, 5),
('Bridge', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 32, 1, 6),
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 33, 1, 7),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 33, 1, 8),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 34, 1, 9),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 34, 1, 10),
('Intro', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 35, 1, 11),
('Bridge', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 35, 1, 12),
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 36, 1, 13),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 36, 1, 14),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 37, 1, 15),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 37, 1, 16),
('Intro', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 38, 1, 17),
('Bridge', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 38, 1, 18),
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 39, 1, 19),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 39, 1, 20),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 40, 1, 21),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 40, 1, 22),
('Intro', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 41, 1, 23),
('Bridge', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 41, 1, 24),
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 42, 1, 25),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 42, 1, 26),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 43, 1, 27),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 43, 1, 28),
('Intro', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 44, 1, 29),
('Bridge', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 44, 1, 30),
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 45, 1, 31),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 45, 1, 32),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 46, 1, 33),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 46, 1, 34),
('Intro', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 47, 1, 35),
('Bridge', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 47, 1, 36),
('Main Riff', '2025-06-16', '[{"positions":[{"str":1,"fret":0},{"str":2,"fret":0},{"str":3,"fret":0}]},{"positions":[{"str":1,"fret":2},{"str":2,"fret":2},{"str":3,"fret":2}]},{"tact":true},{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"tact":true}]', 48, 1, 37),
('Chorus', '2025-06-16', '[{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true},{"positions":[{"str":1,"fret":8},{"str":2,"fret":8},{"str":3,"fret":8}]},{"tact":true}]', 48, 1, 38),
('Verse', '2025-06-16', '[{"positions":[{"str":1,"fret":3},{"str":2,"fret":3},{"str":3,"fret":3}]},{"positions":[{"str":1,"fret":5},{"str":2,"fret":5},{"str":3,"fret":5}]},{"tact":true},{"positions":[{"str":1,"fret":7},{"str":2,"fret":7},{"str":3,"fret":7}]},{"tact":true}]', 49, 1, 39),
('Solo', '2025-06-16', '[{"positions":[{"str":1,"fret":12},{"str":2,"fret":12},{"str":3,"fret":12}]},{"positions":[{"str":1,"fret":14},{"str":2,"fret":14},{"str":3,"fret":14}]},{"tact":true},{"positions":[{"str":1,"fret":15},{"str":2,"fret":15},{"str":3,"fret":15}]},{"tact":true}]', 49, 1, 40);

-- Insert threads
INSERT INTO `threads` (`title`, `content`, `createdby`) VALUES
('Best Guitar Solos of All Time', 'What are your favorite guitar solos? Share your thoughts!', 1),
('Tips for Beginners', 'New to guitar? Share your questions and tips here!', 2),
('Favorite Guitar Brands', 'Which guitar brands do you prefer and why?', 3),
('Learning Resources', 'Share your favorite resources for learning guitar', 4),
('Guitar Maintenance', 'How do you take care of your guitar?', 5),
('Favorite Guitarists', 'Who are your favorite guitarists and why?', 6),
('Guitar Effects', 'What effects pedals do you use?', 7),
('Recording Tips', 'Share your tips for recording guitar', 8),
('Guitar Theory', 'Discuss music theory for guitarists', 9),
('Guitar Techniques', 'Share your favorite techniques', 10),
('Guitar Gear', 'What gear do you use?', 11),
('Guitar Lessons', 'Share your experience with lessons', 12),
('Guitar Practice', 'How do you practice?', 13),
('Guitar Songs', 'What songs are you learning?', 14),
('Guitar History', 'Discuss the history of guitar', 15),
('Guitar Styles', 'What styles do you play?', 16),
('Guitar Equipment', 'What equipment do you need?', 17),
('Guitar Performance', 'Share your performance experiences', 18),
('Guitar Composition', 'How do you write guitar parts?', 19),
('Guitar Community', 'Let''s build a better guitar community!', 20);

-- Insert comments with replies
INSERT INTO `threadcomments` (`content`, `replytocommentid`, `likecount`, `threadid`, `userid`) VALUES
-- Thread 1 comments
('Jimi Hendrix - All Along the Watchtower solo is amazing!', 0, 5, 74, 1),
('Agreed! The way he uses feedback is incredible', 1, 3, 74, 2),
('Don''t forget Eddie Van Halen''s Eruption!', 0, 4, 74, 3),
('That tapping technique changed everything', 3, 2, 74, 4),
('What about David Gilmour''s Comfortably Numb solo?', 0, 6, 74, 5),
('The emotion in that solo is unmatched', 5, 3, 74, 6),
('Slash''s November Rain solo is a masterpiece', 0, 4, 74, 7),
('The way he builds up to the climax is perfect', 7, 2, 74, 8),
('Don''t forget Randy Rhoads!', 0, 3, 74, 9),
('His technical ability was incredible', 9, 2, 74, 10),

-- Thread 2 comments
('Start with basic chords!', 0, 4, 75, 11),
('Which chords should I learn first?', 11, 2, 75, 12),
('G, C, D, and E minor are great to start with', 12, 3, 75, 13),
('Practice switching between them slowly', 13, 2, 75, 14),
('Don''t forget to use a metronome!', 0, 5, 75, 15),
('What tempo should I start with?', 15, 2, 75, 16),
('Try 60 BPM and gradually increase', 16, 3, 75, 17),
('Also, focus on proper finger placement', 0, 4, 75, 18),
('Any tips for sore fingers?', 18, 2, 75, 19),
('It gets better with time, keep practicing!', 19, 3, 75, 20),

-- Thread 3 comments
('Fender Stratocaster is my favorite', 0, 5, 76, 21),
('Why do you prefer it?', 21, 2, 76, 22),
('The versatility and tone are unmatched', 22, 3, 76, 23),
('I love my Gibson Les Paul', 0, 4, 76, 24),
('The sustain is incredible', 24, 2, 76, 25),
('What about PRS?', 0, 3, 76, 26),
('Great quality and playability', 26, 2, 76, 27),
('Don''t forget about Ibanez!', 0, 4, 76, 28),
('Perfect for metal and rock', 28, 2, 76, 29),
('What about acoustic brands?', 0, 3, 76, 30),

-- Thread 4 comments
('YouTube has great tutorials', 0, 5, 77, 31),
('Any specific channels you recommend?', 31, 2, 77, 32),
('Justin Guitar is amazing for beginners', 32, 3, 77, 33),
('What about paid courses?', 0, 4, 77, 34),
('Fender Play is worth it', 34, 2, 77, 35),
('Any good books?', 0, 3, 77, 36),
('The Guitar Handbook is a classic', 36, 2, 77, 37),
('What about apps?', 0, 4, 77, 38),
('Yousician is great for practice', 38, 2, 77, 39),
('Any other recommendations?', 0, 3, 77, 40),

-- Thread 5 comments
('Change strings regularly!', 0, 5, 78, 1),
('How often should I change them?', 1, 2, 78, 2),
('Every 3-4 months with regular playing', 2, 3, 78, 3),
('What about cleaning?', 0, 4, 78, 4),
('Use a microfiber cloth after playing', 4, 2, 78, 5),
('How to prevent humidity damage?', 0, 3, 78, 6),
('Use a humidifier in dry climates', 6, 2, 78, 7),
('What about storage?', 0, 4, 78, 8),
('Keep it in a case when not playing', 8, 2, 78, 9),
('Any other maintenance tips?', 0, 3, 78, 10),

-- Thread 6 comments
('Jimi Hendrix changed everything', 0, 5, 79, 11),
('His innovation was incredible', 11, 2, 79, 12),
('What about Eddie Van Halen?', 0, 4, 79, 13),
('His technique was revolutionary', 13, 2, 79, 14),
('Don''t forget David Gilmour', 0, 3, 79, 15),
('His phrasing is perfect', 15, 2, 79, 16),
('What about modern players?', 0, 4, 79, 17),
('John Mayer is amazing', 17, 2, 79, 18),
('Any other favorites?', 0, 3, 79, 19),

-- Thread 7 comments
('I love my Tube Screamer', 0, 5, 80, 20),
('What settings do you use?', 20, 2, 80, 21),
('Drive at 9 o''clock, tone at 12', 21, 3, 80, 22),
('What about delay?', 0, 4, 80, 23),
('Boss DD-7 is my go-to', 23, 2, 80, 24),
('Any good reverb pedals?', 0, 3, 80, 25),
('Strymon BigSky is amazing', 25, 2, 80, 26),
('What about multi-effects?', 0, 4, 80, 27),
('Line 6 Helix is great', 27, 2, 80, 28),
('Any other recommendations?', 0, 3, 80, 29),

-- Thread 8 comments
('Use a good microphone', 0, 5, 81, 30),
('Which mic do you recommend?', 30, 2, 81, 31),
('SM57 is a classic', 31, 3, 81, 32),
('What about DI recording?', 0, 4, 81, 33),
('Axe-Fx is great for direct recording', 33, 2, 81, 34),
('How to get good tone?', 0, 3, 81, 35),
('Start with a clean sound', 35, 2, 81, 36),
('What about mixing?', 0, 4, 81, 37),
('EQ is crucial', 37, 2, 81, 38),
('Any other tips?', 0, 3, 81, 39),

-- Thread 9 comments
('Learn the major scale first', 0, 5, 82, 40),
('Why is it important?', 40, 2, 82, 1),
('It''s the foundation of most music', 41, 3, 82, 2),
('What about modes?', 0, 4, 82, 3),
('Start with Ionian and Aeolian', 43, 2, 82, 4),
('How to learn chord construction?', 0, 3, 82, 5),
('Start with triads', 45, 2, 82, 6),
('What about progressions?', 0, 4, 82, 7),
('I-IV-V is a good start', 47, 2, 82, 8),
('Any other theory basics?', 0, 3, 82, 9),

-- Thread 10 comments
('Hammer-ons and pull-offs are essential', 0, 5, 83, 10),
('How to practice them?', 50, 2, 83, 11),
('Start slow and build speed', 51, 3, 83, 12),
('What about bending?', 0, 4, 83, 13),
('Practice with a tuner', 53, 2, 83, 14),
('How to improve vibrato?', 0, 3, 83, 15),
('Start wide and get tighter', 55, 2, 83, 16),
('What about tapping?', 0, 4, 83, 17),
('Start with simple patterns', 57, 2, 83, 18),
('Any other techniques?', 0, 3, 83, 19),

-- Thread 11 comments
('I use a Fender amp', 0, 5, 84, 20),
('Which model?', 60, 2, 84, 21),
('Twin Reverb is my favorite', 61, 3, 84, 22),
('What about pedals?', 0, 4, 84, 23),
('I use a small board', 63, 2, 84, 24),
('How to choose a guitar?', 0, 3, 84, 25),
('Try different ones in store', 65, 2, 84, 26),
('What about strings?', 0, 4, 84, 27),
('I prefer Ernie Ball', 67, 2, 84, 28),
('Any other gear?', 0, 3, 84, 29),

-- Thread 12 comments
('Private lessons helped me most', 0, 5, 85, 30),
('How often did you go?', 70, 2, 85, 31),
('Once a week for a year', 71, 3, 85, 32),
('What about online lessons?', 0, 4, 85, 33),
('They can be good too', 73, 2, 85, 34),
('How to find a good teacher?', 0, 3, 85, 35),
('Ask for recommendations', 75, 2, 85, 36),
('What about group lessons?', 0, 4, 85, 37),
('Great for motivation', 77, 2, 85, 38),
('Any other advice?', 0, 3, 85, 39),

-- Thread 13 comments
('Set specific goals', 0, 5, 86, 40),
('Like what?', 80, 2, 86, 1),
('Learn one song per week', 81, 3, 86, 2),
('How long to practice?', 0, 4, 86, 3),
('Start with 30 minutes daily', 83, 2, 86, 4),
('What to practice?', 0, 3, 86, 5),
('Mix of scales and songs', 85, 2, 86, 6),
('How to stay motivated?', 0, 4, 86, 7),
('Record your progress', 87, 2, 86, 8),
('Any other tips?', 0, 3, 86, 9),

-- Thread 14 comments
('Currently learning Stairway', 0, 5, 87, 10),
('How''s it going?', 90, 2, 87, 11),
('The solo is challenging', 91, 3, 87, 12),
('What about other songs?', 0, 4, 87, 13),
('Working on Hotel California', 93, 2, 87, 14),
('How to choose songs?', 0, 3, 87, 15),
('Start with your favorites', 95, 2, 87, 16),
('What about difficulty?', 0, 4, 87, 17),
('Gradually increase challenge', 97, 2, 87, 18),
('Any recommendations?', 0, 3, 87, 19),

-- Thread 15 comments
('Guitar started in Spain', 0, 5, 88, 20),
('Really? Tell me more', 100, 2, 88, 21),
('It evolved from the lute', 101, 3, 88, 22),
('What about electric guitar?', 0, 4, 88, 23),
('Les Paul was a pioneer', 103, 2, 88, 24),
('How did it evolve?', 0, 3, 88, 25),
('Many innovations over time', 105, 2, 88, 26),
('What about modern guitars?', 0, 4, 88, 27),
('Digital technology changed everything', 107, 2, 88, 28),
('Any other history?', 0, 3, 88, 29),

-- Thread 16 comments
('I play blues mostly', 0, 5, 89, 30),
('What got you into blues?', 110, 2, 89, 31),
('BB King inspired me', 111, 3, 89, 32),
('What about rock?', 0, 4, 89, 33),
('Classic rock is my favorite', 113, 2, 89, 34),
('How about jazz?', 0, 3, 89, 35),
('Still learning jazz chords', 115, 2, 89, 36),
('What about metal?', 0, 4, 89, 37),
('Love playing metal riffs', 117, 2, 89, 38),
('Any other styles?', 0, 3, 89, 39),

-- Thread 17 comments
('Start with a good amp', 0, 5, 90, 40),
('Which one?', 120, 2, 90, 1),
('Fender Mustang is great', 121, 3, 90, 2),
('What about pedals?', 0, 4, 90, 3),
('Start with basics', 123, 2, 90, 4),
('How to choose a guitar?', 0, 3, 90, 5),
('Try different ones', 125, 2, 90, 6),
('What about accessories?', 0, 4, 90, 7),
('Get good cables', 127, 2, 90, 8),
('Any other essentials?', 0, 3, 90, 9),

-- Thread 18 comments
('First gig was nerve-wracking', 0, 5, 91, 10),
('How did you handle it?', 130, 2, 91, 11),
('Practice helped a lot', 131, 3, 91, 12),
('What about mistakes?', 0, 4, 91, 13),
('Keep playing through them', 133, 2, 91, 14),
('How to prepare?', 0, 3, 91, 15),
('Practice with a band', 135, 2, 91, 16),
('What about stage presence?', 0, 4, 91, 17),
('Be confident and have fun', 137, 2, 91, 18),
('Any other tips?', 0, 3, 91, 19),

-- Thread 19 comments
('Start with a melody', 0, 5, 92, 20),
('How to develop it?', 140, 2, 92, 21),
('Add chords underneath', 141, 3, 92, 22),
('What about structure?', 0, 4, 92, 23),
('Verse-chorus-verse is classic', 143, 2, 92, 24),
('How to add solos?', 0, 3, 92, 25),
('Build on the melody', 145, 2, 92, 26),
('What about lyrics?', 0, 4, 92, 27),
('Match the mood of the music', 147, 2, 92, 28),
('Any other advice?', 0, 3, 92, 29),

-- Thread 20 comments
('Share your knowledge', 0, 5, 93, 30),
('How to help beginners?', 150, 2, 93, 31),
('Be patient and encouraging', 151, 3, 93, 32),
('What about online?', 0, 4, 93, 33),
('Create helpful content', 153, 2, 93, 34),
('How to connect?', 0, 3, 93, 35),
('Join local groups', 155, 2, 93, 36),
('What about events?', 0, 4, 93, 37),
('Organize jam sessions', 157, 2, 93, 38),
('Any other ideas?', 0, 3, 93, 39); 