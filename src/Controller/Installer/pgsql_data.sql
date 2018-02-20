-- ----------------------------
-- Records of admingroups
-- ----------------------------
INSERT INTO "admingroups" VALUES ('1', 'SUPERADMIN', 'Superadmin', '1');
INSERT INTO "admingroups" VALUES ('2', 'PRIVILEGED', 'Privileged User', '16');
INSERT INTO "admingroups" VALUES ('3', 'OBSERVE_TABLE', 'User observes table_access attributes', '256');
INSERT INTO "admingroups" VALUES ('4', 'OBSERVE_ROW', 'User observes table_access and row_access attributes', '4096');

-- ----------------------------
-- Records of articlecategories
-- ----------------------------
INSERT INTO "articlecategories" VALUES ('1', '0', '1', '0', 'articles', 'Articles', null, null, null);

-- ----------------------------
-- Records of folders
-- ----------------------------
INSERT INTO "folders" VALUES ('1', '0', '1', '0', null, 'files', null, null, 'files/', 'RW', null, null, null, null, null, null);

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO "admin" VALUES ('1', '1', 'Administrator', 'admin@mail.invalid', 'admin', '$2a$10$7e2cb875355b8d2ca21cfucPgqvPQk9XeJL03SjBP6X6AeBDCG5EO', null, null, null, CURRENT_TIMESTAMP, null);
