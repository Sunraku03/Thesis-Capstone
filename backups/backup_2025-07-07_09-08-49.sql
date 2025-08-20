

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activity_log` VALUES("2", "2", "2025-05-12 18:05:11", "2025-05-12 18:11:58");
INSERT INTO `activity_log` VALUES("3", "2", "2025-05-12 18:12:07", "2025-05-12 18:15:02");
INSERT INTO `activity_log` VALUES("4", "2", "2025-05-12 18:15:11", "2025-05-12 18:15:39");
INSERT INTO `activity_log` VALUES("5", "2", "2025-05-12 18:15:51", "2025-05-12 18:16:26");
INSERT INTO `activity_log` VALUES("6", "2", "2025-05-12 18:16:30", "2025-05-12 18:17:38");
INSERT INTO `activity_log` VALUES("7", "2", "2025-05-12 18:17:42", "2025-05-12 18:17:49");
INSERT INTO `activity_log` VALUES("8", "2", "2025-05-12 18:17:53", "2025-05-12 18:18:00");
INSERT INTO `activity_log` VALUES("9", "2", "2025-05-12 18:18:41", "2025-05-12 18:20:40");
INSERT INTO `activity_log` VALUES("10", "2", "2025-05-12 18:20:43", "2025-05-12 19:22:57");
INSERT INTO `activity_log` VALUES("11", "1", "2025-05-12 19:23:05", "2025-05-12 19:23:36");
INSERT INTO `activity_log` VALUES("12", "1", "2025-05-12 19:26:11", "2025-05-12 20:49:24");
INSERT INTO `activity_log` VALUES("14", "1", "2025-05-13 16:37:18", "2025-05-13 16:37:23");
INSERT INTO `activity_log` VALUES("15", "2", "2025-05-13 16:37:28", "2025-05-13 17:54:46");
INSERT INTO `activity_log` VALUES("16", "2", "2025-05-13 17:54:52", "2025-05-13 20:28:59");
INSERT INTO `activity_log` VALUES("28", "2", "2025-06-03 13:49:49", "2025-06-03 16:10:03");
INSERT INTO `activity_log` VALUES("29", "2", "2025-06-03 16:10:51", "2025-06-03 17:44:39");
INSERT INTO `activity_log` VALUES("30", "1", "2025-06-03 17:44:44", "2025-06-03 22:31:47");
INSERT INTO `activity_log` VALUES("35", "2", "2025-06-06 13:42:07", "2025-06-06 13:43:23");
INSERT INTO `activity_log` VALUES("36", "2", "2025-06-06 13:43:35", "2025-06-06 13:44:52");
INSERT INTO `activity_log` VALUES("41", "2", "2025-06-12 16:20:24", "2025-06-12 16:25:04");
INSERT INTO `activity_log` VALUES("42", "2", "2025-06-12 16:25:12", "2025-06-12 16:26:11");
INSERT INTO `activity_log` VALUES("43", "2", "2025-06-12 16:26:17", "2025-06-12 16:36:54");
INSERT INTO `activity_log` VALUES("44", "1", "2025-06-12 16:37:06", "2025-06-12 17:30:45");
INSERT INTO `activity_log` VALUES("45", "2", "2025-06-12 17:30:51", "2025-06-12 17:35:03");
INSERT INTO `activity_log` VALUES("46", "1", "2025-06-12 17:35:11", "2025-06-12 20:46:42");
INSERT INTO `activity_log` VALUES("47", "2", "2025-06-12 20:46:46", "2025-06-12 21:24:23");
INSERT INTO `activity_log` VALUES("49", "2", "2025-06-17 14:18:26", "2025-06-17 14:42:48");
INSERT INTO `activity_log` VALUES("50", "1", "2025-06-17 14:45:41", "2025-06-17 14:46:49");
INSERT INTO `activity_log` VALUES("54", "2", "2025-06-19 01:01:56", "2025-06-19 01:02:00");
INSERT INTO `activity_log` VALUES("55", "2", "2025-06-19 01:16:35", "2025-06-19 01:16:45");
INSERT INTO `activity_log` VALUES("56", "2", "2025-06-19 01:20:04", "2025-06-19 01:20:07");
INSERT INTO `activity_log` VALUES("57", "1", "2025-06-19 21:56:26", "2025-06-20 00:14:01");
INSERT INTO `activity_log` VALUES("60", "2", "2025-06-20 21:15:18", "2025-06-21 00:02:12");
INSERT INTO `activity_log` VALUES("61", "1", "2025-06-21 00:02:39", "2025-06-21 00:07:52");
INSERT INTO `activity_log` VALUES("62", "1", "2025-06-21 00:09:44", "2025-06-21 00:18:12");
INSERT INTO `activity_log` VALUES("63", "1", "2025-06-21 00:24:40", "2025-06-21 00:27:17");
INSERT INTO `activity_log` VALUES("66", "2", "2025-06-22 20:24:25", "2025-06-22 22:22:23");
INSERT INTO `activity_log` VALUES("68", "2", "2025-06-23 21:02:30", "2025-06-23 21:39:26");
INSERT INTO `activity_log` VALUES("69", "1", "2025-06-23 21:39:32", "2025-06-23 21:44:18");
INSERT INTO `activity_log` VALUES("70", "1", "2025-06-23 21:44:27", "2025-06-23 21:47:28");
INSERT INTO `activity_log` VALUES("71", "1", "2025-06-23 21:48:28", "2025-06-23 21:51:08");
INSERT INTO `activity_log` VALUES("72", "2", "2025-06-23 21:51:13", "2025-06-23 21:54:31");
INSERT INTO `activity_log` VALUES("73", "1", "2025-06-23 21:55:31", "2025-06-23 21:56:55");
INSERT INTO `activity_log` VALUES("74", "1", "2025-06-23 21:58:21", "2025-06-23 21:58:58");
INSERT INTO `activity_log` VALUES("75", "5", "2025-06-23 21:59:04", "2025-06-24 01:16:57");
INSERT INTO `activity_log` VALUES("76", "1", "2025-06-24 01:17:05", "2025-06-24 01:17:23");
INSERT INTO `activity_log` VALUES("78", "5", "2025-06-24 02:39:03", "2025-06-24 02:39:20");
INSERT INTO `activity_log` VALUES("80", "1", "2025-06-24 13:32:31", "NULL");
INSERT INTO `activity_log` VALUES("81", "2", "2025-06-24 13:15:17", "NULL");
INSERT INTO `activity_log` VALUES("82", "2", "2025-06-24 13:16:42", "NULL");
INSERT INTO `activity_log` VALUES("83", "1", "2025-06-24 13:18:31", "NULL");
INSERT INTO `activity_log` VALUES("84", "1", "2025-06-24 13:20:21", "NULL");
INSERT INTO `activity_log` VALUES("85", "1", "2025-06-24 13:23:06", "NULL");
INSERT INTO `activity_log` VALUES("86", "1", "2025-06-24 13:26:40", "NULL");
INSERT INTO `activity_log` VALUES("87", "1", "2025-06-24 13:29:07", "NULL");
INSERT INTO `activity_log` VALUES("88", "1", "2025-06-24 13:32:53", "NULL");
INSERT INTO `activity_log` VALUES("89", "1", "2025-06-24 13:35:30", "NULL");
INSERT INTO `activity_log` VALUES("90", "2", "2025-06-24 13:43:23", "NULL");
INSERT INTO `activity_log` VALUES("91", "1", "2025-06-24 13:54:25", "NULL");
INSERT INTO `activity_log` VALUES("92", "2", "2025-06-24 14:02:18", "2025-06-24 14:14:01");
INSERT INTO `activity_log` VALUES("93", "2", "2025-06-24 14:13:56", "NULL");
INSERT INTO `activity_log` VALUES("94", "1", "2025-06-24 14:14:13", "2025-06-24 18:47:33");
INSERT INTO `activity_log` VALUES("95", "5", "2025-06-24 14:18:12", "2025-06-24 14:20:39");
INSERT INTO `activity_log` VALUES("96", "1", "2025-06-24 14:20:29", "NULL");
INSERT INTO `activity_log` VALUES("97", "6", "2025-06-24 15:30:37", "2025-06-24 15:42:06");
INSERT INTO `activity_log` VALUES("98", "1", "2025-06-24 15:42:17", "2025-06-24 15:42:52");
INSERT INTO `activity_log` VALUES("99", "6", "2025-06-24 15:42:55", "2025-06-24 15:43:36");
INSERT INTO `activity_log` VALUES("100", "6", "2025-06-24 15:43:41", "NULL");
INSERT INTO `activity_log` VALUES("101", "6", "2025-06-25 01:03:18", "NULL");
INSERT INTO `activity_log` VALUES("102", "2", "2025-06-25 08:36:19", "2025-06-25 08:48:22");
INSERT INTO `activity_log` VALUES("103", "6", "2025-06-25 10:07:04", "NULL");
INSERT INTO `activity_log` VALUES("104", "1", "2025-06-25 14:08:03", "2025-06-25 19:10:46");
INSERT INTO `activity_log` VALUES("105", "2", "2025-06-25 14:51:06", "2025-06-25 14:52:45");
INSERT INTO `activity_log` VALUES("106", "2", "2025-06-26 08:11:01", "2025-06-26 08:18:23");
INSERT INTO `activity_log` VALUES("107", "2", "2025-06-26 08:20:29", "NULL");
INSERT INTO `activity_log` VALUES("108", "1", "2025-06-26 08:26:48", "2025-06-26 09:20:13");
INSERT INTO `activity_log` VALUES("109", "2", "2025-06-26 08:28:46", "2025-06-26 08:43:41");
INSERT INTO `activity_log` VALUES("110", "2", "2025-06-26 08:45:19", "NULL");
INSERT INTO `activity_log` VALUES("111", "1", "2025-06-27 04:49:26", "2025-06-27 05:07:43");
INSERT INTO `activity_log` VALUES("112", "7", "2025-06-27 14:25:27", "NULL");
INSERT INTO `activity_log` VALUES("113", "1", "2025-07-02 12:52:48", "2025-07-02 12:53:18");
INSERT INTO `activity_log` VALUES("114", "1", "2025-07-02 13:28:38", "2025-07-02 14:30:48");
INSERT INTO `activity_log` VALUES("115", "6", "2025-07-06 11:43:58", "2025-07-06 19:50:20");
INSERT INTO `activity_log` VALUES("116", "6", "2025-07-06 19:50:21", "NULL");
INSERT INTO `activity_log` VALUES("117", "1", "2025-07-07 07:40:00", "NULL");



CREATE TABLE `client_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `action` enum('Created','Edited','Notified') DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `client_activity_log` VALUES("1", "1", "2", "Edited", "2025-06-03 15:30:56");
INSERT INTO `client_activity_log` VALUES("2", "0", "0", "Created", "2025-06-03 15:31:15");
INSERT INTO `client_activity_log` VALUES("3", "23", "2", "Created", "2025-06-03 15:43:55");
INSERT INTO `client_activity_log` VALUES("4", "7", "2", "Edited", "2025-06-03 17:43:00");
INSERT INTO `client_activity_log` VALUES("5", "1", "2", "Edited", "2025-06-03 17:43:40");
INSERT INTO `client_activity_log` VALUES("6", "7", "1", "Edited", "2025-06-03 17:45:04");
INSERT INTO `client_activity_log` VALUES("7", "3", "1", "Edited", "2025-06-03 22:05:51");
INSERT INTO `client_activity_log` VALUES("8", "3", "1", "Edited", "2025-06-03 22:06:19");
INSERT INTO `client_activity_log` VALUES("9", "1", "1", "Edited", "2025-06-03 22:13:24");
INSERT INTO `client_activity_log` VALUES("10", "25", "1", "Created", "2025-06-03 22:40:52");
INSERT INTO `client_activity_log` VALUES("11", "1", "2", "Edited", "2025-06-03 22:50:41");
INSERT INTO `client_activity_log` VALUES("12", "4", "2", "Edited", "2025-06-06 13:44:34");
INSERT INTO `client_activity_log` VALUES("13", "1", "1", "Edited", "2025-06-08 16:03:55");
INSERT INTO `client_activity_log` VALUES("14", "1", "1", "Edited", "2025-06-08 16:04:10");
INSERT INTO `client_activity_log` VALUES("15", "19", "2", "Created", "2025-06-12 14:27:36");
INSERT INTO `client_activity_log` VALUES("16", "19", "2", "Edited", "2025-06-12 14:50:15");
INSERT INTO `client_activity_log` VALUES("17", "19", "2", "Edited", "2025-06-12 14:50:52");
INSERT INTO `client_activity_log` VALUES("18", "19", "2", "Edited", "2025-06-12 14:52:35");
INSERT INTO `client_activity_log` VALUES("19", "19", "2", "Edited", "2025-06-12 14:55:06");
INSERT INTO `client_activity_log` VALUES("20", "19", "2", "Edited", "2025-06-12 15:00:41");
INSERT INTO `client_activity_log` VALUES("21", "1", "2", "Edited", "2025-06-12 15:01:46");
INSERT INTO `client_activity_log` VALUES("22", "19", "2", "Edited", "2025-06-12 15:07:33");
INSERT INTO `client_activity_log` VALUES("23", "19", "2", "Edited", "2025-06-12 15:08:06");
INSERT INTO `client_activity_log` VALUES("24", "19", "2", "Edited", "2025-06-12 15:08:47");
INSERT INTO `client_activity_log` VALUES("25", "19", "2", "Edited", "2025-06-12 15:10:46");
INSERT INTO `client_activity_log` VALUES("26", "1", "2", "Edited", "2025-06-12 15:11:08");
INSERT INTO `client_activity_log` VALUES("27", "2", "2", "Edited", "2025-06-12 15:13:16");
INSERT INTO `client_activity_log` VALUES("28", "3", "1", "Edited", "2025-06-12 17:18:18");
INSERT INTO `client_activity_log` VALUES("29", "2", "1", "Edited", "2025-06-12 18:27:19");
INSERT INTO `client_activity_log` VALUES("30", "NULL", "1", "", "2025-06-12 20:38:07");
INSERT INTO `client_activity_log` VALUES("31", "NULL", "2", "", "2025-06-12 20:47:05");
INSERT INTO `client_activity_log` VALUES("32", "NULL", "2", "", "2025-06-12 20:52:50");
INSERT INTO `client_activity_log` VALUES("33", "NULL", "2", "", "2025-06-12 20:55:28");
INSERT INTO `client_activity_log` VALUES("34", "NULL", "2", "", "2025-06-12 21:02:35");
INSERT INTO `client_activity_log` VALUES("35", "NULL", "2", "", "2025-06-12 21:16:07");
INSERT INTO `client_activity_log` VALUES("36", "NULL", "2", "", "2025-06-12 21:20:11");
INSERT INTO `client_activity_log` VALUES("37", "NULL", "1", "", "2025-06-12 21:24:48");
INSERT INTO `client_activity_log` VALUES("38", "NULL", "1", "", "2025-06-12 21:25:01");
INSERT INTO `client_activity_log` VALUES("39", "NULL", "1", "", "2025-06-12 21:26:15");
INSERT INTO `client_activity_log` VALUES("40", "1", "2", "Edited", "2025-06-17 14:20:23");
INSERT INTO `client_activity_log` VALUES("41", "20", "2", "Created", "2025-06-17 14:24:26");
INSERT INTO `client_activity_log` VALUES("42", "19", "2", "Edited", "2025-06-17 14:26:17");
INSERT INTO `client_activity_log` VALUES("43", "1", "2", "Edited", "2025-06-17 14:27:15");
INSERT INTO `client_activity_log` VALUES("44", "2", "2", "Edited", "2025-06-17 14:27:28");
INSERT INTO `client_activity_log` VALUES("45", "20", "2", "Edited", "2025-06-17 14:27:48");
INSERT INTO `client_activity_log` VALUES("46", "NULL", "2", "", "2025-06-17 14:28:11");
INSERT INTO `client_activity_log` VALUES("47", "21", "1", "Created", "2025-06-18 00:42:33");
INSERT INTO `client_activity_log` VALUES("48", "23", "1", "Created", "2025-06-18 00:47:17");
INSERT INTO `client_activity_log` VALUES("49", "24", "1", "Created", "2025-06-18 00:48:16");
INSERT INTO `client_activity_log` VALUES("50", "25", "1", "Created", "2025-06-18 00:49:51");
INSERT INTO `client_activity_log` VALUES("51", "26", "1", "Created", "2025-06-18 00:52:04");
INSERT INTO `client_activity_log` VALUES("52", "27", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("53", "28", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("54", "29", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("55", "30", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("56", "31", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("57", "32", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("58", "33", "1", "Created", "2025-06-18 00:54:26");
INSERT INTO `client_activity_log` VALUES("59", "34", "1", "Created", "2025-06-18 00:56:30");
INSERT INTO `client_activity_log` VALUES("60", "35", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("61", "36", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("62", "37", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("63", "38", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("64", "39", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("65", "40", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("66", "41", "1", "Created", "2025-06-18 01:02:36");
INSERT INTO `client_activity_log` VALUES("67", "42", "1", "Created", "2025-06-18 01:06:54");
INSERT INTO `client_activity_log` VALUES("68", "43", "1", "Created", "2025-06-18 01:15:09");
INSERT INTO `client_activity_log` VALUES("69", "44", "1", "Created", "2025-06-18 01:15:09");
INSERT INTO `client_activity_log` VALUES("70", "45", "1", "Created", "2025-06-18 01:18:46");
INSERT INTO `client_activity_log` VALUES("76", "1", "1", "Edited", "2025-06-19 23:55:09");
INSERT INTO `client_activity_log` VALUES("77", "2", "1", "Edited", "2025-06-19 23:56:06");
INSERT INTO `client_activity_log` VALUES("78", "51", "2", "Created", "2025-06-20 18:29:11");
INSERT INTO `client_activity_log` VALUES("79", "1", "2", "Edited", "2025-06-20 18:37:32");
INSERT INTO `client_activity_log` VALUES("80", "51", "2", "Edited", "2025-06-20 18:42:40");
INSERT INTO `client_activity_log` VALUES("81", "1", "2", "Edited", "2025-06-20 18:42:55");
INSERT INTO `client_activity_log` VALUES("82", "12", "2", "Edited", "2025-06-20 18:47:32");
INSERT INTO `client_activity_log` VALUES("83", "2", "2", "Edited", "2025-06-20 18:50:43");
INSERT INTO `client_activity_log` VALUES("84", "21", "2", "Created", "2025-06-20 21:49:34");
INSERT INTO `client_activity_log` VALUES("85", "22", "2", "Created", "2025-06-20 21:49:34");
INSERT INTO `client_activity_log` VALUES("86", "23", "2", "Created", "2025-06-20 21:50:59");
INSERT INTO `client_activity_log` VALUES("87", "24", "2", "Created", "2025-06-20 21:50:59");
INSERT INTO `client_activity_log` VALUES("88", "25", "2", "Created", "2025-06-20 21:55:57");
INSERT INTO `client_activity_log` VALUES("89", "26", "2", "Created", "2025-06-20 21:55:57");
INSERT INTO `client_activity_log` VALUES("90", "21", "2", "Edited", "2025-06-20 22:05:55");
INSERT INTO `client_activity_log` VALUES("91", "2", "2", "Edited", "2025-06-20 22:10:11");
INSERT INTO `client_activity_log` VALUES("92", "3", "2", "Edited", "2025-06-20 22:11:50");
INSERT INTO `client_activity_log` VALUES("93", "4", "2", "Edited", "2025-06-20 22:13:38");
INSERT INTO `client_activity_log` VALUES("94", "4", "2", "Edited", "2025-06-20 22:14:10");
INSERT INTO `client_activity_log` VALUES("95", "4", "2", "Edited", "2025-06-20 22:15:42");
INSERT INTO `client_activity_log` VALUES("96", "4", "2", "Edited", "2025-06-20 22:18:26");
INSERT INTO `client_activity_log` VALUES("97", "4", "2", "Edited", "2025-06-20 22:23:40");
INSERT INTO `client_activity_log` VALUES("98", "5", "2", "Edited", "2025-06-20 22:31:04");
INSERT INTO `client_activity_log` VALUES("99", "9", "2", "Edited", "2025-06-20 22:35:38");
INSERT INTO `client_activity_log` VALUES("101", "2", "2", "Edited", "2025-06-20 23:44:26");
INSERT INTO `client_activity_log` VALUES("111", "1", "2", "Edited", "2025-06-22 22:34:49");
INSERT INTO `client_activity_log` VALUES("112", "23", "2", "Created", "2025-06-23 01:04:48");
INSERT INTO `client_activity_log` VALUES("113", "23", "2", "Edited", "2025-06-23 01:08:14");
INSERT INTO `client_activity_log` VALUES("114", "24", "2", "Created", "2025-06-23 03:35:30");
INSERT INTO `client_activity_log` VALUES("115", "25", "2", "Created", "2025-06-23 03:35:30");
INSERT INTO `client_activity_log` VALUES("116", "26", "2", "Created", "2025-06-23 03:38:50");
INSERT INTO `client_activity_log` VALUES("117", "27", "2", "Created", "2025-06-23 03:38:50");
INSERT INTO `client_activity_log` VALUES("118", "28", "2", "Created", "2025-06-23 03:41:04");
INSERT INTO `client_activity_log` VALUES("119", "29", "2", "Created", "2025-06-23 03:41:04");
INSERT INTO `client_activity_log` VALUES("120", "30", "2", "Created", "2025-06-23 03:41:36");
INSERT INTO `client_activity_log` VALUES("121", "32", "2", "Created", "2025-06-23 03:41:59");
INSERT INTO `client_activity_log` VALUES("122", "33", "2", "Created", "2025-06-23 03:41:59");
INSERT INTO `client_activity_log` VALUES("123", "34", "2", "Created", "2025-06-23 03:44:40");
INSERT INTO `client_activity_log` VALUES("124", "35", "2", "Created", "2025-06-23 03:44:40");
INSERT INTO `client_activity_log` VALUES("125", "36", "2", "Created", "2025-06-23 03:46:42");
INSERT INTO `client_activity_log` VALUES("126", "37", "2", "Created", "2025-06-23 03:46:42");
INSERT INTO `client_activity_log` VALUES("127", "13", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("128", "14", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("129", "15", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("130", "16", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("131", "17", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("132", "18", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("133", "19", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("134", "20", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("135", "21", "2", "Created", "2025-06-23 21:19:25");
INSERT INTO `client_activity_log` VALUES("136", "9", "2", "Edited", "2025-06-23 21:20:34");
INSERT INTO `client_activity_log` VALUES("137", "7", "2", "Edited", "2025-06-23 21:25:18");
INSERT INTO `client_activity_log` VALUES("138", "7", "2", "Edited", "2025-06-23 21:25:46");
INSERT INTO `client_activity_log` VALUES("139", "8", "2", "Edited", "2025-06-23 21:27:00");
INSERT INTO `client_activity_log` VALUES("140", "9", "5", "Edited", "2025-06-23 22:58:20");
INSERT INTO `client_activity_log` VALUES("141", "2", "5", "Edited", "2025-06-23 22:58:54");
INSERT INTO `client_activity_log` VALUES("142", "NULL", "5", "Notified", "2025-06-23 22:59:09");
INSERT INTO `client_activity_log` VALUES("143", "8", "5", "Edited", "2025-06-23 23:51:17");
INSERT INTO `client_activity_log` VALUES("144", "9", "5", "Edited", "2025-06-23 23:51:57");
INSERT INTO `client_activity_log` VALUES("145", "12", "2", "Edited", "2025-06-24 01:32:28");
INSERT INTO `client_activity_log` VALUES("146", "1", "2", "Edited", "2025-06-24 02:40:06");
INSERT INTO `client_activity_log` VALUES("149", "7", "2", "Notified", "2025-06-24 03:04:21");
INSERT INTO `client_activity_log` VALUES("150", "7", "2", "Notified", "2025-06-24 03:11:48");
INSERT INTO `client_activity_log` VALUES("151", "2", "2", "Notified", "2025-06-24 03:20:48");
INSERT INTO `client_activity_log` VALUES("153", "NULL", "2", "Notified", "2025-06-24 03:28:18");
INSERT INTO `client_activity_log` VALUES("154", "NULL", "2", "Notified", "2025-06-24 03:33:32");
INSERT INTO `client_activity_log` VALUES("155", "NULL", "2", "Notified", "2025-06-24 03:36:16");
INSERT INTO `client_activity_log` VALUES("156", "NULL", "2", "Notified", "2025-06-24 03:41:05");
INSERT INTO `client_activity_log` VALUES("157", "2", "2", "Notified", "2025-06-24 03:41:16");
INSERT INTO `client_activity_log` VALUES("158", "7", "2", "Notified", "2025-06-24 03:41:51");
INSERT INTO `client_activity_log` VALUES("159", "2", "1", "Edited", "2025-06-24 13:52:54");
INSERT INTO `client_activity_log` VALUES("160", "NULL", "2", "Notified", "2025-06-24 14:13:05");
INSERT INTO `client_activity_log` VALUES("161", "8", "1", "Edited", "2025-06-24 14:19:09");
INSERT INTO `client_activity_log` VALUES("162", "1", "6", "Edited", "2025-06-24 15:36:29");
INSERT INTO `client_activity_log` VALUES("163", "7", "6", "Edited", "2025-06-24 15:41:42");
INSERT INTO `client_activity_log` VALUES("164", "22", "2", "Created", "2025-06-26 08:12:31");
INSERT INTO `client_activity_log` VALUES("165", "22", "2", "Notified", "2025-06-26 08:12:50");
INSERT INTO `client_activity_log` VALUES("166", "1", "1", "Edited", "2025-07-07 08:59:35");



CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `vehicle_unit` varchar(100) DEFAULT NULL,
  `chasis_no` varchar(100) DEFAULT NULL,
  `motor_no` varchar(100) DEFAULT NULL,
  `plate_no` varchar(50) DEFAULT NULL,
  `vehicle_color` varchar(255) DEFAULT NULL,
  `amount_insured` decimal(15,2) DEFAULT NULL,
  `bipd` decimal(10,2) DEFAULT NULL,
  `pa` decimal(10,2) DEFAULT NULL,
  `aon` decimal(10,2) DEFAULT NULL,
  `net_remitting` decimal(10,2) DEFAULT NULL,
  `hkbc_net` decimal(10,2) DEFAULT NULL,
  `mark_up` decimal(10,2) DEFAULT NULL,
  `late_charges` decimal(10,2) DEFAULT NULL,
  `cancelled_income` decimal(10,2) DEFAULT NULL,
  `reinstatement_fee` decimal(10,2) DEFAULT NULL,
  `make_up_agent` decimal(10,2) DEFAULT NULL,
  `comission` decimal(10,2) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `mortgage` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `or_number` decimal(10,2) DEFAULT NULL,
  `payment_1` decimal(10,2) DEFAULT NULL,
  `payment_2` decimal(10,2) DEFAULT NULL,
  `payment_3` decimal(10,2) DEFAULT NULL,
  `payment_4` decimal(10,2) DEFAULT NULL,
  `payment_5` decimal(10,2) DEFAULT NULL,
  `payment_6` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(100) DEFAULT NULL,
  `date_remittance` date DEFAULT NULL,
  `ctpl` varchar(100) DEFAULT NULL,
  `bank_status` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `old_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cliente` VALUES("1", "HKBC01562348966", "John Kelvin L. Soriano", "Standard Insurance", "John Kelvin Soriano", "0999-152-7435", "sorianovinz5680@gmail.com", "Blk 18 Lot 7 CHRV Langkaan 2 Dasmarinas Cavite", "2025-05-16", "2025-06-20", "", "", "", "", "NULL", "1000000.00", "0.00", "0.00", "0.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "10000.00", "100.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "68580dd88c378_kelvin 1v1.jpg", "1", "0000-00-00 00:00:00", "1");
INSERT INTO `cliente` VALUES("2", "HKBC03662348495", "Charissa Makasiar", "Standard Insurance", "Charissa Makasiar", "", "vinzsoriano5680@gmail.com", "Blk 16 Lot 2 CHRV Langkaan 2 Dasmarinas Cavite", "2025-05-03", "2025-06-26", "MAZDALVL3", "SDFUU982", "17489564", "DFG651", "", "80000.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Inactive", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "1222140_13.jpg", "2", "0000-00-00 00:00:00", "0");
INSERT INTO `cliente` VALUES("3", "HKBC06252349894", "Shiera Mae Bancud", "Standard Insurance", "Shi De Sixto", "0939-254-7495", "kelvin032003@gmail.com", "Blk 35 Lot 2 CHRV Langkaan 2 Dasmarinas Cavite", "2025-03-18", "2025-07-17", "", "", "", "", "NULL", "85000.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "3", "0000-00-00 00:00:00", "1");
INSERT INTO `cliente` VALUES("4", "HKBC06668135995", "Johnny Silverhand", "Standard Insurance", "Johnny Silverhand", "0966-986-8965", "211904soriano@gmail.com", "Apt. 724 589 Senger Trafficway, East Sharice, Night City", "2025-05-28", "2025-08-12", "", "", "", "", "NULL", "207700.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "cyberpunk-2077-johnny-silverhand-g81-mal.jpg", "4", "0000-00-00 00:00:00", "1");
INSERT INTO `cliente` VALUES("5", "HKBC06168135954", "Peter Parker", "Standard Insurance", "Peter Parker", "0948-592-6835", "kenneth02162010@gmail.com", "15th Street, Queens, New York City, New York", "2025-06-03", "2025-08-03", "MAZDALVL4", "SDFFS645", "1563366", "GYJ632", "NULL", "120000.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "gihoMoUEVqMncFN75QvKUm-1200-80.jpg", "6", "0000-00-00 00:00:00", "1");
INSERT INTO `cliente` VALUES("6", "HKBC06528135985", "Taylor Swift", "Standard Insurance", "Taylor Swift", "0998-368-8985", "thunderbolts0T5@gmail.com", "Blk 35 Lot 65 CHRV Langkaan 2 Dasmarinas Cavite", "2025-02-13", "2025-05-24", "", "", "", "", "NULL", "200000.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Inactive", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "10", "0000-00-00 00:00:00", "0");
INSERT INTO `cliente` VALUES("7", "HKBC01562654856", "Arthur Morgan", "Standard Insurance", "", "", "jericklucena15@gmail.com", "Apt. 257 10994 Stark Key, Hackettfort, MA 19017-0959", "2024-12-24", "2025-06-26", "MAZDA", "", "", "", "", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "18", "0000-00-00 00:00:00", "1");
INSERT INTO `cliente` VALUES("8", "HKBC09868138453", "John Carlo Tingzon", "Standard Insurance", "John Carlo Tingzon", "", "kelvinlucena@yahoo.com", "Apt. 257 10994 Stark Key, Hackettfort, MA 19017-0648", "2025-06-06", "2025-07-26", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Inactive", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "6859562425610_genshin-impact-ajaw.png", "19", "0000-00-00 00:00:00", "0");
INSERT INTO `cliente` VALUES("9", "HKBC06868145015", "Josh Wencel Esar", "Standard Insurance", "Josh Wencel Esar", "", "aisat.maisabelorcales@gmail.com", "595 Eldora Cape, East Scottie, WY 47449", "2025-06-17", "2025-07-24", "", "", "", "", "", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "0", "20", "0000-00-00 00:00:00", "1");
INSERT INTO `cliente` VALUES("10", "HKBC02662335968", "Renz Miranda", "Standard Insurance", "Renz Miranda", "0956-586-5965", "gananrenz16@gmail.com", "Blk 26 Lot 6 CHRV Langkaan 2 Dasmarinas Cavite", "2025-06-20", "2025-10-17", "MAZDALVL1", "MQLUP132", "15628564", "MLQ465", "NULL", "50000.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Active", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "0", "NULL", "NULL", "1");
INSERT INTO `cliente` VALUES("11", "HKBC09662295468", "John Marston", "Standard Insurance", "John Marston", "0976-956-5595", "lamban978@gmail.com", "Cavite", "2025-06-03", "2025-06-20", "MAZDALVL3", "SDASP942", "16848564", "ASD213", "NULL", "60000.00", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "Inactive", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "NULL", "0000-00-00 00:00:00", "0");
INSERT INTO `cliente` VALUES("12", "HKBC03963663598", "Juan Dela Cruz", "AXA Philippines", "juandelacruz", "09171234567", "juan@gmail.com", "Taguig City", "2025-01-01", "2025-12-31", "Toyota Vios", "CHS123456", "MTR789012", "ABC123", "Red", "250000.00", "50000.00", "30000.00", "15000.00", "200000.00", "180000.00", "5000.00", "1000.00", "0.00", "0.00", "0.00", "15000.00", "Referral", "BPI", "Active", "81621662.00", "2000.00", "2000.00", "2000.00", "2000.00", "2000.00", "2000.00", "Paid", "2025-02-22", "Yes", "Released", "No issues", "NULL", "NULL", "2025-06-23 03:46:42", "1");
INSERT INTO `cliente` VALUES("13", "HKBC-38562-91427", "PEDRO ANTONIO REYES", "STANDARD INSURANCE", "", "0922 100 2020", "Sintomata19@gmail.com", "123 MABINI ST., BARANGAY SAN ISIDRO, QUEZON CITY, METRO MANILA, 1101", "2025-06-06", "2026-06-06", "2020 TOYOTA VIOS 1.3 E AT", "TV1E1234567890123", "1NRFE1234567", "NAB 1234", "SILVER METALLIC", "650.00", "100.00", "100.00", "80.00", "11.00", "16.00", "5.00", "0.00", "0.00", "0.00", "17.00", "1.00", "JUAN DELA CRUZ / NEW CLIENT", "BPI", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("14", "HKBC-12749-60381", "KAREN DEL ROSARIO", "STANDARD INSURANCE", "", "0998 765 4321", "ajd92002@gmail.com", "45 SAMPAGUITA LANE, BRGY. MALAYA, ANTIPOLO CITY, RIZAL, 1870", "2025-06-08", "2026-08-06", "2019 MITSUBISHI L300 FB EXCEED", "ML3FB987654321000", "4D56FB0987654", "CAV 5678", "WHITE PEARL", "1.00", "200.00", "75.00", "200.00", "9.00", "13.00", "3.00", "0.00", "0.00", "0.00", "14.00", "1.00", "MARIA SANTOS / REPEAT CLIENT", "NIL", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("15", "HKBC-84213-57609", "ANNA ISABEL LORENZO", "STANDARD INSURANCE", "", "0916 777 8888", "tingzon.johncarlo@gmail.com", "76 KALAYAAN AVENUE, BRGY. TANDANG SORA, QUEZON CITY, METRO MANILA, 1106", "2025-06-08", "2026-08-06", "2020 SUZUKI ERTIGA GA MT", "HC125A2022X567890", "ESP125A567890", "NDH 6789", "BLACK MICA", "720.00", "100.00", "100.00", "130.00", "12.00", "10.00", "0.00", "0.00", "0.00", "0.00", "20.00", "1.00", "JOSEPH TAN / REPEAT CLIENT", "NIL", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("16", "HKBC-90481-23765", "LIZA RAMOS MANALO", "PHIL.BRITISH", "", "0906 234 5678", "aisatdejesus211893@gmail.com", "18 MAGSAYSAY BLVD., BRGY. BAGONG BUHAY, SAN FERNANDO, PAMPANGA, 2000", "2025-06-08", "2026-08-06", "2022 TOYOTA HILUX 2.4 G DSL 4X2", "HA14GLX2021B00123", "G4LC014785236", "DAA 4321", "GRAY METALLIC", "850.00", "100.00", "100.00", "200.00", "10.00", "13.00", "3.00", "0.00", "0.00", "0.00", "14.00", "1.00", "JENIFER / REPEAT CLIENT", "EASTWEST BANK", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("17", "HKBC-76301-19842", "JAMES RODRIGO CASTRO", "PHIL.BRITISH", "", "0918 555 0000", "212722acebron@gmail.com", "221 RIZAL AVE., BRGY. SAN VICENTE, CALAMBA CITY, LAGUNA, 4027", "2025-06-10", "2026-10-06", "2021 ISUZU D-MAX LS 4X4 MT", "SEGA2020MT0017890", "K14BMT0098765", "NEM 1122", "RED METALLIC", "900.00", "150.00", "100.00", "250.00", "8.00", "11.00", "3.00", "0.00", "0.00", "0.00", "12.00", "888.00", "ANNA LORENZO / NEW CLIENT", "BDO", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("18", "HKBC-51209-74418", "NICOLE ANGELA CRUZ", "STANDARD INSURANCE", "", "0917 321 9999", "eadriano357@gmail.com", "LOT 5 BLOCK 3, SITIO KABAYAN, BRGY. SILANGAN, TAGUIG CITY, METRO MANILA, 1630", "2025-06-11", "2026-11-06", "2020 KIA K2500 KARGA", "YM125I23X09871234", "YM125M2345678", "YAA 9988", "MATTE BLACK", "1.00", "250.00", "120.00", "60.00", "8.00", "11.00", "3.00", "0.00", "0.00", "0.00", "19.00", "1.00", "PEDRO REYES / OFFICE SALE", "NIL", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("19", "HKBC-33046-88590", "MA. CECILIA ANGELES", "STANDARD INSURANCE", "", "0915 223 3344", "shunsensei11@gmail.com", "BLK 10 LOT 12, GREENFIELDS SUBDIVISION, BRGY. STO. NIÑO, MARIKINA CITY, 1800", "2025-06-11", "2026-11-06", "2022 NISSAN NAVARA EL CALIBRE", "TH24GDSL220987654", "2GDFTV6543219", "RAB 3344", "DARK BLUE MICA", "480.00", "200.00", "150.00", "50.00", "6.00", "9.00", "2.00", "0.00", "0.00", "0.00", "16.00", "1.00", "CHRISTINE GOMEZ / NEW CLIENT", "PNB", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("20", "HKBC-29187-63024", "BEA FRANCISCO", "STANDARD INSURANCE", "", "0956 789 0123", "kelvin.gates@gmail.com", "15 NATIONAL ROAD, BRGY. LUCAP, ALAMINOS CITY, PANGASINAN, 2404", "2025-06-11", "2026-11-06", "2023 FORD RANGER XLT 2.0 4X2 AT", "IDMLS4X4M12345678", "4JJ1TCX987654", "TGA 7733", "GUN METAL GRAY", "1.00", "200.00", "30.00", "100.00", "5.00", "7.00", "1.00", "0.00", "0.00", "0.00", "14.00", "1.00", "MARIA IZA ROSALES / REPEAT CLIENT", "BPI", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("21", "HKBC-40015-72183", "JULIUS VERGARA", "PHIL.BRITISH", "", "0907 101 6767", "concepcionmicaelayadao@gmail.com", "UNIT 2A, 3RD FLOOR, MANILA TOWER, BRGY. ERMITA, MANILA, METRO MANILA, 1000", "2025-06-12", "2026-12-06", "2018 CHEVROLET TRAILBLAZER LT 4X2 AT", "K2500KRGA20209999", "D4CB56781234", "AAB 4488", "FOREST GREEN", "1.00", "200.00", "75.00", "250.00", "10.00", "13.00", "3.00", "0.00", "0.00", "0.00", "13.00", "1.00", "ANECIA PADILLA / REPEAT CLIENT", "BPI", "Active", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "NULL", "NULL", "2025-06-23 21:19:25", "1");
INSERT INTO `cliente` VALUES("22", "123456789", "Marvin Tamayo", "Tester", "marvintamayo21@test.com", "0193998227", "marvintamayo022@gmail.com", "blk test lot test baranggay santa test 2", "2025-06-26", "2025-06-26", "5", "5", "5", "5", "5", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "", "", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "0.00", "", "0000-00-00", "", "", "", "", "NULL", "2025-06-26 08:12:31", "0");



CREATE TABLE `email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_to` text DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `email_log` VALUES("1", "211904soriano@gmail.com,concepcionmicaelayadao@gmail.com,kelvin.gates@gmail.com,kelvin032003@gmail.com,kenneth02162010@gmail.com,lamban978@gmail.com,sorianovinz5680@gmail.com,vinzsoriano5680@gmail.com", "0", "2025-06-03 20:49:03");
INSERT INTO `email_log` VALUES("2", "211904soriano@gmail.com,concepcionmicaelayadao@gmail.com,kelvin.gates@gmail.com,kelvin032003@gmail.com,kenneth02162010@gmail.com,lamban978@gmail.com,sorianovinz5680@gmail.com,vinzsoriano5680@gmail.com", "0", "2025-06-03 21:00:41");
INSERT INTO `email_log` VALUES("3", "211904soriano@gmail.com,concepcionmicaelayadao@gmail.com,kelvin.gates@gmail.com,kelvin032003@gmail.com,kenneth02162010@gmail.com,lamban978@gmail.com,sorianovinz5680@gmail.com,vinzsoriano5680@gmail.com", "0", "2025-06-03 21:08:38");
INSERT INTO `email_log` VALUES("4", "211904soriano@gmail.com,concepcionmicaelayadao@gmail.com,kelvin.gates@gmail.com,kelvin032003@gmail.com,kenneth02162010@gmail.com,lamban978@gmail.com,sorianovinz5680@gmail.com,vinzsoriano5680@gmail.com", "0", "2025-06-03 21:10:42");
INSERT INTO `email_log` VALUES("5", "211904soriano@gmail.com,concepcionmicaelayadao@gmail.com,kelvin.gates@gmail.com,kelvin032003@gmail.com,kenneth02162010@gmail.com,lamban978@gmail.com,sorianovinz5680@gmail.com,vinzsoriano5680@gmail.com", "0", "2025-06-03 21:47:38");
INSERT INTO `email_log` VALUES("6", "kelvin032003@gmail.com", "0", "2025-06-03 22:06:01");
INSERT INTO `email_log` VALUES("7", "kelvin032003@gmail.com", "0", "2025-06-03 22:06:37");
INSERT INTO `email_log` VALUES("8", "kelvin032003@gmail.com", "0", "2025-06-03 22:10:23");
INSERT INTO `email_log` VALUES("9", "sorianovinz5680@gmail.com,kelvin032003@gmail.com", "0", "2025-06-03 22:13:36");
INSERT INTO `email_log` VALUES("10", "sorianovinz5680@gmail.com,kelvin032003@gmail.com", "0", "2025-06-03 22:22:44");
INSERT INTO `email_log` VALUES("11", "sorianovinz5680@gmail.com,kelvin032003@gmail.com", "0", "2025-06-03 22:30:01");
INSERT INTO `email_log` VALUES("12", "sorianovinz5680@gmail.com,kelvin032003@gmail.com", "0", "2025-06-05 15:33:55");
INSERT INTO `email_log` VALUES("13", "sorianovinz5680@gmail.com,kelvin032003@gmail.com", "0", "2025-06-05 15:35:27");
INSERT INTO `email_log` VALUES("14", "sorianovinz5680@gmail.com,kelvin032003@gmail.com", "0", "2025-06-05 15:36:03");
INSERT INTO `email_log` VALUES("15", "211904soriano@gmail.com", "0", "2025-06-12 17:12:14");
INSERT INTO `email_log` VALUES("16", "kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 17:18:35");
INSERT INTO `email_log` VALUES("17", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 18:27:35");
INSERT INTO `email_log` VALUES("18", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 18:27:35");
INSERT INTO `email_log` VALUES("19", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 18:33:01");
INSERT INTO `email_log` VALUES("20", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 19:51:58");
INSERT INTO `email_log` VALUES("21", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 20:32:19");
INSERT INTO `email_log` VALUES("22", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 20:38:07");
INSERT INTO `email_log` VALUES("23", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 20:47:05");
INSERT INTO `email_log` VALUES("24", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 20:52:50");
INSERT INTO `email_log` VALUES("25", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 20:55:28");
INSERT INTO `email_log` VALUES("26", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:02:35");
INSERT INTO `email_log` VALUES("27", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:10:22");
INSERT INTO `email_log` VALUES("28", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:14:48");
INSERT INTO `email_log` VALUES("29", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:16:07");
INSERT INTO `email_log` VALUES("30", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:20:11");
INSERT INTO `email_log` VALUES("31", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:24:48");
INSERT INTO `email_log` VALUES("32", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:25:01");
INSERT INTO `email_log` VALUES("33", "vinzsoriano5680@gmail.com,kelvin032003@gmail.com,211904soriano@gmail.com", "0", "2025-06-12 21:26:15");
INSERT INTO `email_log` VALUES("34", "sorianovinz5680@gmail.com,vinzsoriano5680@gmail.com,kelvin032003@gmail.com,aisat.maisabelorcales@gmail.com", "0", "2025-06-17 14:28:11");
INSERT INTO `email_log` VALUES("35", "sorianovinz5680@gmail.com,jericklucena15@gmail.com,aisat.maisabelorcales@gmail.com,lamban978@gmail.com", "0", "2025-06-20 23:20:29");
INSERT INTO `email_log` VALUES("36", "vinzsoriano5680@gmail.com,jericklucena15@gmail.com", "0", "2025-06-23 22:59:09");
INSERT INTO `email_log` VALUES("37", "vinzsoriano5680@gmail.com,jericklucena15@gmail.com", "0", "2025-06-24 02:40:33");
INSERT INTO `email_log` VALUES("38", "vinzsoriano5680@gmail.com", "0", "2025-06-24 02:48:45");
INSERT INTO `email_log` VALUES("39", "vinzsoriano5680@gmail.com", "0", "2025-06-24 02:58:32");
INSERT INTO `email_log` VALUES("40", "vinzsoriano5680@gmail.com,jericklucena15@gmail.com", "0", "2025-06-24 03:21:02");
INSERT INTO `email_log` VALUES("41", "vinzsoriano5680@gmail.com,jericklucena15@gmail.com", "0", "2025-06-24 03:28:18");
INSERT INTO `email_log` VALUES("42", "vinzsoriano5680@gmail.com,jericklucena15@gmail.com", "0", "2025-06-24 03:33:32");
INSERT INTO `email_log` VALUES("43", "vinzsoriano5680@gmail.com,jericklucena15@gmail.com", "0", "2025-06-24 03:36:16");
INSERT INTO `email_log` VALUES("44", "", "0", "2025-06-24 03:41:05");
INSERT INTO `email_log` VALUES("45", "", "0", "2025-06-24 14:13:05");



CREATE TABLE `payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'PHP',
  PRIMARY KEY (`id`),
  KEY `payment_history_ibfk_1` (`client_id`),
  CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `payment_history` VALUES("1", "1", "1500.00", "2025-06-11", "Cash", "", "PHP");
INSERT INTO `payment_history` VALUES("2", "1", "1200.00", "2025-07-26", "Bank Transfer", "", "PHP");
INSERT INTO `payment_history` VALUES("6", "2", "6000.00", "2025-06-18", "Cash", "", "PHP");



CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES("1", "admin", "password", "admin");
INSERT INTO `users` VALUES("2", "Kelvin", "0987654", "staff");
INSERT INTO `users` VALUES("5", "Charissa", "0123456", "staff");
INSERT INTO `users` VALUES("6", "Shiera", "0654987", "staff");
INSERT INTO `users` VALUES("7", "Micaela", "0654321", "staff");
INSERT INTO `users` VALUES("8", "admin", "password", "staff");

