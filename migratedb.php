<?php
$conn = new mysqli('localhost', 'root', '', null);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "create database if not exists std_management2";

// $result = $conn->query($sql);

if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
    
}
// use db
$conn->select_db('std_management2');

// create table
$sql = "create table if not exists users(
    id int primary key auto_increment,
    username varchar(40) not null,
    password varchar(40) not null,
    email varchar(100) not null,
    phone varchar(11),
    fullname varchar(100)
)";

// excute query


if ($conn->query($sql) === TRUE) {
    echo "Table users created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// create table students

$sql = "create table if not exists students(
    studentid int primary key auto_increment,
    name varchar(40),
    birthday datetime,
    gender varchar(6),
    classid int 
)";

if ($conn->query($sql) === TRUE) {
    echo "Table students created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$sql = "create table if not exists Class(
         ID  int primary key auto_increment ,
        name varchar(40)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table students created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$sql = "create table if not exists Subjects(
        ID int primary key auto_increment,
        name varchar(40)
    )";
if ($conn->query($sql) === TRUE) {
    echo "Table Subjects created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$sql = "create table if not exists StudentSubject(
    ID int primary key auto_increment,
    studentID int,
    SubjectID int
    )";
if ($conn->query($sql) === TRUE) {
    echo "Table Subjects created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "ALTER TABLE students
ADD CONSTRAINT FK_students
FOREIGN KEY (classID) REFERENCES Class(ID)";
if ($conn->query($sql) === TRUE) {
    echo "Table student with FK_students created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$sql = "ALTER TABLE StudentSubject  
ADD CONSTRAINT FK_Subject_StudentSubject  
FOREIGN KEY (SubjectID) REFERENCES Subjects (ID)";
if ($conn->query($sql) === TRUE) {
    echo "Table student with  FK_Subject_StudentSubject   created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$sql = "ALTER TABLE StudentSubject  
ADD CONSTRAINT FK_student_StudentSubject  
FOREIGN KEY (studentID ) REFERENCES students (studentid)";
if ($conn->query($sql) === TRUE) {
    echo "Table student with  FK_student_StudentSubject     created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$sql = "INSERT into class values('1','C2111L')";
$result= $conn->query($sql);
$sql = "INSERT into class values('2','IT')";
$result= $conn->query($sql);
$sql = "INSERT into class values('3','Khoa Học')";
$result= $conn->query($sql);

$conn->close();