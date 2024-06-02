create database if not exists time_master_db character set utf8mb4 collate utf8mb4_unicode_ci;

use time_master_db;

drop table if exists
    user_school,
    user,
    classroom_subject,
    classroom_availability,
    teacher_classroom,
    class_schedule,
    classroom,
    teacher_subject,
    teacher_availability,
    teacher_assignment,
    teacher,
    class_subject,
    subject,
    schedule_day,
    time_preference,
    day,
    schedule_timeslot,
    schedule,
    student,
    timeslot,
    class,
    school;

create table school
(
    schoolId      int auto_increment
        primary key,
    schoolAddress varchar(255) not null,
    schoolName    varchar(255) not null,

    unique (schoolAddress)
);

create table class
(
    classId  int auto_increment
        primary key,
    classRef varchar(255) not null,
    schoolId int          not null,

    unique (classRef, schoolId),
    foreign key (schoolId) references school (schoolId)
);

create table timeslot
(
    timeslotId        int auto_increment
        primary key,
    timeslotStartHour time not null,
    timeslotEndHour   time not null,
    timeslotGroup     int  not null,
    schoolId          int  not null,

    unique (timeslotEndHour, schoolId),
    unique (timeslotStartHour, schoolId),
    foreign key (schoolId) references school (schoolId)
);

create table student
(
    studentId         int auto_increment
        primary key,
    studentEmail      varchar(255) not null,
    studentFamilyName varchar(255) not null,
    studentGivenName  varchar(255) not null,
    schoolId          int          not null,
    classId           int          null,

    unique (studentEmail, schoolId),
    foreign key (classId) references class (classId),
    foreign key (schoolId) references school (schoolId)
);

create table schedule
(
    scheduleId int auto_increment
        primary key,
    schoolId   int not null,

    foreign key (schoolId) references school (schoolId)
);

create table schedule_timeslot
(
    scheduleTimeslotId int auto_increment
        primary key,
    scheduleId         int not null,
    timeslotId         int not null,

    unique (scheduleId, timeslotId),
    foreign key (timeslotId) references timeslot (timeslotId),
    foreign key (scheduleId) references schedule (scheduleId)
);

create table day
(
    dayId   int auto_increment
        primary key,
    dayName varchar(255) not null,

    unique (dayName)
);

create table time_preference
(
    timePreferenceId int auto_increment
        primary key,
    timeslotId       int not null,
    dayId            int not null,
    timePreference   int not null,

    unique (timeslotId, dayId),
    foreign key (timeslotId) references timeslot (timeslotId),
    foreign key (dayId) references day (dayId)
);

create table schedule_day
(
    scheduleDayId int auto_increment
        primary key,
    scheduleId    int not null,
    dayId         int not null,

    unique (scheduleId, dayId),
    foreign key (scheduleId) references schedule (scheduleId),
    foreign key (dayId) references day (dayId)
);

create table subject
(
    subjectId   int auto_increment
        primary key,
    subjectName varchar(255) not null,
    schoolId    int          not null,

    unique (subjectName, schoolId),
    foreign key (schoolId) references school (schoolId)
);

create table class_subject
(
    classSubjectId          int auto_increment
        primary key,
    classSubjectNumberHours int not null,
    classId                 int not null,
    subjectId               int not null,

    unique (classId, subjectId),
    foreign key (classId) references class (classId),
    foreign key (subjectId) references subject (subjectId)
);

create table teacher
(
    teacherId          int auto_increment
        primary key,
    teacherEmail       varchar(255) not null,
    teacherFamilyName  varchar(255) not null,
    teacherGivenName   varchar(255) not null,
    teacherGender      varchar(255) not null,
    teacherNumberHours int          not null,
    schoolId           int          not null,

    unique (teacherEmail, schoolId),
    foreign key (schoolId) references school (schoolId)
);

create table teacher_assignment
(
    teacherAssignmentId          int auto_increment
        primary key,
    classId                      int not null,
    subjectId                    int not null,
    teacherId                    int not null,
    teacherAssignmentNumberHours int not null,

    unique (classId, subjectId),
    foreign key (teacherId) references teacher (teacherId),
    foreign key (classId) references class (classId),
    foreign key (subjectId) references subject (subjectId)
);

create table teacher_availability
(
    teacherAvailabilityId int auto_increment
        primary key,
    teacherId             int                                             not null,
    timeslotId            int                                             not null,
    dayId                 int                                             not null,
    teacherAvailability   enum ('unavailable', 'prefer-not', 'available') not null,

    unique (teacherId, timeslotId, dayId),
    foreign key (timeslotId) references timeslot (timeslotId),
    foreign key (dayId) references day (dayId),
    foreign key (teacherId) references teacher (teacherId)
);

create table teacher_subject
(
    teacherSubjectId int auto_increment
        primary key,
    teacherId        int not null,
    subjectId        int not null,

    unique (teacherId, subjectId),
    foreign key (subjectId) references subject (subjectId),
    foreign key (teacherId) references teacher (teacherId)
);

create table classroom
(
    classroomId          int auto_increment
        primary key,
    classroomRef         varchar(255) not null,
    classroomNumberSeats int          null,
    classroomProjector   tinyint(1)   null,
    schoolId             int          not null,

    unique (classroomRef, schoolId),
    foreign key (schoolId) references school (schoolId)
);

create table class_schedule
(
    classScheduleId int auto_increment
        primary key,
    scheduleId      int null,
    dayId           int not null,
    timeslotId      int not null,
    classId         int not null,
    teacherId       int not null,
    classroomId     int not null,
    subjectId       int not null,

    foreign key (classId) references class (classId),
    foreign key (timeslotId) references timeslot (timeslotId),
    foreign key (scheduleId) references schedule (scheduleId),
    foreign key (dayId) references day (dayId),
    foreign key (subjectId) references subject (subjectId),
    foreign key (teacherId) references teacher (teacherId),
    foreign key (classroomId) references classroom (classroomId)
);

create table teacher_classroom
(
    teacherClassroomId      int auto_increment
        primary key,
    teacherId               int not null,
    classroomId             int not null,
    teacherClassroomRanking int not null,

    unique (teacherId, classroomId),
    foreign key (teacherId) references teacher (teacherId),
    foreign key (classroomId) references classroom (classroomId)
);

create table classroom_availability
(
    classroomAvailabilityId int auto_increment
        primary key,
    classroomId             int                               not null,
    timeslotId              int                               not null,
    dayId                   int                               not null,
    classroomAvailability   enum ('unavailable', 'available') not null,

    unique (classroomId, timeslotId, dayId),
    foreign key (timeslotId) references timeslot (timeslotId),
    foreign key (dayId) references day (dayId),
    foreign key (classroomId) references classroom (classroomId)
);

create table classroom_subject
(
    classroomSubjectId int auto_increment
        primary key,
    classroomId        int not null,
    subjectId          int not null,

    unique (classroomId, subjectId),
    foreign key (subjectId) references subject (subjectId),
    foreign key (classroomId) references classroom (classroomId)
);

create table user
(
    userId         int auto_increment
        primary key,
    userEmail      varchar(255) not null,
    userFamilyName varchar(255) not null,
    userGivenName  varchar(255) not null,
    userPassword   varchar(255) not null,

    unique (userEmail)
);

create table user_school
(
    userSchoolId int auto_increment
        primary key,
    userId       int not null,
    schoolId     int not null,

    unique (userId, schoolId),
    foreign key (schoolId) references school (schoolId),
    foreign key (userId) references user (userId)
);
