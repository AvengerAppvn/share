<!-- BADGES/ -->

[![Build Status](https://travis-ci.org/AvengerAppvn/share.svg?branch=master)](https://travis-ci.org/AvengerAppvn/share)

<!-- /BADGES -->

START php console/yii app/setup
- Get rate -> save to key_storage_item
- 30 minute run to rate
- ALTER TABLE  `user_profile` CHANGE  `country_id`  `country_id` INT( 11 ) NULL DEFAULT  '231';
ALTER TABLE `user` CHANGE `access_token` `access_token` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
