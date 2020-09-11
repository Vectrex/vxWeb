-- ----------------------------
-- Records of admingroups
-- ----------------------------

INSERT INTO `admingroups` (admingroupsid, alias, `name`, privilege_level) VALUES (1, 'SUPERADMIN', 'Superadmin', 1);
INSERT INTO `admingroups` (admingroupsid, alias, `name`, privilege_level) VALUES (2, 'PRIVILEGED', 'Privileged User', 16);
INSERT INTO `admingroups` (admingroupsid, alias, `name`, privilege_level) VALUES (3, 'OBSERVE_TABLE', 'User observes table_access attributes', 256);
INSERT INTO `admingroups` (admingroupsid, alias, `name`, privilege_level) VALUES (4, 'OBSERVE_ROW', 'User observes table_access and row_access attributes', 4096);

-- ----------------------------
-- Records of articlecategories
-- ----------------------------

INSERT INTO `articlecategories` (articlecategoriesid, l, r, `level`, alias, title) VALUES (1, 0, 1, 0, 'articles', 'Articles');

-- ----------------------------
-- Records of folders
-- ----------------------------

INSERT INTO `folders` (foldersid, l, r, `level`, title, path, access) VALUES (1, 0, 1, 0, 'files', 'files/', 'RW');

-- ----------------------------
-- Records of admin
-- ----------------------------

INSERT INTO `admin` (adminid, admingroupsid, `name`, email, username, pwd) VALUES (1, 1, 'Administrator', 'admin@mail.invalid', 'admin', 'password goes here');
