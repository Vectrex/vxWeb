/*
Navicat PGSQL Data Transfer

Source Server         : localhost
Source Server Version : 90506
Source Host           : localhost:5432
Source Database       : vxweb
Source Schema         : public

Target Server Type    : PGSQL
Target Server Version : 90506
File Encoding         : 65001

Date: 2017-03-10 11:40:32
*/


-- ----------------------------
-- Sequence structure for admin_adminid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "admin_adminid_seq";
CREATE SEQUENCE "admin_adminid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."admin_adminid_seq"', 13, true);

-- ----------------------------
-- Sequence structure for admingroups_admingroupsid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "admingroups_admingroupsid_seq";
CREATE SEQUENCE "admingroups_admingroupsid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for articlecategories_articlecategoriesid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "articlecategories_articlecategoriesid_seq";
CREATE SEQUENCE "articlecategories_articlecategoriesid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."articlecategories_articlecategoriesid_seq"', 1, true);

-- ----------------------------
-- Sequence structure for articles_articlesid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "articles_articlesid_seq";
CREATE SEQUENCE "articles_articlesid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."articles_articlesid_seq"', 3, true);

-- ----------------------------
-- Sequence structure for files_filesid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "files_filesid_seq";
CREATE SEQUENCE "files_filesid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."files_filesid_seq"', 4, true);

-- ----------------------------
-- Sequence structure for folders_foldersid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "folders_foldersid_seq";
CREATE SEQUENCE "folders_foldersid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 2
 CACHE 1;
SELECT setval('"public"."folders_foldersid_seq"', 6, true);

-- ----------------------------
-- Sequence structure for notifications_notificationsid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "notifications_notificationsid_seq";
CREATE SEQUENCE "notifications_notificationsid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;

-- ----------------------------
-- Sequence structure for pages_pagesid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "pages_pagesid_seq";
CREATE SEQUENCE "pages_pagesid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."pages_pagesid_seq"', 4, true);

-- ----------------------------
-- Sequence structure for revisions_revisionsid_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "revisions_revisionsid_seq";
CREATE SEQUENCE "revisions_revisionsid_seq"
 INCREMENT 1
 MINVALUE 1
 MAXVALUE 9223372036854775807
 START 1
 CACHE 1;
SELECT setval('"public"."revisions_revisionsid_seq"', 4, true);

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS "admin";
CREATE TABLE "admin" (
"adminid" int4 DEFAULT nextval('admin_adminid_seq'::regclass) NOT NULL,
"admingroupsid" int4,
"name" varchar(64) COLLATE "default" DEFAULT 'Max Mustermann'::character varying,
"email" varchar(256) COLLATE "default" DEFAULT NULL::character varying,
"username" varchar(256) COLLATE "default" DEFAULT NULL::character varying,
"pwd" varchar(256) COLLATE "default" DEFAULT NULL::character varying,
"misc_data" varchar(510) COLLATE "default" DEFAULT NULL::character varying,
"table_access" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"row_access" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"lastupdated" timestamp(6) DEFAULT now() NOT NULL,
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of admin
-- ----------------------------
BEGIN;
INSERT INTO "admin" VALUES ('1', '1', 'Administrator', 'admin@mail.invalid', 'admin', '$2a$10$7e2cb875355b8d2ca21cfucPgqvPQk9XeJL03SjBP6X6AeBDCG5EO', null, null, null, '2016-06-01 17:15:04', '2017-03-09 00:35:31.269291');
COMMIT;

-- ----------------------------
-- Table structure for admin_notifications
-- ----------------------------
DROP TABLE IF EXISTS "admin_notifications";
CREATE TABLE "admin_notifications" (
"adminid" int4 NOT NULL,
"notificationsid" int4 NOT NULL,
"lastupdated" timestamp(6),
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Table structure for admingroups
-- ----------------------------
DROP TABLE IF EXISTS "admingroups";
CREATE TABLE "admingroups" (
"admingroupsid" int4 DEFAULT nextval('admingroups_admingroupsid_seq'::regclass) NOT NULL,
"alias" varchar(64) COLLATE "default" DEFAULT NULL::character varying,
"name" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"privilege_level" int4
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of admingroups
-- ----------------------------
BEGIN;
INSERT INTO "admingroups" VALUES ('1', 'SUPERADMIN', 'Superadmin', '1');
INSERT INTO "admingroups" VALUES ('2', 'PRIVILEGED', 'Privileged User', '16');
INSERT INTO "admingroups" VALUES ('3', 'OBSERVE_TABLE', 'User observes table_access attributes', '256');
INSERT INTO "admingroups" VALUES ('4', 'OBSERVE_ROW', 'User observes table_access and row_access attributes', '4096');
COMMIT;

-- ----------------------------
-- Table structure for articlecategories
-- ----------------------------
DROP TABLE IF EXISTS "articlecategories";
CREATE TABLE "articlecategories" (
"articlecategoriesid" int4 DEFAULT nextval('articlecategories_articlecategoriesid_seq'::regclass) NOT NULL,
"l" int4,
"r" int4,
"level" int4,
"alias" varchar(64) COLLATE "default" NOT NULL,
"title" varchar(255) COLLATE "default" DEFAULT NULL::character varying,
"customsort" int4,
"lastupdated" timestamp(6),
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of articlecategories
-- ----------------------------
BEGIN;
INSERT INTO "articlecategories" VALUES ('1', '0', '1', '0', 'articles', 'Articles', null, null, null);
COMMIT;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS "articles";
CREATE TABLE "articles" (
"articlesid" int4 DEFAULT nextval('articles_articlesid_seq'::regclass) NOT NULL,
"alias" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"articlecategoriesid" int4,
"headline" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"teaser" varchar(500) COLLATE "default" DEFAULT NULL::character varying,
"content" text COLLATE "default",
"article_date" date,
"display_from" date,
"display_until" date,
"published" int2,
"customflags" int8,
"customsort" int4,
"updatedby" int4,
"createdby" int4,
"publishedby" int4,
"lastupdated" timestamp(6) DEFAULT now() NOT NULL,
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Table structure for articles_files
-- ----------------------------
DROP TABLE IF EXISTS "articles_files";
CREATE TABLE "articles_files" (
"filesid" int4 NOT NULL,
"articlesid" int4 NOT NULL,
"customsort" int2,
"updatedby" int4,
"createdby" int4,
"lastupdated" timestamp(6),
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS "files";
CREATE TABLE "files" (
"filesid" int4 DEFAULT nextval('files_filesid_seq'::regclass) NOT NULL,
"foldersid" int4,
"title" varchar(128) COLLATE "default",
"subtitle" varchar(128) COLLATE "default",
"description" text COLLATE "default",
"file" varchar(255) COLLATE "C.UTF-8",
"mimetype" varchar(128) COLLATE "default",
"checksum" varchar(64) COLLATE "default",
"obscured_filename" varchar(64) COLLATE "default",
"customsort" int4,
"updatedby" int4,
"createdby" int4,
"lastupdated" timestamp(6),
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Table structure for folders
-- ----------------------------
DROP TABLE IF EXISTS "folders";
CREATE TABLE "folders" (
"foldersid" int4 DEFAULT nextval('folders_foldersid_seq'::regclass) NOT NULL,
"l" int4,
"r" int4,
"level" int4,
"static" int2,
"alias" varchar(64) COLLATE "default",
"title" varchar(128) COLLATE "default",
"description" text COLLATE "default",
"path" varchar(255) COLLATE "C.UTF-8",
"access" char(2) COLLATE "default",
"obscure_files" int2,
"customsort" int4,
"updatedby" int4,
"createdby" int4,
"lastupdated" timestamp(6),
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of folders
-- ----------------------------
BEGIN;
INSERT INTO "folders" VALUES ('1', '0', '1', '0', null, 'files', null, null, 'files/', 'RW', null, null, null, null, null, null);
COMMIT;

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS "notifications";
CREATE TABLE "notifications" (
"notificationsid" int4 DEFAULT nextval('notifications_notificationsid_seq'::regclass) NOT NULL,
"admingroupsid" int4,
"alias" varchar(32) COLLATE "default",
"not_displayed" int2,
"description" varchar(255) COLLATE "default",
"subject" varchar(255) COLLATE "default",
"message" text COLLATE "default",
"signature" varchar(255) COLLATE "default",
"attachment" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS "pages";
CREATE TABLE "pages" (
"pagesid" int4 DEFAULT nextval('pages_pagesid_seq'::regclass) NOT NULL,
"alias" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"title" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"keywords" varchar(512) COLLATE "default" DEFAULT NULL::character varying,
"template" varchar(128) COLLATE "default" DEFAULT NULL::character varying,
"locked" int2,
"no_nice_edit" int2,
"lastupdated" timestamp(6) DEFAULT now() NOT NULL,
"firstcreated" timestamp(6) NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Table structure for revisions
-- ----------------------------
DROP TABLE IF EXISTS "revisions";
CREATE TABLE "revisions" (
"revisionsid" int4 DEFAULT nextval('revisions_revisionsid_seq'::regclass) NOT NULL,
"authorid" int4,
"pagesid" int4,
"active" int2,
"title" varchar(256) COLLATE "default" DEFAULT NULL::character varying,
"keywords" varchar(512) COLLATE "default" DEFAULT NULL::character varying,
"description" varchar(512) COLLATE "default" DEFAULT NULL::character varying,
"markup" text COLLATE "default",
"rawtext" text COLLATE "default",
"locale" varchar(2) COLLATE "default" DEFAULT NULL::character varying,
"templateupdated" timestamp(6),
"lastupdated" timestamp(6) DEFAULT now() NOT NULL,
"firstcreated" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Alter Sequences Owned By 
-- ----------------------------
ALTER SEQUENCE "admin_adminid_seq" OWNED BY "admin"."adminid";
ALTER SEQUENCE "admingroups_admingroupsid_seq" OWNED BY "admingroups"."admingroupsid";
ALTER SEQUENCE "articlecategories_articlecategoriesid_seq" OWNED BY "articlecategories"."articlecategoriesid";
ALTER SEQUENCE "articles_articlesid_seq" OWNED BY "articles"."articlesid";
ALTER SEQUENCE "files_filesid_seq" OWNED BY "files"."filesid";
ALTER SEQUENCE "folders_foldersid_seq" OWNED BY "folders"."foldersid";
ALTER SEQUENCE "notifications_notificationsid_seq" OWNED BY "notifications"."notificationsid";
ALTER SEQUENCE "pages_pagesid_seq" OWNED BY "pages"."pagesid";
ALTER SEQUENCE "revisions_revisionsid_seq" OWNED BY "revisions"."revisionsid";

-- ----------------------------
-- Uniques structure for table admin
-- ----------------------------
ALTER TABLE "admin" ADD UNIQUE ("email");
ALTER TABLE "admin" ADD UNIQUE ("username");

-- ----------------------------
-- Primary Key structure for table admin
-- ----------------------------
ALTER TABLE "admin" ADD PRIMARY KEY ("adminid");

-- ----------------------------
-- Primary Key structure for table admin_notifications
-- ----------------------------
ALTER TABLE "admin_notifications" ADD PRIMARY KEY ("adminid", "notificationsid");

-- ----------------------------
-- Uniques structure for table admingroups
-- ----------------------------
ALTER TABLE "admingroups" ADD UNIQUE ("alias");

-- ----------------------------
-- Primary Key structure for table admingroups
-- ----------------------------
ALTER TABLE "admingroups" ADD PRIMARY KEY ("admingroupsid");

-- ----------------------------
-- Indexes structure for table articlecategories
-- ----------------------------
CREATE INDEX "articlecategories_l_idx" ON "articlecategories" USING btree ("l");
CREATE INDEX "articlecategories_level_idx" ON "articlecategories" USING btree ("level");
CREATE INDEX "articlecategories_r_idx" ON "articlecategories" USING btree ("r");

-- ----------------------------
-- Uniques structure for table articlecategories
-- ----------------------------
ALTER TABLE "articlecategories" ADD UNIQUE ("alias");

-- ----------------------------
-- Primary Key structure for table articlecategories
-- ----------------------------
ALTER TABLE "articlecategories" ADD PRIMARY KEY ("articlecategoriesid");

-- ----------------------------
-- Indexes structure for table articles
-- ----------------------------
CREATE INDEX "fki_articlecategories_fk" ON "articles" USING btree ("articlecategoriesid");
CREATE INDEX "fki_createdby_fk" ON "articles" USING btree ("createdby");
CREATE INDEX "fki_publishedby_fk" ON "articles" USING btree ("publishedby");
CREATE INDEX "fki_updatedby_fk" ON "articles" USING btree ("updatedby");

-- ----------------------------
-- Uniques structure for table articles
-- ----------------------------
ALTER TABLE "articles" ADD UNIQUE ("alias");

-- ----------------------------
-- Primary Key structure for table articles
-- ----------------------------
ALTER TABLE "articles" ADD PRIMARY KEY ("articlesid");

-- ----------------------------
-- Primary Key structure for table articles_files
-- ----------------------------
ALTER TABLE "articles_files" ADD PRIMARY KEY ("filesid", "articlesid");

-- ----------------------------
-- Indexes structure for table files
-- ----------------------------
CREATE UNIQUE INDEX "files_foldersid_file_key" ON "files" USING btree ("foldersid", "file" COLLATE "C.UTF-8");

-- ----------------------------
-- Uniques structure for table files
-- ----------------------------
ALTER TABLE "files" ADD UNIQUE ("foldersid", "file");

-- ----------------------------
-- Primary Key structure for table files
-- ----------------------------
ALTER TABLE "files" ADD PRIMARY KEY ("filesid");

-- ----------------------------
-- Indexes structure for table folders
-- ----------------------------
CREATE UNIQUE INDEX "folders_alias_idx" ON "folders" USING btree ("alias");
CREATE INDEX "folders_l_idx" ON "folders" USING btree ("l");
CREATE INDEX "folders_r_idx" ON "folders" USING btree ("r");
CREATE INDEX "folders_level_idx" ON "folders" USING btree ("level");

-- ----------------------------
-- Primary Key structure for table folders
-- ----------------------------
ALTER TABLE "folders" ADD PRIMARY KEY ("foldersid");

-- ----------------------------
-- Uniques structure for table notifications
-- ----------------------------
ALTER TABLE "notifications" ADD UNIQUE ("alias");

-- ----------------------------
-- Primary Key structure for table notifications
-- ----------------------------
ALTER TABLE "notifications" ADD PRIMARY KEY ("notificationsid");

-- ----------------------------
-- Uniques structure for table pages
-- ----------------------------
ALTER TABLE "pages" ADD UNIQUE ("alias");
ALTER TABLE "pages" ADD UNIQUE ("template");

-- ----------------------------
-- Primary Key structure for table pages
-- ----------------------------
ALTER TABLE "pages" ADD PRIMARY KEY ("pagesid");

-- ----------------------------
-- Indexes structure for table revisions
-- ----------------------------
CREATE INDEX "fki_author_fk" ON "revisions" USING btree ("authorid");
CREATE INDEX "fki_pages_fk" ON "revisions" USING btree ("pagesid");

-- ----------------------------
-- Uniques structure for table revisions
-- ----------------------------
ALTER TABLE "revisions" ADD UNIQUE ("pagesid", "active");

-- ----------------------------
-- Primary Key structure for table revisions
-- ----------------------------
ALTER TABLE "revisions" ADD PRIMARY KEY ("revisionsid");

-- ----------------------------
-- Foreign Key structure for table "admin_notifications"
-- ----------------------------
ALTER TABLE "admin_notifications" ADD FOREIGN KEY ("adminid") REFERENCES "admin" ("adminid") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "admin_notifications" ADD FOREIGN KEY ("notificationsid") REFERENCES "notifications" ("notificationsid") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Key structure for table "articles"
-- ----------------------------
ALTER TABLE "articles" ADD FOREIGN KEY ("createdby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "articles" ADD FOREIGN KEY ("publishedby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "articles" ADD FOREIGN KEY ("updatedby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "articles" ADD FOREIGN KEY ("articlecategoriesid") REFERENCES "articlecategories" ("articlecategoriesid") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Key structure for table "articles_files"
-- ----------------------------
ALTER TABLE "articles_files" ADD FOREIGN KEY ("updatedby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "articles_files" ADD FOREIGN KEY ("createdby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "articles_files" ADD FOREIGN KEY ("articlesid") REFERENCES "articles" ("articlesid") ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE "articles_files" ADD FOREIGN KEY ("filesid") REFERENCES "files" ("filesid") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Key structure for table "files"
-- ----------------------------
ALTER TABLE "files" ADD FOREIGN KEY ("createdby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "files" ADD FOREIGN KEY ("updatedby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "files" ADD FOREIGN KEY ("foldersid") REFERENCES "folders" ("foldersid") ON DELETE CASCADE ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Key structure for table "folders"
-- ----------------------------
ALTER TABLE "folders" ADD FOREIGN KEY ("createdby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE "folders" ADD FOREIGN KEY ("updatedby") REFERENCES "admin" ("adminid") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Key structure for table "notifications"
-- ----------------------------
ALTER TABLE "notifications" ADD FOREIGN KEY ("admingroupsid") REFERENCES "admingroups" ("admingroupsid") ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------
-- Foreign Key structure for table "revisions"
-- ----------------------------
ALTER TABLE "revisions" ADD FOREIGN KEY ("authorid") REFERENCES "admin" ("adminid") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "revisions" ADD FOREIGN KEY ("pagesid") REFERENCES "pages" ("pagesid") ON DELETE NO ACTION ON UPDATE NO ACTION;
