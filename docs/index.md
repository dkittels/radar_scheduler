# Introduction

Radar Scheduler is extremely simple.  There is no authentication to speak of outside of including a user_id.  
This is the only parameter required in every API call.  All API calls return a json encoded payload with
either the requested data or a basic error description.  There are 8 API calls in all, 4 of which are 
exclusive to "manager" level users.

#### *get* /shift/:id

Given the id of a single shift get the details about it.  Managers can retrieve any shift, but employees can only retrieve shifts they are assigned to.

###### Request
param|value 
-----|-----
:id | **Required** *integer* the id of the shift being requested
user_id | **Required**  *integer* the id of the user making the request

###### Response
param|value 
-----|-----
id | *integer* the id of the shift
manager_id | *integer* the id of the user who is managing the shift
employee_id | *integer* the id of the employee who is working the shift
break | *float* the amount of hours during the shift intended as break time
start_time | *date(YYYY-MM-DD HH:MM:SS)* the start time of the shift
end_time | *date(YYYY-MM-DD HH:MM:SS)* the end time of the shift

###### Example Request
    http://localhost:8080/shift/1?user_id=5

###### Example Response
    {
      "id": "1",
      "manager_id": "5",
      "employee_id": "1",
      "break": "1",
      "start_time": "2016-03-01 08:00:00",
      "end_time": "2016-03-01 12:00:00",
    }
   

#### *get* /user/:id

Given the id of a single user get the details about it.  Only managers can 
retrieve user details directly.

###### Request
param|value 
-----|-----
:id | **Required** *integer* the id of the user being requested
user_id | **Required** *integer* the id of the user making the request

###### Response
param|value 
-----|-----
id | *integer* the id of the user
name | *string* the name of the user
role | *string* the role of the user
email | *string* the email of the user
phone | *string* the phone number of the user

###### Example Request
    http://localhost:8080/user/1?user_id=5

###### Example Response
    {
      "id": "1",
      "name": "Paul McCartney",
      "role": "employee",
      "email": "paul@beatles.com",
      "phone": "555-555-5551"
    }


#### *get* /readSchedule

Available to managers only, this will return a list of all shifts between
start_time and end_time.

###### Request
param|value 
-----|-----
user_id | **Required** *integer* the id of the user making the request
start_time | **Required** *date(YYYY-MM-DD HH:MM:SS)* the start time of the schedule
end_time | **Required** *date(YYYY-MM-DD HH:MM:SS)* the end time of the schedule

###### Response
Any array of shifts within the specificed range.

###### Example Request
    http://localhost:8080/readSchedule?user_id=5&start_time=2016-03-01 00:00:00&end_time=2016-03-03 00:00:00

###### Example Response
	[
	  {
		"employee_name": "Paul McCartney",
		"break": "1",
		"start_time": "2016-03-01 08:00:00",
		"end_time": "2016-03-01 12:00:00"
	  },
	  {
		"employee_name": "Ringo Starr",
		"break": "1",
		"start_time": "2016-03-01 08:00:00",
		"end_time": "2016-03-01 12:00:00"
	  },
	  {
		"employee_name": "John Lennon",
		"break": "1",
		"start_time": "2016-03-01 12:00:00",
		"end_time": "2016-03-01 20:00:00"
	  },
	  {
		"employee_name": "George Harrison",
		"break": "0",
		"start_time": "2016-03-01 12:00:00",
		"end_time": "2016-03-01 16:00:00"
	  },
	  {
		"employee_name": "Ringo Starr",
		"break": "1",
		"start_time": "2016-03-02 08:00:00",
		"end_time": "2016-03-02 16:00:00"
	  },
	  {
		"employee_name": "John Lennon",
		"break": "1",
		"start_time": "2016-03-02 12:00:00",
		"end_time": "2016-03-02 20:00:00"
	  }
	]


#### *get* /readUserSchedule

Returns a list of schedules the current user is assigned to.

###### Request
param|value 
-----|-----
user_id | **Required** *integer* the id of the user making the request

###### Response
Any array of shifts the user is assigned to.

###### Example Request
    http://localhost:8080/readSchedule?user_id=5

###### Example Response
	[
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "1",
		"start_time": "2016-03-01 08:00:00",
		"end_time": "2016-03-01 12:00:00"
	  },
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "0",
		"start_time": "2016-03-03 08:00:00",
		"end_time": "2016-03-03 12:00:00"
	  },
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "0",
		"start_time": "2016-03-05 12:00:00",
		"end_time": "2016-03-05 16:00:00"
	  },
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "1.5",
		"start_time": "2016-03-06 08:00:00",
		"end_time": "2016-03-06 16:00:00"
	  },
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "0",
		"start_time": "2016-03-08 08:00:00",
		"end_time": "2016-03-08 16:00:00"
	  },
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "1",
		"start_time": "2016-03-16 08:00:00",
		"end_time": "2016-03-16 16:00:00"
	  },
	  {
		"manager_name": "Darth Vader",
		"manager_email": "kittens@rainbow.net",
		"manager_phone": null,
		"break": "1",
		"start_time": "2016-03-24 08:00:00",
		"end_time": "2016-03-24 16:00:00"
	  }
	]


#### *get* /readUserWeeklyHours

Retrieves a week by week summary of a user's worked hours.  The first week
will be from start_date to the following saturday, the last week will
be from the previous Sunday to the end_date, while all months in between
will be reported as calendar weeks.

###### Request
param|value 
-----|-----
user_id | **Required** *integer* the id of the user making the request
start_date | **Required** *date('YYYY-MM-DD')* the date beginning the summary
end_date | **Required** *date('YYYY-MM-DD')* the date ending the summary

###### Response
An array of weekly results indexed by date range with fields:

param|value 
-----|-----
hours | *float* number of hours scheduled
break | *float* number of hours allocated for breaks
total_hours_worked | *string* number of hours scheduled minus breaks

###### Example Request
    http://localhost:8080/readUserWeeklyHours?user_id=4&start_date=2016-03-01&end_date=2016-03-31

###### Example Response
	{
	  "2016-03-01-2016-03-05": {
		"hours": "8.00",
		"time_on_break": "0.00",
		"total_hours_worked": "8.00"
	  },
	  "2016-03-06-2016-03-12": {
		"hours": "0.00",
		"time_on_break": "0.00",
		"total_hours_worked": "0.00"
	  },
	  "2016-03-13-2016-03-19": {
		"hours": "0.00",
		"time_on_break": "0.00",
		"total_hours_worked": "0.00"
	  },
	  "2016-03-20-2016-03-26": {
		"hours": "0.00",
		"time_on_break": "0.00",
		"total_hours_worked": "0.00"
	  },
	  "2016-03-27-2016-03-31": {
		"hours": "0.00",
		"time_on_break": "0.00",
		"total_hours_worked": "0.00"
	  }
	}


#### *get* /readCoworkers

Within a range of dates, returns a list of a user's shifts as well as what
users are scheduled for a shift at the same time as them and when.

###### Request
param|value 
-----|-----
user_id | **Required** *integer* the id of the user making the request
start_date | **Required** *date('YYYY-MM-DD')* the date beginning the summary
end_date | **Required** *date('YYYY-MM-DD')* the date ending the summary

###### Response
An array of shifts by day with the following info:

param|value 
-----|-----
start_time | *date(YYYY-MM-DD HH:MM:SS)* time user's shift starts for the day
end_time | *date(YYYY-MM-DD HH:MM:SS)* time user's shift ends for the day
co_workers | *array*
 | **co-worker** *string* name of co-worker
 | **from** *date(YYYY-MM-DD HH:MM:SS)* time the user will start co-working with co-worker
 | **to** *date(YYYY-MM-DD HH:MM:SS)* time the user will stop co-working with co-worker   

###### Example Request
    http://localhost:8080/readCoworkers?user_id=1&start_date=2016-03-01&end_date=2016-03-05

###### Example Response
	[
	  {
		"start_time": "2016-03-01 08:00:00",
		"end_time": "2016-03-01 12:00:00",
		"co-workers": [
		  {
			"co-worker": "Ringo Starr",
			"from": "2016-03-01 08:00:00",
			"to": "2016-03-01 12:00:00"
		  }
		]
	  },
	  {
		"start_time": "2016-03-03 08:00:00",
		"end_time": "2016-03-03 12:00:00",
		"co-workers": []
	  },
	  {
		"start_time": "2016-03-05 12:00:00",
		"end_time": "2016-03-05 16:00:00",
		"co-workers": [
		  {
			"co-worker": "John Lennon",
			"from": "2016-03-05 12:00:00",
			"to": "2016-03-05 16:00:00"
		  },
		  {
			"co-worker": "George Harrison",
			"from": "2016-03-05 12:00:00",
			"to": "2016-03-05 16:00:00"
		  }
		]
	  },
	  {
		"start_time": "2016-03-06 08:00:00",
		"end_time": "2016-03-06 16:00:00",
		"co-workers": []
	  },
	  {
		"start_time": "2016-03-08 08:00:00",
		"end_time": "2016-03-08 16:00:00",
		"co-workers": []
	  },
	  {
		"start_time": "2016-03-16 08:00:00",
		"end_time": "2016-03-16 16:00:00",
		"co-workers": []
	  },
	  {
		"start_time": "2016-03-24 08:00:00",
		"end_time": "2016-03-24 16:00:00",
		"co-workers": [
		  {
			"co-worker": "Ringo Starr",
			"from": "2016-03-24 12:00:00",
			"to": "2016-03-24 16:00:00"
		  }
		]
	  }
	]
	
	
#### *post* /createShift

Creates a new shift.  Only available to managers.

###### Request
param|value 
-----|-----
user_id | **Required** *integer* the id of the user making the request
start_time | **Required** *date(YYYY-MM-DD HH:MM:SS)* the date and time beginning the shift
end_time | **Required** *date(YYYY-MM-DD HH:MM:SS)* the date and time ending the shift
break | *float* the number of hours designated for a break on this shift
employee_id | *integer* the id of the user assigned to this shift
manager_id | *integer* the id of the user assigned to manage this shift.  If left blank it will be set to the current user as default.
###### Response
The created shift (as seen in readShift)

###### Example Request
    http://localhost:8080/createShift?user_id=5&manager_id=5&employee_id=1&start_time=2016-03-01 08:00:00&end_time=2016-03-01 12:00:00&break=0.5

###### Example Response
	{
	  "id": null,
	  "manager_id": "5",
	  "employee_id": "1",
	  "break": "0.5",
	  "start_time": 1456840800,
	  "end_time": 1456855200,
	  "error_message": null
	}
	
#### *put* /shift/:id

Updates an existing shift.  Only available to managers.  Fields will only be updated
if they are included in the request.

###### Request
param|value 
-----|-----
:id | **Required** the id of the shift being updated
user_id | **Required** *integer* the id of the user making the request
start_time | *date(YYYY-MM-DD HH:MM:SS)* the date and time beginning the shift
end_time | *date(YYYY-MM-DD HH:MM:SS)* the date and time ending the shift
break | *float* the number of hours designated for a break on this shift
employee_id | *integer* the id of the user assigned to this shift
manager_id | *integer* the id of the user assigned to manage this shift.  If left blank it will be set to the current user as default.
###### Response
The updated shift (as seen in readShift)

###### Example Request
    http://localhost:8080/shift/25?user_id=5&manager_id=6&employee_id=2&start_time=2016-03-01 08:00:00&end_time=2016-03-01 12:00:00

###### Example Response
	{
	  "id": "25",
	  "manager_id": "6",
	  "employee_id": "2",
	  "break": "0.5",
	  "start_time": 1456840800,
	  "end_time": 1456855200,
	  "error_message": null
	}
	