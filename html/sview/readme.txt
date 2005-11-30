KCCI SchoolNet8 Viewer README
Author:  Daryl Herzmann
Dated:    31 Dec 2003

Topics covered in this README:
Motivations for this application
Installation
Running
What this application does
How this application works
Known Issues
Future Plans

Motivations for this application:
	I have gotten requests from schools in the KCCI SchoolNet8 network for a web application that they can run to display live information on their internal TV network.  At the time, the website only had a dynamically updating web graphic with the live data on it.  This image was too small for display and it required a 40K download every minute to refresh the image.  Clearly, it would be better if only the data was transferred to the display application.  So I wrote a java application to view and manipulate the raw SchoolNet8 data.
	Writting a java application has many benefits.  Java is cross platform and has an extensive programming interface.  If your operating system supports the Sun Java Virtual Machine (JVM), you can run this application,

Installation
	Installing a version of Java 1.3 or higher is beyond the scope of this document.  If you have questions about java, please consult Sun's excellent website at http://java.sun.com .  Once you have Java installed, you can proceed to install the application.
	The application is available in a zip file on the website.  You will only need this one file.  After you have downloaded the zip file, unzip the archive into a directory of your choice.  The following files should be visible.
  - SchoolNet8Viewer.jar
  - settings.ini 
  - GPL.txt
  - runme.bat
  - runme.csh

	Only one file is absolutely needed for this application to run.  The SchoolNet8Viewer.jar file contains the .class files necessary to run the application.   The GPL.txt file is the license file for this software.  The runme.csh and runme.bat files are scripts to run this program if your .jar file does not execute.
  
Running
	If your system is configured to exec .jar files, you should be able to doubleclick the .jar file and launch the program.  If your system does not understand how to execute .jar files, you can run the runme.bat batch file, which will exec java and launch the application.
	Alternatively, if you are running UNIX or like the command line.  You can launch this application by typing “java -jar SchoolNet8Viewer.jar” at your favorite comand prompt.

What this application does 
	This application replicates the on-air display that KCCI uses to display their Schoolnet8 data.  It also does some other tricks.  Functionalities of the app are shown on different 'panes'.  These panes are named after their function.

Single View:  shows data from one site
Quad View:  shows data from sites with extreme values of rain, wind, and temperature
Table View: shows data from all sites in a sortable table.
RADAR View: shows the most recent KCCI Super Doppler image.
Camera View: shows the latest still image from the network of Web cameras.
Preferences:  log viewer and preference setting.

On the preferences pane, you can set an option to automatically switch the display between the 'Single View' and the 'RADAR View'.  After setting this option, you must then switch to either of these panes to begin the automatic switching of panes.


How this application works
	When the application launches, it downloads a station file from the Iowa Environmental Mesonet website to initialize some data structures in the app.  The app then downloads a comma delimited file from the same website every minute afterwards to update the data in the various displays.  The values of the station file and data file are configuable settings in the settings.ini file.  You should not need to change these values.
	Similarly, the RADAR data is downloaded from KCCI's website.  Since these files are much larger, roughly 30K per image, the app can be configured to never download the RADAR images or not download the images as often.  By default, the image is only downloaded every 4 minutes.  Keep in mind that the RADAR data usually only updates every 2 minutes or so anyway.

 
Known Issues
There are no major bugs known at the time of this release, but they probably exist somewhere!   I will certainly be interested in feedback on how this app has been working for folks.

On Mac OS-X, the temperature bar does not appear as red, but silverish.  This is a feature of the user interface integration of Mac OS-X.

Future Plans
The future of this app will be dictated by what the users want.  One of the major features being worked on is streaming of data to the application so that data updates every 10 seconds or so instead of every minute.  If you have any ideas, please let me know!

Daryl Herzmann
akrherz@iastate.edu 
515 294 5978