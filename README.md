ASIGNMENT DESCRIPTION : 
There is an external event booking system which exports a simple plain json file (File was provided).

ACTIONS REQUIRED :
- Design a database scheme for optimized storage
- Read the json data and save it to the database using php
- Create a simple page with filters for the employee name, event name and date
- Output the filtered results in a table below the filters
- Add a last row for the total price of all filtered entries

ACTIONS TAKEN : 
- Database named "rexxevent_db" created in Xampp.
	NOTE: 
	-please create a database named "rexxevent_db" in MYSQL and then place the extracted folder inside xampp-htdocs. Then run the project.

- JSON file is decoded, read and the data are inserted in table "records".
	NOTE: 
	-The json file is renamed for better usage. The file is also placed inside the project folder for easy access.
	-The json file is read exactly once, and made sure it is not decoded and inserted every time the script runs.
	-Created table is Indexed for better performance

- Created a simple page which shows all the data listed. The data can be custom filtered using fields "employee name" , "event name", "event date".
	NOTE: 
	- Filter button allows to filter data based on selected parameters. If none of the parameters are selected it gives an alert.
	- Refresh button allows to reset the search parameters.
	- Datepicker provided for easy access of callender and better view.

- Filtered values are displayed inside datatable just below the search parameters.

- At the bottom of the table, total price is calculated for each page and filtered records.

ADDITIONAL INFORMAION :
- the versions prior to 1.0.17+60 had timezone Europe/Berlin. The event dates for versions below 1.0.17+60 are converted from Europe/Berlin to UCT and are displayed in the datatable.

ENVIRONMENT :
- MYSQL version used: XAMPP for Windows 7.2.31 
- PHP version used: PHP 7.2.31)
