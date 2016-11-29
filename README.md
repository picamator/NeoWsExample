NeoWsExample
============

NeoWsExample is an example application to show how to use [NeoWsClient](https://github.com/picamator/NeoWsClient) (client for NASA Open Api "[Near Earth Object Web Service](https://api.nasa.gov/neo/?api_key=DEMO_KEY)")
with [Symfony 2.8](http://symfony.com/blog/symfony-2-8-0-released).

Requirements
------------
* [PHP 5.6](http://php.net/manual/en/migration56.new-features.php)
* [MongoDB](https://www.mongodb.com/)
* [Mongo](https://pecl.php.net/package/mongo)

Installation
------------
NeoWsExample has virtualization development environment based on [Laravel's Vagrant Homestead](https://laravel.com/docs/5.3/homestead). 

Please follow steps in [instructions](doc/INSTALLATION.md) to configure your dev environment.

Database
--------
NeoWsExample uses MongoDB database to save NeoWs data.

Database has those documents:

* Neo: keeps Neo objects
* SyncLog: journal for synchronization history

### Neo document
Neo document saves Neo objects with mapping displayed in table bellow.

 NeoWs Raw Response                                             | Neo Document  | Description
 ---                                                            | ---           | ---
 near_earth_object[index]                                       | data          | Save the `index` as date
 neo_reference_id                                               | reference     | Reference identifier
 name                                                           | name          | Asteroid name     
 close_approach_data[*].relative_velocity.kilometers_per_hour   | speed         | Average speed km/h over all `close_approach_data`. Zero means that there no `close_approach_data` data present.
 is_potentially_hazardous_asteroid                              | isHazardous   | Indicate if current object is hazardous or not
 
### SyncLog document
SyncLog document uses to keep `start_date`, `end_date` NeoWs request parameters to prevent duplication run.
Table bellow shows how those parameters match with the document.
 
 NeoWs Request Parameter    | SynLog Document   | Description
 ---                        | ---               | ---
 start_date                 | startDate         | First date range parameter
 end_date                   | endDate           | Second date range parameter
 
Examples
--------

### Command to synchronize NEOs data with database
Command `php app/console feed:sync`:

1. Gets data from NeoWs by NeoWsClient using `GET /rest/v1/feed?detailed=0` resource for last 3 Days
2. Saves data to database
3. Prevents from duplication run

Contribution
------------
If you find this project worth to use please add a star. Follow changes to see all activities.
And if you see room for improvement, proposals please feel free to create an issue or send pull request.
Here is a great [guide to start contributing](https://guides.github.com/activities/contributing-to-open-source/).

Please note that this project is released with a [Contributor Code of Conduct](http://contributor-covenant.org/version/1/4/).
By participating in this project and its community you agree to abide by those terms.

License
-------
NeoWsExample is licensed under the MIT License. Please see the [LICENSE](LICENSE.txt) file for details.
