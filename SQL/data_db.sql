-- ---------------------------------------
-- BAZA DANYCH - Quiz
-- Adding data to tables
-- ---------------------------------------

SELECT t.ID ROW FROM ticket t LEFT JOIN users u ON t.id_declarant = u.ID
WHERE t.ID >0 ORDER BY t.ID DESC LIMIT 5 , 5

-- --------------------------------------------
-- Query adding data to table ticketPriority
-- --------------------------------------------
INSERT INTO groups (ID, name, permission )
VALUES
(1, 'Administrators', '{"tickets":{"all":1,"group":1,"self":1},"add_comment":{"all": 1,"group":1,"self":1},"users page":{"show":1, "hidden": 0}}'),
(2, 'IT', '{"tickets":{"all":1,"group":1,"self":1},"add_comment":{"all": 1,"group":1,"self":1},"users page":{"show":0, "hidden": 1}}'),
(3, 'User', '{"tickets":{"all":0,"group":0,"self":1},"add_comment":{"all":0,"group":0,"self":1},"users page":{"show":0, "hidden": 1}}');

-- --------------------------------------------
-- Query adding data to table ticketPriority
-- --------------------------------------------
INSERT INTO ticketPriority (ID, name_priority)
VALUES
(1, 'Normalny'),
(2, 'Pilny'),
(3, 'Krytyczny');

-- --------------------------------------------
-- Query adding data to table ticketStatus
-- --------------------------------------------
INSERT INTO ticketStatus (ID, name_status)
VALUES
(1, 'Nowe'),
(2, 'W realizacji'),
(3, 'Do odbioru'),
(4, 'Do potwierdzenia'),
(5, 'Do odbioru'),
(6, 'Oczekujące');

-- --------------------------------------------
-- Query adding data to table ticketQueue
-- --------------------------------------------
INSERT INTO ticketQueue (ID, queue)
VALUES 
(1, 'HelpDesk'),
(2, 'Uprawnienia'),
(3, 'Awaria sprzętu'),
(4, 'Poczta'),
(5, 'Office'),
(6, 'Zakup'),
(7, 'Wyporzyczenie');