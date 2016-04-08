<?php

namespace tests\codeception\backend\unit;

use backend\modules\fixtures\components\FixtureHandler;
use backend;

class FixturesTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testFixturesFeed()
    {
        $fixture = [
            'feed_generated_at' => '2016-02-14 19:30:45',
            'teams' => [
                'home' => 'Manchester City',
                'away' => 'Leicester',
            ],
            'location' => 'Etihad',
            'kickoff' => '2016-02-14 20:00:00',
            'result' => '',
        ];
        $fixture = json_encode($fixture);

        $fixtureHandler = new FixtureHandler();
        $fixtureHandler->process($fixture);
    }

    public function testMatchReportFeed()
    {
        $fixture = [
            'feed_generated_at' => '2016-02-14 20:47:13',
            'teams' => [
                'home' => 'Manchester City',
                'away' => 'Leicester',
            ],
            'location' => 'Etihad',
            'kickoff' => '2016-02-14 20:00:00',
            'result' => '1-0',
            'players' => [
                'home_team' => [
                    'player 1',
                    'player 2',
                ],
                'away_team' => [
                    'player 3',
                    'player 4',
                ],
            ],
            'goals' => [
                'home_team' => [
                    [
                        'player' => 'player 2',
                        'goal_time' => '2016-02-14 20:23:15',
                    ],
                ],
                'away_team' => [],
            ],
            'fouls' => [
                'home_team' => [
                    [
                        'player' => 'player 1',
                        'offence' => 'yellow card',
                        'offence_time' => '2016-02-14 20:08:25',
                    ],
                ],
                'away_team' => [
                    [
                        'player' => 'player 3',
                        'offence' => 'red card',
                        'offence_time' => '2016-02-14 20:16:45',
                    ],
                    [
                        'player' => 'player 4',
                        'offence' => 'yellow card',
                        'offence_time' => '2016-02-14 20:22:33',
                    ],
                ],
            ],
        ];
        $fixture = json_encode($fixture);
        $this->_clearModels();

        $team = backend\modules\fixtures\models\Team::findOne(['name' => 'Leicester']);
        $this->assertNull($team);

        $fixtureHandler = new FixtureHandler();
        $fixtureHandler->process($fixture);

        $team = backend\modules\fixtures\models\Team::findOne(['name' => 'Leicester']);
        $this->assertNotNull($team);
        $this->assertEquals('Leicester', $team->name);
    }

    private function _clearModels()
    {
        backend\modules\fixtures\models\FixtureFouls::deleteAll();
        backend\modules\fixtures\models\FixtureGoals::deleteAll();
        backend\modules\fixtures\models\FixturePlayers::deleteAll();
        backend\modules\fixtures\models\Fixture::deleteAll();
        backend\modules\fixtures\models\Location::deleteAll();
        backend\modules\fixtures\models\TeamPlayers::deleteAll();
        backend\modules\fixtures\models\Team::deleteAll();
    }
}
