-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2025 at 03:48 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `de_torres_db`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `bsarch_students`
-- (See below for the actual view)
--
CREATE TABLE `bsarch_students` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `bsa_students`
-- (See below for the actual view)
--
CREATE TABLE `bsa_students` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `bsedeng_students`
-- (See below for the actual view)
--
CREATE TABLE `bsedeng_students` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `bsis_students`
-- (See below for the actual view)
--
CREATE TABLE `bsis_students` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `bsn_students`
-- (See below for the actual view)
--
CREATE TABLE `bsn_students` (
);

-- --------------------------------------------------------

--
-- Table structure for table `institute_tbl`
--

CREATE TABLE `institute_tbl` (
  `ins_id` int(11) NOT NULL,
  `ins_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `institute_tbl`
--

INSERT INTO `institute_tbl` (`ins_id`, `ins_name`) VALUES
(1, 'College of Information Systems'),
(2, 'College of Architecture'),
(3, 'College of Education'),
(4, 'College of Nursing'),
(5, 'College of Accountancy');

-- --------------------------------------------------------

--
-- Table structure for table `program_tbl`
--

CREATE TABLE `program_tbl` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(100) DEFAULT NULL,
  `ins_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `program_tbl`
--

INSERT INTO `program_tbl` (`program_id`, `program_name`, `ins_id`) VALUES
(1, 'BS Information Systems', 1),
(2, 'BS Architecture', 2),
(3, 'BS Nursing', 4),
(4, 'BSEd Major in English', 3),
(5, 'BS Accountancy', 5);

-- --------------------------------------------------------

--
-- Table structure for table `semester_tbl`
--

CREATE TABLE `semester_tbl` (
  `sem_id` int(11) NOT NULL,
  `sem_name` varchar(50) DEFAULT NULL,
  `year_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `semester_tbl`
--

INSERT INTO `semester_tbl` (`sem_id`, `sem_name`, `year_id`) VALUES
(1, '1st Semester', 1),
(2, '2nd Semester', 1),
(3, '1st Semester', 2),
(4, '2nd Semester', 2),
(5, 'Summer', 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_allowance`
-- (See below for the actual view)
--
CREATE TABLE `student_allowance` (
);

-- --------------------------------------------------------

--
-- Table structure for table `student_load`
--

CREATE TABLE `student_load` (
  `load_id` int(11) NOT NULL,
  `stud_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_load`
--

INSERT INTO `student_load` (`load_id`, `stud_id`, `subject_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 3),
(4, 3, 1),
(5, 4, 4);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_program`
-- (See below for the actual view)
--
CREATE TABLE `student_program` (
);

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `stud_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `allowance` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`stud_id`, `first_name`, `middle_name`, `last_name`, `program_id`, `allowance`) VALUES
(1, 'Maria ', 'Torres', 'Mendoza', 3, 5000),
(2, 'Juan', 'Santos', 'Dela Cruz', 2, 1000),
(3, 'Lea', 'Hernandez', 'Bautista', 1, 1000),
(4, 'Jose', 'Garcia ', 'Reyes', 4, 800),
(5, 'Ana ', 'Lopez', 'Ramirez', 5, 900),
(6, 'John ', 'Cruz', 'Villanueva', 1, 1500),
(7, 'Sofia', 'Fernandez', 'Morales', 3, 1200),
(8, 'Mark', 'Domingo', 'Aquino', 2, 1000),
(9, 'Liza', 'Torres', 'Navarro', 4, 1300),
(10, 'Carlo', 'Ramos', 'Gutierez', 5, 1100),
(11, 'Angela', 'Castillo', 'Pascual', 3, 1400),
(12, 'Miguel', 'Flores', 'Salazar', 4, 1250),
(13, 'Beatriz', 'Estrada', 'Manalo', 5, 1600),
(14, 'Dominic', 'Perez', 'Alcantara', 1, 1350),
(15, 'Francine', 'Santiago', 'Yambao', 2, 1500),
(26, '', NULL, '', 3, 3000),
(27, '', NULL, '', 2, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `subject_tbl`
--

CREATE TABLE `subject_tbl` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(100) DEFAULT NULL,
  `sem_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject_tbl`
--

INSERT INTO `subject_tbl` (`subject_id`, `subject_name`, `sem_id`) VALUES
(1, 'Introduction to Programming', 1),
(2, 'Data Structures', 2),
(3, 'Database Management', 3),
(4, 'Accounting Principles', 4),
(5, 'Educational Psychology', 5);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_students`
-- (See below for the actual view)
--
CREATE TABLE `view_students` (
);

-- --------------------------------------------------------

--
-- Table structure for table `year_tbl`
--

CREATE TABLE `year_tbl` (
  `year_id` int(11) NOT NULL,
  `year_from` int(11) DEFAULT NULL,
  `year_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `year_tbl`
--

INSERT INTO `year_tbl` (`year_id`, `year_from`, `year_to`) VALUES
(1, 2023, 2024),
(2, 2024, 2025),
(3, 2025, 2026),
(4, 2026, 2027),
(5, 2027, 2028);

-- --------------------------------------------------------

--
-- Structure for view `bsarch_students`
--
DROP TABLE IF EXISTS `bsarch_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bsarch_students`  AS SELECT `s`.`name` AS `name` FROM (`student_tbl` `s` join `program_tbl` `p` on(`s`.`program_id` = `p`.`program_id`)) WHERE `p`.`program_name` = 'BS Architecture' ;

-- --------------------------------------------------------

--
-- Structure for view `bsa_students`
--
DROP TABLE IF EXISTS `bsa_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bsa_students`  AS SELECT `s`.`name` AS `name` FROM (`student_tbl` `s` join `program_tbl` `p` on(`s`.`program_id` = `p`.`program_id`)) WHERE `p`.`program_name` = 'BS Accountancy' ;

-- --------------------------------------------------------

--
-- Structure for view `bsedeng_students`
--
DROP TABLE IF EXISTS `bsedeng_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bsedeng_students`  AS SELECT `s`.`name` AS `name` FROM (`student_tbl` `s` join `program_tbl` `p` on(`s`.`program_id` = `p`.`program_id`)) WHERE `p`.`program_name` = 'BSEd Major in English' ;

-- --------------------------------------------------------

--
-- Structure for view `bsis_students`
--
DROP TABLE IF EXISTS `bsis_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bsis_students`  AS SELECT `s`.`name` AS `name` FROM (`student_tbl` `s` join `program_tbl` `p` on(`s`.`program_id` = `p`.`program_id`)) WHERE `p`.`program_name` = 'BS Information Systems' ;

-- --------------------------------------------------------

--
-- Structure for view `bsn_students`
--
DROP TABLE IF EXISTS `bsn_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bsn_students`  AS SELECT `s`.`name` AS `name` FROM (`student_tbl` `s` join `program_tbl` `p` on(`s`.`program_id` = `p`.`program_id`)) WHERE `p`.`program_name` = 'BS Nursing' ;

-- --------------------------------------------------------

--
-- Structure for view `student_allowance`
--
DROP TABLE IF EXISTS `student_allowance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_allowance`  AS SELECT `student_tbl`.`name` AS `name`, `student_tbl`.`allowance` AS `allowance` FROM `student_tbl` WHERE `student_tbl`.`allowance` > (select avg(`student_tbl`.`allowance`) from `student_tbl`) ;

-- --------------------------------------------------------

--
-- Structure for view `student_program`
--
DROP TABLE IF EXISTS `student_program`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_program`  AS SELECT `n`.`name` AS `name`, `p`.`program_name` AS `program_name` FROM (`student_tbl` `n` join `program_tbl` `p` on(`n`.`program_id` = `p`.`program_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_students`
--
DROP TABLE IF EXISTS `view_students`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_students`  AS SELECT `student_tbl`.`name` AS `name` FROM `student_tbl` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `institute_tbl`
--
ALTER TABLE `institute_tbl`
  ADD PRIMARY KEY (`ins_id`);

--
-- Indexes for table `program_tbl`
--
ALTER TABLE `program_tbl`
  ADD PRIMARY KEY (`program_id`),
  ADD KEY `ins_id` (`ins_id`);

--
-- Indexes for table `semester_tbl`
--
ALTER TABLE `semester_tbl`
  ADD PRIMARY KEY (`sem_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `student_load`
--
ALTER TABLE `student_load`
  ADD PRIMARY KEY (`load_id`),
  ADD KEY `stud_id` (`stud_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`stud_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `subject_tbl`
--
ALTER TABLE `subject_tbl`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `sem_id` (`sem_id`);

--
-- Indexes for table `year_tbl`
--
ALTER TABLE `year_tbl`
  ADD PRIMARY KEY (`year_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `program_tbl`
--
ALTER TABLE `program_tbl`
  ADD CONSTRAINT `program_tbl_ibfk_1` FOREIGN KEY (`ins_id`) REFERENCES `institute_tbl` (`ins_id`);

--
-- Constraints for table `semester_tbl`
--
ALTER TABLE `semester_tbl`
  ADD CONSTRAINT `semester_tbl_ibfk_1` FOREIGN KEY (`year_id`) REFERENCES `year_tbl` (`year_id`);

--
-- Constraints for table `student_load`
--
ALTER TABLE `student_load`
  ADD CONSTRAINT `student_load_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student_tbl` (`stud_id`),
  ADD CONSTRAINT `student_load_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject_tbl` (`subject_id`);

--
-- Constraints for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD CONSTRAINT `student_tbl_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program_tbl` (`program_id`);

--
-- Constraints for table `subject_tbl`
--
ALTER TABLE `subject_tbl`
  ADD CONSTRAINT `subject_tbl_ibfk_1` FOREIGN KEY (`sem_id`) REFERENCES `semester_tbl` (`sem_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
