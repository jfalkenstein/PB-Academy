/**
 * Author:  jfalkenstein
 * Created: Apr 5, 2016
 */
DROP TABLE IF EXISTS `#__CBULessons`;
DROP TABLE IF EXISTS `#__CBUContentTypes`;
DROP TABLE IF EXISTS `#__CBUCategories`;

CREATE TABLE `#__CBUContentTypes` (
    `Id`        INT(11)     NOT NULL AUTO_INCREMENT,
    `TypeName`  VARCHAR(25) NOT NULL,
    PRIMARY KEY (`Id`)
)
    ENGINE =InnoDB
    AUTO_INCREMENT =0
    DEFAULT CHARSET =utf8;

INSERT INTO `#__CBUContentTypes` (`TypeName`) VALUES
('Brainshark'),
('Video'),
('Document'),
('Other');

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

INSERT INTO `#__CBUCategories` (`Name`,`Description`,`ImagePath`) VALUES
('The Treasurer''s Corner', 'Here you''ll find lessons related to the role of treasurer',null),
('Taking care of your pastor','Here you''ll find lessons about how to pay and otherwise care of your pastor.', null);



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
    PRIMARY KEY (`Id`),
    FOREIGN KEY (`ContentTypeId`)
        REFERENCES `#__CBUContentTypes`(`Id`),
    FOREIGN KEY (`CategoryId`)
        REFERENCES `#__CBUCategories` (`Id`)
)
    Engine =InnoDB
    AUTO_INCREMENT =0
    DEFAULT CHARSET =utf8;

INSERT INTO `#__CBULessons` (`Title`, 
                              `SeriesId`, 
                              `CategoryId`, 
                              `ContentTypeId`, 
                              `ImagePath`, 
                              `SourceCredit`,
                              `Content`,
                              `Description`,
                              `DatePublished`)
                              VALUES
                             ('My First lesson',
                              '1',
                              '2',
                              '1',
                              null,
                              null,
                              null,
                              'My description',
                              now());
                              
                              
                              