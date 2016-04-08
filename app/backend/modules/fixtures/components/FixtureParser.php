<?php

namespace backend\modules\fixtures\components;

use yii\base\Component;

/**
 * Fixture parser class decomposes a json string to an array closely structured 
 * to it's model schema relationships.
 * This class assumes the structure of the decoded json to be:
 * fixtures feed
 * -------------
 * [
 *   	'feed_generated_at' => '2016-02-14 19:30:45',
 *    	'teams' => [
 *   		'home' => 'Manchester City',
 *	  		'away' => 'Leicester',
 *    	],
 *   	'location' => 'Etihad',
 *    	'kickoff' => '2016-02-14 20:00:00',
 *   	'result' => '',
 * ],
 * match report feed
 * -----------------
 * [
 *    	'feed_generated_at' => '2016-02-14 20:47:13',
 *    	'teams' => [
 *    		'home' => 'Manchester City',
 *    		'away' => 'Leicester',
 *    	],
 *    	'location' => 'Etihad',
 *    	'kickoff' => '2016-02-14 20:00:00',
 *    	'result' => '1-0',
 *    	'players' => [
 *    		'home_team' => [
 *    			'player 1',
 *    			'player 2',
 *    		],
 *    		'away_team' => [
 *    			'player 3',
 *    			'player 4',
 *    		],
 *    	],
 *    	'goals' => [
 *    		'home_team' => [
 *    			[
 *    				'player' => 'player 2',
 *    				'goal_time' => '2016-02-14 20:23:15',
 *    			],
 *    		],
 *   		'away_team' => [],
 *    	],
 *    	'fouls' => [
 *    		'home_team' => [
 *    			[
 *    				'player' => 'player 1',
 *    				'offence' => 'yellow card',
 *    				'offence_time' => '2016-02-14 20:08:25',
 *    			],
 *    		],
 *    		'away_team' => [
 *    			[
 *    				'player' => 'player 3',
 *    				'offence' => 'red card',
 *    				'offence_time' => '2016-02-14 20:16:45',
 *    			],
 *    			[
 *    				'player' => 'player 4',
 *    				'offence' => 'yellow card',
 *    				'offence_time' => '2016-02-14 20:22:33',
 *    			],
 *    		],
 *    	],
 * ],.
 */
class FixtureParser extends Component
{
    private $feedData = [];
    //private $normalizedData = [];
    private $normalizedData = [
        'teams' => [],
        'team_players' => [],
        'location' => '',
        'fixture' => [],
        'fixture_goals' => [],
        'fixture_fouls' => [],
    ];

    /**
     * Constructor initializes the json feed.
     *
     * @param string $jsonFeed the json string from the feed.
     */
    public function __construct($jsonFeed)
    {
        try {
            $this->feedData = json_decode($jsonFeed, true);
        } catch (\Exception $error) {
            exit($error->getMessage());
        }
    }

    /**
     * Parses the json feed.
     *
     * @return array
     */
    public function parse()
    {
        return $this->getNormalizedData();
    }

    /**
     * Normalizes the json feed to the underlying models.
     *
     * @return array
     */
    protected function getNormalizedData()
    {
        if (!is_array($this->feedData) || empty($this->feedData)) {
            return $this->normalizedData;
        }

        $this->normalizedData = [
            'teams' => $this->getTeams(),
            'team_players' => $this->getTeamPlayers(),
            'location' => $this->getLocation(),
            'fixture' => $this->getFixture(),
            'fixture_goals' => $this->getFixtureEvents('goals'),
            'fixture_fouls' => $this->getFixtureEvents('fouls'),
        ];

        return $this->normalizedData;
    }

    /**
     * Get the teams in the feed.
     *
     * @return array
     */
    protected function getTeams()
    {
        $teams = [];
        foreach ((array) $this->feedData['teams'] as $team) {
            $teams[] = $team;
        }

        return $teams;
    }

    /**
     * Gets the team players in the feed.
     *
     * @return array
     */
    protected function getTeamPlayers()
    {
        $teamPlayers = [];
        if (($teams = $this->getFixtureTeams()) == null || empty($this->feedData['players'])) {
            return $teamPlayers;
        }

        foreach ((array) $this->feedData['players'] as $teamSide => $players) {
            $team = ($teamSide == 'home_team') ? $teams['home'] : $teams['away'];
            foreach ($players as $player) {
                $teamPlayers[$team][] = $player;
            }
        }

        return $teamPlayers;
    }

    /**
     * Gets the name of the team side.
     *
     * @param string $side accepts 'home' or 'away'
     *
     * @return string|null
     */
    protected function getTeamSide($side)
    {
        $teamSide = null;
        if ($side != 'home' && $side != 'away') {
            return $teamSide;
        }

        if (!empty($this->feedData['teams']) && !empty($this->feedData['teams'][$side])) {
            $teamSide = $this->feedData['teams'][$side];
        }

        return$teamSide;
    }

    /**
     * Gets the fixture's location.
     *
     * @return string
     */
    protected function getLocation()
    {
        $location = (!empty($this->feedData['location'])) ? $this->feedData['location'] : null;

        return $location;
    }

    /**
     * Gets fixture's data.
     *
     * @return array
     */
    protected function getFixture()
    {
        $fixture = [
            'home_team' => $this->getTeamSide('home'),
            'away_team' => $this->getTeamSide('away'),
            'location' => $this->getLocation(),
            'fixture_date' => (!empty($this->feedData['kickoff'])) ? $this->feedData['kickoff'] : null,
            'result' => (!empty($this->feedData['result'])) ? $this->feedData['result'] : null,
        ];

        return $fixture;
    }

    /**
     * Gets the events(goals/fouls) in a fixture.
     *
     * @param string $eventType
     *
     * @return array
     */
    protected function getFixtureEvents($eventType)
    {
        $fixtureEvents = [];
        if ((($teams = $this->getFixtureTeams()) == null) || empty($this->feedData[$eventType])) {
            return $fixtureEvents;
        }

        foreach ((array) $this->feedData[$eventType] as $teamSide => $teamEvents) {
            $team = ($teamSide == 'home_team') ? $teams['home'] : $teams['away'];
            foreach ($teamEvents as $event) {
                $fixtureEvents[$team][] = $event;
            }
        }

        return $fixtureEvents;
    }

    /**
     * Gets the teams in a fixture.
     *
     * @return array|null
     */
    protected function getFixtureTeams()
    {
        $teams = [];
        $teams['home'] = $this->getTeamSide('home');
        $teams['away'] = $this->getTeamSide('away');

        if (empty($teams['home']) || empty($teams['away'])) {
            return;
        }

        return $teams;
    }
}
