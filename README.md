**SOLUTION parts.<br/>

--Fixed bugs - <br/>
  indexes and 2nd diagonal winning.<br/>
--New features: <br/>
board size, <br/>
Save and Restore from DB,<br/>
human or computer mode.<br/>

--Design <br/>
added colors and emphasized winning squares.

-- I have worked on XAMP on Windows 10, 
it looked like that
http://localhost/JobExams/caveret/tic-test/web/start/4/computer

**Code description <br/>

You will see in default.yml all the route I added. <br/>
Briefly, start route contains size for board size and mode - either human or computer. <br/>

So, if you start with /web/start it will start with default size 0f 3 and human mode,<br/>
/web/start is equal to /web/start/3/human <br/>
Yet you can change to /web/start/4/computer and in this case you will get board of 4 and computer will play for 'o'<br/>
each 2 seconds.<br/>
Also You will need to set up DB credentials as detailed below to enjoy Save and Restore options when clicking respective buttons.<br/>
Game json is saved and retrieved from DB table game has only one row for that json.<br/>
<br/>
<br/>

**DB setup description <br/>

You will need to set up tou mysql or whatever credentials in parameters.yml file <br/>
For example: <br/>
parameters:<br/>
    database_host: 127.0.0.1 <br/>
    database_port: 3306 <br/>
    database_name: tic-test <br/>
    database_user: avraham <br/>
    database_password: 123456 <br/>
    
and then run manually short sql dump to create tic-test DB and one table called game. See below <br/>

=======================================================================
<br/>
-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2020 at 01:05 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tic-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `json` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `title`, `json`) VALUES
(1, 'last', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


