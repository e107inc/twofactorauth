/*
 * TwoFactorAuth
 *
 * Copyright (C) 2021-2022 e107 Inc. (https://www.e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 */


CREATE TABLE twofactorauth (
  `user_id` int(10) NOT NULL,
  `secret_key` varchar(255) NOT NULL
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM;