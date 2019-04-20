-- ---------------------------------------
-- BAZA DANYCH - Quiz
-- Adding data to tables
-- ---------------------------------------



-- --------------------------------------------
-- Query adding data to table ticketPriority
-- --------------------------------------------
INSERT INTO groups (ID, name )
VALUES
(1, 'Administrators'),
(2, 'IT'),
(3, 'User');

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