ALTER TABLE  `zt_task` CHANGE  `status`  `status` ENUM(  'wait',  'doing',  'done',  'cancel',  'closed',  'pause' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'wait'