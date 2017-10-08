-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 07, 2017 at 10:27 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `QBnB`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(8) NOT NULL,
  `member_id` int(8) NOT NULL,
  `property_id` int(8) NOT NULL,
  `start_date` date NOT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `member_id`, `property_id`, `start_date`, `status`) VALUES
(3, 5, 4, '2015-12-20', 'confirmed'),
(6, 7, 8, '2015-07-22', 'confirmed'),
(8, 9, 4, '2015-08-22', 'confirmed'),
(9, 10, 10, '2015-05-02', 'confirmed'),
(10, 4, 8, '2016-02-25', 'confirmed'),
(12, 7, 4, '2016-04-02', 'requested'),
(13, 13, 11, '2016-07-20', 'requested'),
(14, 11, 8, '2016-01-18', 'confirmed'),
(15, 12, 9, '2015-09-02', 'confirmed'),
(21, 12, 16, '0000-00-00', 'Denied'),
(22, 12, 16, '2016-08-11', 'Denied'),
(23, 12, 16, '2016-08-19', 'Denied'),
(24, 12, 16, '2016-12-09', 'Denied'),
(25, 12, 16, '2016-12-17', 'Denied'),
(26, 12, 16, '2016-12-21', 'Denied'),
(27, 12, 16, '2016-12-29', 'Denied'),
(28, 12, 16, '2016-05-11', 'Denied'),
(29, 12, 16, '2016-03-15', 'Denied'),
(30, 12, 16, '2016-05-03', 'Denied'),
(31, 12, 16, '2016-05-30', 'Denied'),
(32, 12, 16, '2016-05-12', 'Requested'),
(33, 12, 16, '2016-07-06', 'Requested'),
(34, 12, 16, '0000-00-00', 'Requested'),
(35, 12, 16, '2016-09-01', 'Requested'),
(36, 12, 16, '2016-09-09', 'Requested'),
(37, 12, 16, '2016-08-24', 'Requested'),
(38, 12, 16, '2016-09-17', 'Requested'),
(40, 16, 18, '2016-04-12', 'confirmed'),
(41, 17, 6, '2016-05-22', 'Requested'),
(42, 17, 11, '2016-03-07', 'requested'),
(43, 17, 19, '2016-06-12', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(8) NOT NULL,
  `booking_id` int(8) NOT NULL,
  `rating` int(1) DEFAULT NULL,
  `text` varchar(300) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `booking_id`, `rating`, `text`, `time`) VALUES
(1, 1, 5, 'Gerald was a very excellent host and made us feel welcome.', '2015-04-06 04:31:21'),
(2, 2, 4, 'Gerald was easy to contact and quick with his responses. I would definitely recommend this property.', '2015-07-18 12:53:08'),
(3, 3, 4, 'Comfortable home. Annie''s tips for the nearby highlights were very helpful.', '2016-01-08 09:15:01'),
(4, 7, 5, 'Host was very kind. Would definitely return.', '2015-08-23 02:46:11'),
(5, 8, 4, 'Very enjoyable stay. Thanks Annie.', '2015-09-04 10:40:10'),
(6, 10, 1, 'Room did not smell good, and breakfast was not included although stated.', '2016-03-02 10:00:00'),
(7, 4, 5, 'Very nice place with a great property owner!', '2016-02-10 08:30:00'),
(8, 9, 4, 'I had a great stay, thanks!', '2015-06-20 10:05:00'),
(9, 14, 5, 'I would definitely recommend this place, the location was very convenient!', '2016-01-27 10:10:09'),
(10, 11, 2, 'The matresses on the beds were uncomfortable, and the kitchen had a moldy fridge. Would not recommend this location.', '2015-03-10 05:05:01'),
(11, 15, 5, 'The owner was wonderful and very accomodating to my special requests. Definitely coming back!', '2015-06-20 03:45:31'),
(12, 42, 4, 'This place was great!', '2016-04-04 12:26:11');

-- --------------------------------------------------------

--
-- Table structure for table `degree`
--

CREATE TABLE `degree` (
  `degree_id` int(8) NOT NULL,
  `year` int(4) NOT NULL,
  `faculty` varchar(20) NOT NULL,
  `degree_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `degree`
--

INSERT INTO `degree` (`degree_id`, `year`, `faculty`, `degree_type`) VALUES
(1, 1997, 'Applied Science & En', 'BEng'),
(2, 2002, 'Applied Science & En', 'BEng'),
(3, 2016, 'Applied Science & En', 'BEng'),
(4, 2016, 'Arts & Science', 'BSc'),
(5, 1999, 'Arts & Science', 'BSc'),
(6, 2001, 'Applied Science & En', 'BEng'),
(7, 2005, 'Arts & Science', 'BSc'),
(8, 1995, 'Computing', 'Bsc'),
(9, 1985, 'Arts & Science', 'BSc'),
(10, 2009, 'Medicine', 'MD'),
(11, 2013, 'Computing', 'Bsc'),
(12, 1996, 'Applied Science & En', 'BEng'),
(13, 2002, 'Medicine', 'MD'),
(14, 2017, 'Arts & Science', 'BSc.'),
(15, 1985, 'Arts & Science', 'MSc');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_id` int(8) NOT NULL,
  `district_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_id`, `district_name`) VALUES
(1, 'Kanata Lakes'),
(2, 'Lebreton Flats'),
(3, 'The Glebe'),
(4, 'Centretown'),
(5, 'Westboro'),
(6, 'Sandy Hill'),
(7, 'Bayshore'),
(8, 'Vanier'),
(9, 'Hunt Club'),
(10, 'Redwood'),
(11, 'Nepean'),
(12, 'Hintonburg'),
(13, 'Carlington');

-- --------------------------------------------------------

--
-- Table structure for table `has_degree`
--

CREATE TABLE `has_degree` (
  `member_id` int(8) NOT NULL,
  `degree_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `has_degree`
--

INSERT INTO `has_degree` (`member_id`, `degree_id`) VALUES
(3, 3),
(4, 2),
(5, 4),
(7, 5),
(7, 8),
(9, 6),
(10, 10),
(11, 3),
(12, 9),
(12, 11),
(13, 12),
(15, 13),
(16, 14),
(17, 15);

-- --------------------------------------------------------

--
-- Table structure for table `has_feature`
--

CREATE TABLE `has_feature` (
  `property_id` int(8) NOT NULL,
  `feature_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `has_feature`
--

INSERT INTO `has_feature` (`property_id`, `feature_id`) VALUES
(2, 2),
(2, 3),
(2, 4),
(2, 8),
(2, 9),
(2, 10),
(4, 4),
(4, 5),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(6, 2),
(6, 3),
(6, 7),
(6, 10),
(6, 11),
(7, 2),
(7, 4),
(7, 5),
(8, 1),
(8, 2),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 10),
(9, 6),
(9, 10),
(9, 13),
(10, 2),
(10, 7),
(10, 19),
(11, 8),
(11, 14),
(11, 17),
(11, 18),
(12, 2),
(12, 4),
(12, 19),
(14, 3),
(14, 16),
(14, 18),
(14, 23),
(15, 14),
(15, 15),
(15, 21),
(15, 22),
(16, 1),
(16, 6),
(16, 7),
(16, 8),
(18, 3),
(18, 4),
(18, 8),
(18, 11),
(18, 15),
(19, 2),
(19, 3),
(19, 4),
(19, 5),
(19, 6),
(19, 7),
(19, 8),
(19, 9),
(19, 10),
(19, 13),
(19, 17),
(19, 18),
(19, 22),
(19, 23);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(8) NOT NULL,
  `member_type` varchar(20) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_type`, `first_name`, `last_name`, `email`, `username`, `password`) VALUES
(3, 'general', 'Eric', 'James', 'eric@gmail.com', 'Eric_J', 'scruffy'),
(4, 'general', 'Annie', 'Johnson', 'Annie.Johnson@hotmail.com', 'AnnieJohnson', 'QBnB'),
(5, 'general', 'Amanda', 'Trump', 'Amanda555@hotmail.com', 'Amanda', 'SoccerStar555'),
(6, 'administrator', 'Mike', 'Cole', NULL, 'adminMike', 'H6y*9)kr3'),
(7, 'general', 'Carry', 'Lincoln', 'clinc@gmail.com', 'Carry', 'opensesame'),
(8, 'administrator', 'Ryan', 'John', NULL, 'adminRyan', 'bb6h96J%i8'),
(9, 'general', 'Michael', 'Young', 'mike.young@hotmail.com', 'MikeY', 'drowssap'),
(10, 'general', 'Patrick', 'Martin', 'martin@queensu.ca', 'Mrmartin', 'DatabasesIsLOVE'),
(11, 'general', 'Sophia', 'Hatarak', 'sophia@live.com', 'S_5676', 'onetimeuse'),
(12, 'administrator', 'Mayora', 'Mccarthy', 'mayoraisthebest@gmail.com', 'Mccarthy67', 'westerngrad'),
(13, 'general', 'Ahmed', 'Hassan', 'ahasan@hotmail.com', 'AHassan', 'Barcelona11'),
(14, 'general', 'Madeline', 'Jones', 'mjones@live.com', 'JonesGirl1', 'makeup101'),
(15, 'administrator', 'Adam', 'Levin', 'adamLevin123@gmail.com', 'adminAdam', 'Levin4@#$'),
(16, 'general', '', 'Marion', 'justin@hotmail.com', 'admin', 'admin'),
(17, 'general', 'Toinet', 'Hoenselaar', 'toilet@gmail.com', 'Toinet', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `member_phone`
--

CREATE TABLE `member_phone` (
  `member_id` int(8) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_phone`
--

INSERT INTO `member_phone` (`member_id`, `phone_number`) VALUES
(3, '6134567890'),
(4, '6049851628'),
(5, '6789356792'),
(7, '6138903456'),
(9, '6138950856'),
(9, '6792909812'),
(10, '4167845547'),
(10, '6132365872'),
(11, '6476246624'),
(12, '6472623847'),
(13, '6132228569'),
(14, '6132587452'),
(15, '4166549852'),
(16, '5367826588'),
(17, '61763874889');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(8) NOT NULL,
  `member_id` int(8) NOT NULL,
  `message` varchar(255) NOT NULL,
  `seen` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `member_id`, `message`, `seen`) VALUES
(2, 3, 'Your booking #103452 has been cancelled', 1),
(3, 7, 'Booking request #00000006 has been accepted', 0),
(4, 9, 'Booking request #00000007 has been accepted', 1),
(5, 4, 'A review has been posted on your property regarding booking #00000003', 1),
(6, 10, 'You have a new booking request on your property.', 0),
(7, 11, 'Booking request #00000009 has been accepted', 1),
(8, 4, 'Your booking for 271 Kirchoffer Ave has been cancelled because the property owner has removed the property listing.', 0),
(9, 10, 'You have a new booking request on your property.', 1),
(10, 7, 'Booking request #00000001 has been accepted', 1),
(11, 9, 'Booking request #00000004 has been accepted', 0),
(12, 4, 'A booking has been added to one of your properties.', 0),
(13, 4, 'A booking has been added to one of your properties.', 0),
(14, 4, 'A booking has been added to one of your properties.', 0),
(18, 12, 'A booking has been added to one of your properties.', 0),
(19, 12, 'A booking has been added to one of your properties.', 0),
(20, 12, 'A booking has been added to one of your properties.', 0),
(21, 12, 'A booking has been added to one of your properties.', 0),
(22, 12, 'A booking has been added to one of your properties.', 0),
(23, 12, 'A booking has been added to one of your properties.', 0),
(24, 12, 'A booking has been added to one of your properties.', 0),
(25, 12, 'Your booking #21 was denied, sorry!', 0),
(26, 12, 'Your booking #22 was denied, sorry!', 0),
(27, 12, 'Your booking #23 was denied, sorry!', 0),
(28, 12, 'Your booking #24 was denied, sorry!', 0),
(29, 12, 'Your booking #25 was denied, sorry!', 0),
(30, 12, 'Your booking #26 was denied, sorry!', 0),
(31, 12, 'Your booking #27 was denied, sorry!', 0),
(32, 12, 'A booking has been added to one of your properties.', 0),
(33, 12, 'A booking has been added to one of your properties.', 0),
(34, 12, 'A booking has been added to one of your properties.', 0),
(35, 12, 'A booking has been added to one of your properties.', 0),
(36, 12, 'Your booking #28 was denied, sorry!', 0),
(37, 12, 'Your booking #29 was denied, sorry!', 0),
(38, 12, 'Your booking #30 was denied, sorry!', 0),
(39, 12, 'Your booking #31 was denied, sorry!', 0),
(40, 12, 'A booking has been added to one of your properties.', 0),
(41, 12, 'A booking has been added to one of your properties.', 0),
(42, 12, 'A booking has been added to one of your properties.', 0),
(43, 12, 'A booking has been added to one of your properties.', 0),
(44, 12, 'A booking has been added to one of your properties.', 0),
(45, 12, 'A booking has been added to one of your properties.', 0),
(46, 12, 'A booking has been added to one of your properties.', 0),
(47, 5, 'A booking has been added to one of your properties.', 0),
(48, 5, 'A booking on one of your properties was altered', 0),
(49, 5, 'A booking on one of your properties was removed', 0),
(50, 16, 'A booking has been added to one of your properties.', 1),
(51, 16, 'Your booking #40 was confirmed, thank you!', 1),
(53, 5, 'A booking has been added to one of your properties.', 0),
(54, 5, 'A booking on one of your properties was altered', 0),
(55, 5, 'A booking on one of your properties was altered', 0),
(56, 5, 'A booking on one of your properties was altered', 0),
(57, 5, 'A booking on one of your properties was altered', 0),
(58, 10, 'A booking has been added to one of your properties.', 0),
(59, 17, 'A booking has been added to one of your properties.', 1),
(60, 17, 'Your booking #43 was confirmed, thank you!', 1),
(61, 17, 'A booking on one of your properties was altered', 0),
(62, 17, 'Your booking #43 was confirmed, thank you!', 0);

-- --------------------------------------------------------

--
-- Table structure for table `points_of_interest`
--

CREATE TABLE `points_of_interest` (
  `poi_id` int(8) NOT NULL,
  `district_id` int(8) NOT NULL,
  `poi_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `points_of_interest`
--

INSERT INTO `points_of_interest` (`poi_id`, `district_id`, `poi_name`) VALUES
(1, 1, 'Kanata Centrum'),
(2, 1, 'Kanata Golf and Country Club'),
(3, 2, 'Canadian War Museum'),
(4, 3, 'Lansdowne Park'),
(5, 3, 'Dow''s Lake Pavilion'),
(6, 3, 'Rideau Canal'),
(7, 4, 'Parliament Hill'),
(8, 4, 'Canadian Museum of Nature'),
(9, 4, 'Supreme Court of Canada'),
(10, 5, 'Westboro Beach'),
(11, 5, 'Hampton Park'),
(12, 6, 'University of Ottawa'),
(13, 7, 'Andrew Hadon Park'),
(14, 8, 'Downtown Rideau'),
(15, 8, 'Tag Zone'),
(16, 9, 'Canadian Mint'),
(17, 10, 'Hog''s Back Falls'),
(18, 10, 'Diefenbunker'),
(19, 11, 'Nepean Museum'),
(20, 11, 'Nepean Point'),
(21, 11, 'Nepean Creative Arts Centre');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `property_id` int(8) NOT NULL,
  `district_id` int(8) NOT NULL,
  `street_number` int(7) DEFAULT NULL,
  `street_name` varchar(15) DEFAULT NULL,
  `apt_number` int(3) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `postal_code` varchar(6) NOT NULL,
  `type` varchar(30) NOT NULL,
  `num_guests` int(1) DEFAULT NULL,
  `price` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`property_id`, `district_id`, `street_number`, `street_name`, `apt_number`, `city`, `province`, `postal_code`, `type`, `num_guests`, `price`) VALUES
(2, 1, 123, 'Sherk Cres', NULL, 'Ottawa', 'Ontario', 'K2K4G9', 'house', 2, 680),
(4, 2, 24, 'Preston St', NULL, 'Ottawa', 'Ontario', 'K3M4J8', 'house', 6, 799),
(6, 4, 238, 'Kent St', 3, 'Ottawa', 'Ontario', 'K2P 2A', 'town home', 7, 589),
(7, 5, 251, 'Royal Ave', NULL, 'Ottawa', 'Ontario', 'K2A 1T', 'house', 3, 455),
(8, 5, 271, 'Kirchoffer Ave', NULL, 'Ottawa', 'Ontario', 'K2A 1Y', 'house', 1, 225),
(9, 6, 184, 'Osgoode St', NULL, 'Ottawa', 'Ontario', 'K1N 6S', 'house', 3, 575),
(10, 7, 735, 'Clifford St', 2, 'Ottawa', 'Ontario', 'H2C 1K', 'town home', 6, 556),
(11, 4, 234, 'Osheaga Dr', 4, 'Ottawa', 'Ontario', 'K5A 2T', 'apartment', 3, 450),
(12, 9, 65, 'Brandon Ave', NULL, 'Ottawa', 'Ontario', 'B4A 2K', 'duplex', 2, 550),
(14, 11, 2100, 'Baseline Rd', 2, 'Ottawa', 'Ontario', 'K2G 5J', 'apartment', 2, 510),
(15, 11, 45, 'Chartwell Ave', NULL, 'Ottawa', 'Ontario', 'K7K 6P', 'town house', 4, 650),
(16, 11, 13, 'Deerfield Dr', NULL, 'Ottawa', 'Ontario', 'K2G 3R', 'house', 3, 450),
(17, 1, 353, 'dghddgh', 45, 'Ottawa', 'Ontario', 'fdhdfh', 'House', 3, 4554),
(18, 7, 5357, 'Division', 1, 'Ottawa', 'Ontario', 'K0C1H0', 'Town House', 5, 567),
(19, 9, 13723, 'Loucks Road', 2, 'Ottawa', 'Ontario', 'K0C1H0', 'House', 6, 520);

-- --------------------------------------------------------

--
-- Table structure for table `property_feature`
--

CREATE TABLE `property_feature` (
  `feature_id` int(8) NOT NULL,
  `feature` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_feature`
--

INSERT INTO `property_feature` (`feature_id`, `feature`) VALUES
(1, 'Kitchen'),
(2, 'Internet'),
(3, 'Shampoo'),
(4, 'Washer'),
(5, 'Dryer'),
(6, 'Heating'),
(7, 'Air Conditioning'),
(8, 'Parking'),
(9, 'TV'),
(10, 'Pets Allowed'),
(11, 'Smoking Allowed'),
(12, 'Wheelchair Accessible'),
(13, 'Pool'),
(14, 'Gym'),
(15, 'Breakfast'),
(16, 'Iron'),
(17, 'Towels'),
(18, 'Smoke Free'),
(19, 'Room Service'),
(20, 'Shuttle Service'),
(21, 'OC Transpo Accesible'),
(22, 'Netflix Service'),
(23, 'Hot Tub');

-- --------------------------------------------------------

--
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
  `reply_id` int(8) NOT NULL,
  `comment_id` int(8) NOT NULL,
  `text` varchar(255) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reply`
--

INSERT INTO `reply` (`reply_id`, `comment_id`, `text`, `time`) VALUES
(1, 1, 'Hey, I''m glad you enjoyed your stay.', '2015-04-06 07:31:21'),
(2, 2, 'It was a pleasure having you visit.', '2015-07-19 02:30:55'),
(3, 3, 'Thanks for the kind review!', '2016-01-08 11:36:02'),
(4, 4, 'Screw you!', '2015-08-01 09:35:23'),
(5, 5, 'I''m glad to hear that. You''re welcome back any time!', '2015-08-23 03:21:10'),
(6, 6, 'You''re welcome! Thanks for the review.', '2015-09-04 10:41:12'),
(7, 7, 'I''m sorry you had a bad experience, please email me with more information so I can compensate appropriately.', '2016-03-03 08:30:00'),
(8, 8, 'Great to hear!', '2016-02-10 10:00:45'),
(9, 9, 'Come again', '2015-07-01 13:32:00'),
(10, 10, 'Please come again!', '2016-01-28 09:30:00'),
(11, 11, 'Sorry to hear that. I hope we can discuss the issues so I can better my service.', '2015-03-11 10:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `property_id` int(8) NOT NULL,
  `member_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`property_id`, `member_id`) VALUES
(2, 3),
(4, 4),
(6, 5),
(7, 7),
(8, 7),
(9, 9),
(10, 10),
(11, 10),
(12, 5),
(14, 6),
(15, 11),
(16, 12),
(18, 16),
(19, 17);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `degree`
--
ALTER TABLE `degree`
  ADD PRIMARY KEY (`degree_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_id`);

--
-- Indexes for table `has_degree`
--
ALTER TABLE `has_degree`
  ADD PRIMARY KEY (`member_id`,`degree_id`),
  ADD KEY `degree_id` (`degree_id`);

--
-- Indexes for table `has_feature`
--
ALTER TABLE `has_feature`
  ADD PRIMARY KEY (`property_id`,`feature_id`),
  ADD KEY `feature_id` (`feature_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `member_phone`
--
ALTER TABLE `member_phone`
  ADD PRIMARY KEY (`member_id`,`phone_number`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `points_of_interest`
--
ALTER TABLE `points_of_interest`
  ADD PRIMARY KEY (`poi_id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `property_feature`
--
ALTER TABLE `property_feature`
  ADD PRIMARY KEY (`feature_id`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`property_id`,`member_id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `degree`
--
ALTER TABLE `degree`
  MODIFY `degree_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
  MODIFY `district_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `points_of_interest`
--
ALTER TABLE `points_of_interest`
  MODIFY `poi_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `property_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `property_feature`
--
ALTER TABLE `property_feature`
  MODIFY `feature_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `reply_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `has_degree`
--
ALTER TABLE `has_degree`
  ADD CONSTRAINT `has_degree_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `has_degree_ibfk_2` FOREIGN KEY (`degree_id`) REFERENCES `degree` (`degree_id`) ON DELETE CASCADE;

--
-- Constraints for table `has_feature`
--
ALTER TABLE `has_feature`
  ADD CONSTRAINT `has_feature_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `has_feature_ibfk_2` FOREIGN KEY (`feature_id`) REFERENCES `property_feature` (`feature_id`) ON DELETE CASCADE;

--
-- Constraints for table `member_phone`
--
ALTER TABLE `member_phone`
  ADD CONSTRAINT `member_phone_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `supplies`
--
ALTER TABLE `supplies`
  ADD CONSTRAINT `supplies_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`property_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplies_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
