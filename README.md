# Radar Scheduler

This scheduler API is a sample coding project for [When-I-Work](https://github.com/wheniwork/standards/blob/master/project.md).
It's meant for demonstration purposes and not intended as a larger project. It's
built using the [Radar ADR System](https://github.com/radarphp/Radar.Project) as a
wrapper with the goal of covering the user stories listed in the original coding project's
documentation.

## Starting With Scheduler

A sample mysql database is included in the file scheduler_api.sql.  The database includes
the tables described in the project documentation as well as a modest set of sample
data.  After you import this to a local database of your choosing, you can configure
the database connection settings in [src/Domain/DB.php](src/Domain/DB.php).

After that, get the php server running by going to the project directory
in command line and entering

    php -S localhost:8080 -t web/
    
From here the API should be running at http://localhost:8080/.  To make
sure things are running as expected, try going to [http://localhost:8080/shift/1?user_id=5](http://localhost:8080/shift/1?user_id=5)!

You should see something along the lines of

    {
      "id": "1",
      "manager_id": "5",
      "employee_id": "1",
      "break": "1",
      "start_time": "2016-03-01 08:00:00",
      "end_time": "2016-03-01 12:00:00",
      "error_message": null
    }

## Documentation

API Documentation can be found [here](docs/index.md).
