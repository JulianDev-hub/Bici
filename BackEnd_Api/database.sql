CREATE DATABASE IF NOT EXIST biciusuarios;
USE biciusuarios;

CREATE TABLE Users(
IdUsuario int(255) auto_increment not null,
Rol varchar(20),
TipoDocumento varchar(255),
NumeroDocumento varchar(255),
Nombres varchar(255),
Apellidos varchar(255),
Genero varchar(20),
Edad int(2),
Contrasena varchar(255),
Email varchar(255),
created_at TIMESTAMP,
updated_at TIMESTAMP,
CONSTRAINT pk_Usuarios PRIMARY KEY(IdUsuario)
)ENGINE=InnoDB;

CREATE TABLE Ubication(
    IdUbication int(255) auto_increment not null,
    IdUsuario int(255) not null,
    NombreUbicacion varchar(255),
    Latitud int(255),
    Longitud int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_Ubication PRIMARY KEY(IdUbication),
    CONSTRAINT fk_Ubication_Users FOREIGN KEY(IdUsuario) REFERENCES Users(IdUsuario)
)ENGINE=InnoDB;

CREATE TABLE MonthMaster(
    IdMes int(255) auto_increment not null,
    NombreMes varchar(255),
    Cifra int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_MonthMaster PRIMARY KEY(IdMes)
)ENGINE=InnoDB;

CREATE TABLE WeekHourMaster(
    IdHoraSemana int(255) auto_increment not null,
    HoraInicial int(255),
    HoraFinal int(255),
    DiaSemana varchar(255),
    Cifra int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_WeekHourMaster PRIMARY KEY(IdHoraSemana)
)ENGINE=InnoDB;

CREATE TABLE LocationMaster(
    IdLocalidad int(255) auto_increment not null,
    NombreLocalidad varchar(255),
    Cifra int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_LocationMaster PRIMARY KEY(IdLocalidad)
)ENGINE=InnoDB;

CREATE TABLE GenderAgeMaster(
    IdGeneroEdad int(255) auto_increment not null,
    Genero varchar(255),
    EdadInicial int(255),
    EdadFinal int(255),
    Cifra int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_GenderAgeMaster PRIMARY KEY(IdGeneroEdad)
)ENGINE=InnoDB;

CREATE TABLE DataMaster(
    IdTotalAccidentes int(255) auto_increment not null,
    Cifra int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_DataMaster PRIMARY KEY(IdTotalAccidentes)
)ENGINE=InnoDB;

CREATE TABLE ActiveFrames(
    IdFramesActivos int(255) auto_increment not null,
    FrameMapa boolean,
    FrameIndicaciones boolean,
    FrameIBOCA boolean,
    FrameCovid boolean,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_ActiveFrames PRIMARY KEY(IdFramesActivos)
)ENGINE=InnoDB;

CREATE TABLE IBOCA(
    IdIBOCA int(255) auto_increment not null,
    Localidad varchar(255),
    NombreMedidor varchar(255),
    ValorMedidor varchar (255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_IBOCA PRIMARY KEY(IdIBOCA)
)ENGINE=InnoDB;

CREATE TABLE COVID(
    IdCOVID int(255) auto_increment not null,
    Localidad varchar(255),
    ValorLocalidad int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_COVID PRIMARY KEY(IdCOVID)
)ENGINE=InnoDB;

CREATE TABLE MasterCOVID(
    IdMasterCOVID int(255) auto_increment not null,
    TotalCasos int(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_MasterCOVID PRIMARY KEY(IdMasterCOVID)
)ENGINE=InnoDB;

CREATE TABLE Questions(
    IdQuestions int(255) auto_increment not null,
    Descripcion varchar(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_Questions PRIMARY KEY(IdQuestions)
)ENGINE=InnoDB;

CREATE TABLE HeadInformatioQuestions(
    IdHeadInformatioQuestions int(255) auto_increment not null,
    IdDetailInformatioQuestions int(255),
    IdUsuario int(255),
    Contestado varchar(2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_HeadInformatioQuestions PRIMARY KEY(IdHeadInformatioQuestions)
    CONSTRAINT fk_HeadInformation_Users FOREIGN KEY(IdUsuario) REFERENCES Users(IdUsuario)
    CONSTRAINT fk_HeadInformation_DetailInformation FOREIGN KEY(IdDetailInformatioQuestions) REFERENCES DetailInformatioQuestions(IdDetailInformatioQuestions)
)ENGINE=InnoDB;

CREATE TABLE DetailInformatioQuestions(
    IdDetailInformatioQuestions int(255) auto_increment not null,
    IdQuestions int(255),
    ValorSI varchar(2),
    ValorNO varchar (2),
    ValorParcialmente  varchar(2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_DetailInformatioQuestions PRIMARY KEY(IdDetailInformatioQuestions)
    CONSTRAINT fk_DetailInformation_Questions FOREIGN KEY(IdQuestions) REFERENCES Questions(IdQuestions)
)ENGINE=InnoDB;

CREATE TABLE Comments(
    CommentsId int(255) auto_increment not null,
    RouteComment varchar(255),
    ReadFlag varchar(2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    CONSTRAINT pk_Comments PRIMARY KEY(CommentsId)
   
)ENGINE=InnoDB;