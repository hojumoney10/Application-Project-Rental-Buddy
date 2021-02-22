# Application Project - Rental Buddy
 
[![Build Status](https://travis-ci.com/TaehyungAlexKim/Application-Project-Rental-Buddy.svg?token=aTxJ7y6DwwppjrZauChh&branch=main)](https://travis-ci.com/TaehyungAlexKim/Application-Project-Rental-Buddy)

This project is the result of the Fanshawe college 21W IWD1 Application Project course.  
**Live demo**: [Here](https://rental.fanshawe21w.tk/)

## System

**Mainly used languages for development**: Vanilla PHP, Vanilla JavaScript  
**Libraries**: Bootstrap, Symfony  
**Database**: MariaDB  
**Production Machine**: AWS  
**CI System**: Travis-AWS.S3/CodeDeploy  

## Developers: Group5

Graham Blandford  
Jordan Foster  
Sirlin Jeong  
Sung-Kyu Choi  
Taehyung Kim

## Deployment Instructions (LAMP stack on Ubuntu 20.04 required)

(Note: We are using MariaDB on our live demo site, but application is running on VM's using MySQL with no issue)

1. Install this GIT into your /var/www directory
2. Login into MariaDb as root user and execute the script rental.sql
3. Create a database user with matching credentials that has access to the rental database:

   create user 'rental'@'localhost' identified by 'SScAGAMfi4g0gwgp';
   grant all privileges on rental.* to 'rental'@'localhost';

4. Navigate to home page /index.php

## Rental Buddy Deployment Access

Robust Login/Authentication will be handled in Sprint 2. We determined this needed some thought as we
have had no solid introduction to AUTH in PHP and this is why we focused on other functionality for Sprint 1.

We have provided a simple dropdown to mimic the three types of users of the system; Admin, Landlord & Tenant. 
The menus represented for the first two users are the same, but the tenant user has specific menu functionality.

Further details are provided in the video-walkthough.
