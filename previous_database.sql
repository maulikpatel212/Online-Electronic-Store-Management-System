-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2021 at 06:22 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `previous_database`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `bill` (IN `cust_id` INT(5), IN `date` DATE)  NO SQL
BEGIN
select orders.order_id, delivery_address.*, orders.order_time, product.product_name, order_details.qty, product.product_price, product.product_price*order_details.qty as total_price from orders, order_details, delivery_address, product where orders.customer_id = cust_id and orders.order_id = order_details.order_id and orders.delivery_address_id = delivery_address.delivery_address_id and  order_details.product_id = product.product_id and  date(orders.order_time) = date;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delivery_address_insert` (IN `d_block` VARCHAR(50), IN `d_house_no` INT, IN `d_apartment_name` VARCHAR(50), IN `d_street` VARCHAR(50), IN `d_city` VARCHAR(50), IN `d_pincode` INT)  NO SQL
BEGIN

INSERT INTO delivery_address(block,house_no,apartment_name,street,city,pincode) VALUES(d_block,d_house_no,d_apartment_name,d_street,d_city,d_pincode);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `most_sold_product` ()  NO SQL
BEGIN

SELECT order_details.product_id, product.product_name, product.product_details as description, product.product_MRP, product.product_price, count(order_details.product_id) as total_qty_sold, product.product_quantity as available_stock from order_details,product where order_details.product_id=product.product_id group by order_details.product_id order by total_qty_sold desc limit 3;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `my_orders` (IN `uid` INT(5))  BEGIN
if(uid='') THEN
	select orders.*,delivery_address.*,order_status.status as order_status_str from orders,order_status,delivery_address where order_status.status_id=orders.status and delivery_address.delivery_address_id=orders.delivery_address_id order by orders.order_id;
else
	select orders.*,delivery_address.*,order_status.status as order_status_str from orders,order_status,delivery_address where orders.customer_id=uid and order_status.status_id=orders.status and delivery_address.delivery_address_id=orders.delivery_address_id order by orders.order_id;

END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `offer` (IN `ord_id` INT(4))  BEGIN
DECLARE i float;
DECLARE Day,Total,customer_id,order_id int;
DECLARE finished INT DEFAULT 0;
DECLARE cur1 CURSOR FOR SELECT DAYOFWEEK(order_time) as Day,SUM(product.product_price*order_details.qty) as Total,orders.customer_id,orders.order_id from order_details,orders,product where orders.order_id=ord_id and order_details.order_id=orders.order_id and order_details.product_id=product.product_id GROUP BY order_details.order_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    OPEN cur1; 
    get_off : LOOP
    	IF finished = 1 THEN
        	LEAVE get_off;
        END IF;
        FETCH cur1 INTO Day, Total, customer_id, order_id;
		IF (Day=1 or Day=7) and (Total>2000) THEN
			SET i = Total/10;
			IF i>=1000 THEN
    			SET i = 1000;
    		END IF;
			INSERT INTO discount(customer_id, order_id, disc_amount) VALUES(customer_id, order_id, i);
         ELSE
        	INSERT INTO discount(customer_id, order_id, disc_amount) VALUES(customer_id, order_id, 0);
		END IF;
        	
	END LOOP get_off;
    CLOSE cur1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrationInsert` (IN `first_name` VARCHAR(50), IN `middle_name` VARCHAR(50), IN `last_name` VARCHAR(50), IN `contact` BIGINT(20), IN `email` VARCHAR(50), IN `password` VARCHAR(30))  NO SQL
BEGIN

insert into users(first_name,middle_name,last_name,contact,email,password) values(first_name,middle_name,last_name,contact,email,password);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `top3` ()  BEGIN
    DECLARE u_id int;
    DECLARE r1 int DEFAULT 0;
    DECLARE Total_bought, Total_saved float;
    
    DECLARE cur1 cursor for SELECT SUM(product.product_price*order_details.qty) as Total,orders.customer_id from order_details,orders,product where order_details.order_id=orders.order_id and order_details.product_id=product.product_id group by orders.customer_id order by Total desc limit 3;
    DECLARE continue handler for not found set r1 = 1; 
    
    OPEN cur1;
    DELETE from top3;
    get_id: LOOP
    
   	fetch cur1 into Total_bought,u_id;
    IF r1 = 1 THEN
    	LEAVE get_id;
    END IF;

    SELECT SUM(disc_amount) FROM discount WHERE discount.customer_id=u_id GROUP BY customer_id into Total_saved;
      insert into top3(customer_id, Total_bought, Total_saved) values(u_id, Total_bought, Total_saved);
    END LOOP get_id;
    CLOSE cur1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_userprofile` (IN `f_name` VARCHAR(30), IN `m_name` VARCHAR(30), IN `l_name` VARCHAR(30), IN `user_contact` INT(20), IN `mail` VARCHAR(30), IN `pass` VARCHAR(30), IN `u_id` INT(20))  BEGIN
	UPDATE users set first_name=f_name, middle_name=m_name,last_name=l_name, contact=user_contact, email=mail, password=pass where customer_id = u_id;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `admin_duplication` (`admin_username` VARCHAR(30)) RETURNS INT(11) BEGIN
DECLARE if_finished,flag int DEFAULT 0;
DECLARE a varchar(50);
DECLARE cur1 CURSOR FOR SELECT admin_users.username FROM admin_users;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET if_finished  = 1;        
        OPEN cur1;
        FETCH cur1 INTO a;
        WHILE if_finished = 0 DO
        if (a=admin_username) then
			set flag = 1;   
	    end if;
        FETCH cur1 INTO a;
        END WHILE;
        RETURN flag;
        CLOSE cur1;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `feedback` (`or_id` INT, `pr_id` INT, `f_feedback` TEXT) RETURNS VARCHAR(50) CHARSET utf8mb4 NO SQL
BEGIN
DECLARE o_id,p_id int;
#DECLARE flag boolean DEFAULT false;
#DECLARE fback text;
#DECLARE msg varchar(50);
DECLARE if_finished int DEFAULT 0;

DECLARE c1 CURSOR for SELECT order_id, product_id from feedback;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET if_finished = 1;
#SELECT count(feedback_id) from feedback into temp;
OPEN c1;
	get_feedback: LOOP
	FETCH c1 INTO o_id,p_id;

IF(if_finished = 1) THEN
	LEAVE get_feedback;
END IF;

IF (o_id = or_id  AND p_id = pr_id) THEN	
	RETURN 'You have already responded';
END IF;		
END LOOP get_feedback;
CLOSE c1;

	INSERT INTO feedback(order_id,product_id,feedback_desc) VALUES(or_id,pr_id,f_feedback);
    RETURN 'Thank you for your response';
    
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `registration` (`f_name` VARCHAR(50), `m_name` VARCHAR(50), `l_name` VARCHAR(50), `u_contact` BIGINT, `u_email` VARCHAR(50), `u_password` VARCHAR(50)) RETURNS VARCHAR(50) CHARSET utf8mb4 NO SQL
BEGIN

DECLARE user_email varchar(50);
DECLARE user_password varchar(50); 
DECLARE if_finished int DEFAULT 0;
DECLARE flag int DEFAULT 0;

DECLARE c1 CURSOR FOR SELECT email,password from users;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET if_finished = 1;

OPEN c1;
	get_user_data: LOOP
    	FETCH c1 into user_email,user_password;
        
IF(if_finished = 1) THEN
	LEAVE get_user_data;
END IF;

IF (user_email = u_email  AND user_password = u_password) THEN	
	RETURN 'exist';
END IF;		
END LOOP get_user_data;
CLOSE c1;

insert into users(first_name,middle_name,last_name,contact,email,password) values(f_name,m_name,l_name,u_contact,u_email,u_password);

RETURN 'success';

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(6, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_status`) VALUES
(22, 'Laptop', 1),
(23, 'Mobile', 1),
(24, 'TV', 1),
(25, 'Earphones', 1);

--
-- Triggers `category`
--
DELIMITER $$
CREATE TRIGGER `cat_deletion` BEFORE DELETE ON `category` FOR EACH ROW begin 
delete from product where category_id=old.category_id;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaints_id` int(11) NOT NULL,
  `customer_id` int(50) NOT NULL,
  `order_id` int(11) NOT NULL,
  `complaint` text NOT NULL,
  `admin_reply` varchar(500) NOT NULL,
  `complaint_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaints_id`, `customer_id`, `order_id`, `complaint`, `admin_reply`, `complaint_date`) VALUES
(59, 100, 56, 'Very bad', 'Ok dne', '2021-04-16'),
(60, 100, 57, 'hmm', '', '2021-04-16'),
(61, 100, 58, 'Faulty', '', '2021-04-16'),
(63, 117, 61, 'Faulty...Not expected this...', 'Sorry for inconvenience...We will get back to you soon...', '2021-05-06'),
(64, 117, 61, 'Faulty...Not expected this...', '', '2021-05-06'),
(65, 121, 69, 'faulty product...never expected', 'We will get back to you soon...', '2021-05-07');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_address`
--

CREATE TABLE `delivery_address` (
  `delivery_address_id` int(11) NOT NULL,
  `block` varchar(10) NOT NULL,
  `house_no` int(11) NOT NULL,
  `apartment_name` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery_address`
--

INSERT INTO `delivery_address` (`delivery_address_id`, `block`, `house_no`, `apartment_name`, `street`, `city`, `pincode`) VALUES
(47, '2', 101, 'shreeji apartment', 'cg road', 'Ahmedabad', 365645),
(48, '2', 9, 'Satnam Society', 'Elish Bridge Road', 'cdsac', 250001),
(49, 'E', 11, 'Shubham Apartment', 'John\'s Street', 'Rajkot', 342423),
(50, 'E', 11, 'Tirth Academy', 'Janmarg Char Rasta', 'Junagadh', 342423),
(51, 'G', 11, 'Shubh Apartment', 'John\'s Street', 'bgdbg', 10000),
(52, 'E', 33, 'Tirth Academy', 'John\'s Street', 'Rajkot', 54325),
(53, 'F', 11, 'Satnam Society', 'Elish Bridge Road', 'Rajkot', 250001),
(54, 'G', 9, 'Satnam Society', 'Janmarg Char Rasta', 'Junagadh', 10000),
(55, 'F', 11, 'Satnam Society', 'Janmarg Char Rasta', 'Rajkot', 1002),
(56, 'e', 9, 'Shubham Apartment', 'Dohn Street', 'Rajkot', 342423),
(57, 'F', 10, 'Tirth Academy', 'Janmarg Char Rasta', 'Rajkot', 10000),
(58, 'E', 33, 'Tirth Academy', 'Janmarg Char Rasta', 'Junagadh', 342423);

--
-- Triggers `delivery_address`
--
DELIMITER $$
CREATE TRIGGER `address_check` BEFORE INSERT ON `delivery_address` FOR EACH ROW BEGIN
    DECLARE d_block varchar(4);
    DECLARE d_house_no int;
    DECLARE d_apartment_name varchar(20);
    DECLARE d_street varchar(20);
    DECLARE d_city varchar(30);
    DECLARE d_pincode int;
    DECLARE is_finished int DEFAULT 0;
    DECLARE c_1 CURSOR FOR SELECT block, house_no, apartment_name, street, city, pincode from delivery_address;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET is_finished = 1;
    OPEN c_1;
    get_d: LOOP
    	FETCH c_1 into d_block, d_house_no, d_apartment_name, d_street, d_city, d_pincode;
        IF is_finished = 1 THEN
        	LEAVE get_d;
        END IF;
        IF d_block=new.block AND d_house_no=new.house_no AND d_apartment_name=new.apartment_name AND d_street=new.street AND d_city=new.city AND d_pincode=new.pincode THEN
        CALL raise_application_error(20001, 'Inserted Successfully');	
         END IF;
     END LOOP get_d;
     CLOSE c_1;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `disc_amount` int(5) NOT NULL,
  `customer_id` int(3) NOT NULL,
  `order_id` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`disc_amount`, `customer_id`, `order_id`) VALUES
(0, 100, 56),
(0, 100, 57),
(0, 100, 58),
(0, 100, 59),
(0, 113, 60),
(0, 117, 61),
(0, 117, 62),
(0, 100, 63),
(0, 100, 64),
(0, 100, 65),
(0, 100, 66),
(0, 121, 69);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `feedback_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `order_id`, `product_id`, `feedback_desc`) VALUES
(17, 57, 21, 'Satisfied'),
(18, 56, 27, 'Satisfied'),
(19, 60, 19, 'Satisfied'),
(20, 61, 19, 'Not Satisied'),
(22, 69, 26, 'Not Satisied...');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `delivery_address_id` int(11) NOT NULL,
  `order_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `delivery_address_id`, `order_time`, `status`) VALUES
(56, 100, 48, '2021-04-16 00:49:48', 1),
(57, 100, 49, '2021-04-16 00:57:52', 1),
(58, 100, 50, '2021-04-16 02:32:11', 1),
(59, 100, 51, '2021-05-07 08:04:56', 4),
(60, 113, 52, '2021-05-06 01:44:30', 1),
(61, 117, 53, '2021-05-06 03:21:11', 1),
(62, 117, 54, '2021-05-07 08:04:28', 4),
(63, 100, 55, '2021-05-07 04:36:30', 1),
(64, 100, 56, '2021-05-07 04:49:28', 1),
(65, 100, 57, '2021-05-07 04:54:04', 1),
(66, 100, 57, '2021-05-07 05:11:55', 1),
(69, 121, 58, '2021-05-07 13:16:22', 4);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_details_id` int(10) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_details_id`, `order_id`, `product_id`, `qty`) VALUES
(63, 56, 27, 1),
(64, 57, 21, 1),
(65, 58, 21, 1),
(66, 59, 26, 1),
(67, 60, 19, 2),
(68, 60, 24, 1),
(69, 61, 19, 1),
(70, 61, 24, 1),
(71, 62, 25, 1),
(72, 62, 21, 1),
(73, 63, 27, 1),
(74, 64, 25, 1),
(75, 65, 26, 1),
(76, 66, 26, 1),
(83, 69, 26, 1),
(84, 69, 19, 1),
(85, 69, 21, 1);

--
-- Triggers `order_details`
--
DELIMITER $$
CREATE TRIGGER `stock_manage` AFTER INSERT ON `order_details` FOR EACH ROW BEGIN
DECLARE temp,temp2 int;
SELECT product_quantity from product where product.product_id=new.product_id into temp;
SET temp2 = (temp - new.qty);
update product set product_quantity=temp2 WHERE product.product_id=new.product_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `status_id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`status_id`, `status`) VALUES
(1, 'Pending'),
(2, 'Shipped'),
(3, 'Cancelled'),
(4, 'Dispatched');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_details` text CHARACTER SET latin1 NOT NULL,
  `product_MRP` int(15) NOT NULL,
  `product_price` float NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_status` tinyint(4) NOT NULL,
  `product_image` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `category_id`, `product_details`, `product_MRP`, `product_price`, `product_quantity`, `product_status`, `product_image`) VALUES
(19, 'boAt Rockerz 255F', 25, 'Best in this range...', 2990, 999, 17, 1, '554827038_'),
(20, 'Lenovo ThinkPad E15', 22, 'Fast Processor with lightwiight', 77499, 64990, 10, 1, '789561468_'),
(21, 'DELL Inspiron Ryzen 3', 22, 'Pre-installed Genuine Windows 10 OS', 40200, 36700, 24, 1, '149553147_'),
(24, 'Mi 4A PRO', 24, 'There is no fun in watching your favourite movie or show on a TV where the display quality is poor. Now, boost the fun and watch them all in good and clear-quality on this 80 cm (32) Mi smart TV.', 14499, 14499, 8, 1, '333640822_'),
(25, 'LG 108 cm', 24, 'The LG Ultra HD LED Smart TV comes with a 4K IPS display that lets you enjoy your content in its finest details.', 52990, 34999, 13, 1, '889946223_'),
(26, 'APPLE IPhone 11', 23, 'Best Phone', 49999, 55555, 9, 1, '269093208_'),
(27, 'POCO C3', 23, 'Battery life is good.', 14999, 19999, 8, 1, '169996596_');

-- --------------------------------------------------------

--
-- Table structure for table `top3`
--

CREATE TABLE `top3` (
  `customer_id` int(3) NOT NULL,
  `Total_bought` float NOT NULL,
  `Total_saved` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `top3`
--

INSERT INTO `top3` (`customer_id`, `Total_bought`, `Total_saved`) VALUES
(100, 315062, 0),
(117, 87197, 0),
(121, 93254, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `contact` bigint(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`customer_id`, `first_name`, `middle_name`, `last_name`, `contact`, `email`, `password`) VALUES
(100, 'maulik', 'm', 'm', 12, 'maulik@gmail.com', 'maulik'),
(104, 'Patel', 'raj', 'jayeshbhai', 8778651111, 'rajpatel@gmail.com', 'rajpatel'),
(108, 'A', 'B', 'C ', 1234567890, 'abc@gmail.com', 'abcdefgh'),
(110, 'a', 'a', 'a', 1222222, 'a@gmail.com', 'defesfaeafafaa'),
(113, 'abc', 'abc', 'abc', 356789242, 'gfg@gmail.com', '1234567'),
(115, 'gjg', 'jhgd', 'fds', 533266234, 'fancy@gmail.com', '1234567'),
(117, 'ghf', 'hjfd', 'fdss', 2147483647, 'duck@gmail.com', 'duck123456'),
(121, 'ghhc', 'vgtjvmg', 'thj', 458568869, 'dummy@gmail.com', '12345678'),
(122, 'ghhc', 'vvm', 'thj', 458568869, 'dummy@gmail.com', '1234567');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `email_validation` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
  IF NEW.email NOT LIKE '%@_%._%' THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email field is not valid';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `password_validation` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
  IF (length(NEW.password)) < 6 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'password shoeld have atleast 6 characters';
  
  END if;
     
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `user_del` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
#tables affected - order_details, discount, feedback, complaints, orders.
declare o_id int;
declare is_finished int DEFAULT 0;
DECLARE c1 cursor for select order_id from orders where customer_id=old.customer_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET is_finished = 1;
open c1;
get_orderid : LOOP
	fetch c1 into o_id;
    IF is_finished = 1 THEN
        LEAVE get_orderid;
    END IF;
	delete from order_details where order_id=o_id;
    delete from discount where order_id=o_id;
    delete from feedback where order_id=o_id;
    delete from complaints where order_id=o_id;
    delete from orders where order_id=o_id;
end loop get_orderid;
close c1;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaints_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `delivery_address`
--
ALTER TABLE `delivery_address`
  ADD PRIMARY KEY (`delivery_address_id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cust` (`customer_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `status` (`status`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `delivery_address_id` (`delivery_address_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_details_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `top3`
--
ALTER TABLE `top3`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaints_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `delivery_address`
--
ALTER TABLE `delivery_address`
  MODIFY `delivery_address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_details_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`customer_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `feedback_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`status`) REFERENCES `order_status` (`status_id`),
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `users` (`customer_id`),
  ADD CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`delivery_address_id`) REFERENCES `delivery_address` (`delivery_address_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `order_details_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
