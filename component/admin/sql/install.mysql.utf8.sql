DROP TABLE IF EXISTS `#__CBULessons`;
DROP TABLE IF EXISTS `#__CBUContentTypes`;
DROP TABLE IF EXISTS `#__CBUCategories`;
DROP TABLE IF EXISTS `#__CBUSeries`;

CREATE TABLE `#__CBUContentTypes` (
    `Id`        INT(11)     NOT NULL AUTO_INCREMENT,
    `TypeName`  VARCHAR(25) NOT NULL,
    PRIMARY KEY (`Id`)
)
    ENGINE =InnoDB
    AUTO_INCREMENT =0
    DEFAULT CHARSET =utf8;


CREATE TABLE `#__CBUCategories` (
    `Id`            INT(11)         NOT NULL AUTO_INCREMENT,
    `Name`          VARCHAR(100)    NOT NULL,
    `Description`   TEXT            NULL,
    `ImagePath`     VARCHAR(255)    NULL,
    PRIMARY KEY (`Id`)
)
    ENGINE =InnoDB
    AUTO_INCREMENT =0
    DEFAULT CHARSET =utf8;

CREATE TABLE `#__CBUSeries` (
    `Id`            INT(11)     NOT NULL AUTO_INCREMENT,
    `SeriesName`    VARCHAR(255) NOT NULL,
    `ImagePath`     VARCHAR(255) NULL,
    `Description`   VARCHAR(255) NULL,
    PRIMARY KEY (`Id`)
)
    ENGINE =InnoDB
    AUTO_INCREMENT =0
    DEFAULT CHARSET =utf8;


CREATE TABLE `#__CBULessons` (
    `Id`            INT(11)         NOT NULL AUTO_INCREMENT,
    `Title`         VARCHAR(255)    NOT NULL,
    `SeriesId`      INT(11)         NULL, /*This will become a foreign key when that part of the site gets built out*/
    `CategoryId`    INT(11)         NOT NULL,
    `ContentTypeId` INT(11)         NOT NULL,
    `ImagePath`     VARCHAR(255)    NULL,
    `SourceCredit`  VARCHAR(255)    NULL,
    `Content`       TEXT            NULL,
    `Description`   TEXT            NULL,
    `DatePublished` DATE            NULL,
    `SeriesOrder`   TINYINT(4)      NULL,
    `Published`     TINYINT(1)      DEFAULT 0,
    PRIMARY KEY (`Id`),
    FOREIGN KEY (`ContentTypeId`)
        REFERENCES `#__CBUContentTypes`(`Id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`CategoryId`)
        REFERENCES `#__CBUCategories` (`Id`)
        ON DELETE CASCADE,
    FOREIGN KEY (`SeriesId`)
        REFERENCES `#__CBUSeries` (`Id`)
        ON DELETE SET NULL
)
    Engine =InnoDB
    AUTO_INCREMENT =0
    DEFAULT CHARSET =utf8;

INSERT INTO `#__CBUContentTypes` (`Id`,`TypeName`) VALUES
(1, 'Brainshark'),
(2, 'Generic Video'),
(3, 'Download'),
(4, 'YouTube'),
(5, 'Html'),
(6, 'Nazarene Media Library'),
(7, 'Url Iframe');


INSERT INTO `#__CBUCategories` (`Id`, `Name`, `Description`, `ImagePath`) VALUES
(1, 'The Treasurer''s Corner', 'Here you''ll find lessons related to the role of treasurer', 'http://placekitten.com/600/500'),
(2, 'Taking care of your pastor', 'Here you''ll find lessons about how to pay and otherwise care of your pastor.', 'http://placekitten.com/600/450'),
(3, 'The Secretary''s Corner', 'This is all about secretary stuff.', 'http://placekitten.com/600/450'),
(4, 'What does the Manual say?', 'This is all about issues relating to the Manual.', '/components/com_pbacademy/images/categories/manual.jpg'),
(5, 'Frequently Asked Questions', 'This is the place for all your frequently asked questions.', '/components/com_pbacademy/images/categories/FAQs.jpg'),
(6, 'Church Board Members', 'This is the place for all the questions you might have relateing to the role of church board member.','/components/com_pbacademy/images/categories/ChurchBoardTable.jpg'),
(7, 'The Pastor''s role','Here we''ll address how a pastor can fulfill his function as ex officio president and chairperson of the board.','http://lorempixel.com/600/600'),
(8, 'Legal and Tax Issues','This is the place for lessons related to legal and tax issues.', '/components/com_pbacademy/images/categories/legal.jpg'),
(12, 'P&B Benefits', 'Lessons in this school are all about the various benefit programs available through Pensions and Benefits USA.', '/images/pbacademy/schools/P&B-Blue.png');
