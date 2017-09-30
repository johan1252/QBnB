# QBnB

QBnB is a web application designed for Queen's Alumni to find temporary housing.
The application was originally designed for CISC 332 Databases course during Spring 2016 semester.

The website allows a user to both find their perfect accommodation, as well as host fellow alumni at their own properties.
Featuring a detailed search page the user can find an accommodation for any length of stay with a variety of filters.
Only available and matching accommodations will be displayed to the user.

A user can review their listings and booking at any time using their respective pages and make any changes necessary.
As well the user is reminded of any new bookings, cancellations etc. using the notifications page (and indicator at the top of each page).

Credibility of each accommodation is maintained through fellow user reviews. 
After an alumni has completed their booking they are provided the oppertunity to rate and review the listing.
This allows new users to make an education decision when choosing their future accommodations.

## Architecture
The web application's front end is comprised of HTML, CSS and PHP.
The initial web page is designed to be a public web page with general information on the service.
Once a user has authenticated using a username and password, the following pages are made available to them:
* Housing Search Page - Used to allow the user to find their perfect accommodation.
* My Listings Page - Showing the user's own personal housing listings (if they are listing any of their own properties)
* My Bookings Page - Shows the user any future, on-going, and past bookings that have made.
* Notifications Page - Shows the user any notifications their account has received (if a booking was confirmed etc.)
* User Configuration Page - Allows the user to edit their password, email address, phone number etc.

The web application's back end is based on MySQL database server which is queried by the front end using PHP.

## Getting Started

### Prerequisites

```
Web Hosting or Local Web Server such as XAMPP
MySQL Database Application
```

### Running the web application using XAMPP
1. Launch XAMPP
2. Ensure "MySQL Database" and "Apache Web Server" server's are started. If not, start both servers now.
3. Clone this repository "QBnB" into xamppApplicationFolder/htdocs/
	1. Use "git clone <git clone url from github>"
4. Launch your favourite web browser and go to "http://localhost:8080/QBnB/"
5. Voilà! You are now running a local version of the web application.
	1. Any changes made to files in xamppApplicationFolder/htdocs/ should be reflected at "http://localhost:8080/QBnB/"

## Authors

* Ryan Dick
* Maytha Nassor
* Johan Cornelissen

## Screenshots

Public Homepage:

![alt text](https://github.com/johan1252/QBnB/blob/master/Screenshots/Screenshot_Homepage.png?raw=true)

Housing Search Page:

![alt text](https://github.com/johan1252/QBnB/blob/master/Screenshots/Screenshot_Search.png?raw=true)

My Listings Page:

![alt text](https://github.com/johan1252/QBnB/blob/master/Screenshots/Screenshot_MyListings.png?raw=true)

My Bookings Page:

![alt text](https://github.com/johan1252/QBnB/blob/master/Screenshots/Screenshot_MyBookings.png?raw=true)

User Configuration Page:

![alt text](https://github.com/johan1252/QBnB/blob/master/Screenshots/Screenshot_User.png?raw=true)

