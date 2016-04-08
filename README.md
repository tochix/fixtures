# Game Fixtures Feed Implementation
Fixture feed was implemented using the Yii framework. Yii was used because like any other PHP framework, uses the MVC design pattern and also has an easy to use ActiveRecord ORM.

A module was created to contain all fixture related implementation. You can find this under "app/backend/modules/fixture". The module comes with migration files (see "app/backend/modules/fixture/migrations") that creates the normalized schemas required for the feed. Models (see "app/backend/modules/fixture/models") were generated based upon the schema design.

The main part of the module can be found in the "components" folder. There you'll find a feed parser and feed handler class.

The FeedParser class handles parsing of the fixture's JSON feed. This class is initialized by the FeedHandler class which handles persisting the feed's data to the models.


# Unit Testing
There's now a basic unit testing case that can be found at "app/tests/codeception/backend/unit/FixturesTest.php". This can be run by:
```
 codecept run unit unit/FixturesTest.php
 codecept run unit unit/FixturesTest.php:testMatchReportFeed
```

Note: This requires a dependency on the Codeception library.