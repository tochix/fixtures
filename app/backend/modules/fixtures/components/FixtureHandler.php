<?php

namespace backend\modules\fixtures\components;

use Yii;
use yii\base\Component;

/**
 * Class for processing fixtures
 */
class FixtureHandler extends Component
{
	private $relationKeys = [];
	private $fixtureData = [];

	function _construct()
	{
	}

	/**
	 * Parses the fixture's json feed and persists data to the models.
	 */
	public function process($jsonFeed)
	{
		$fixtureParser = new FixtureParser($jsonFeed);
		$this->fixtureData = $fixtureParser->parse();
		$this->saveModels();
	}

	/**
	 * Saves the parsed data to db, maintaining dependency/relationship order
	 */
	protected function saveModels()
	{
		$this->saveTeams();
		$this->saveTeamPlayers();
		$this->saveLocation();
		$this->saveFixture();
		$this->saveFixturePlayers();
		$this->saveFixtureGoals();
	}

	/**
	 * Saves teams
	 */
	protected function saveTeams()
	{
		foreach ((array) $this->fixtureData['teams'] as $team) {
			$record = $this->saveOrUpdate(['name' => $team], 'Team');
		}
	}

	/**
	 * Saves team players
	 */
	protected function saveTeamPlayers()
	{
		foreach ((array) $this->fixtureData['team_players'] as $team => $players) {
			$teamId = $this->getRelationValue('name', $team, 'Team');
			if (empty($teamId)) {
				continue;
			}

			foreach ($players as $player) {
				$record = $this->saveOrUpdate(['player_name' => $player, 'team_id' => $teamId], 'TeamPlayers');
			}
		}
	}

	/**
	 * Saves fixture location
	 */
	protected function saveLocation()
	{
		$this->saveOrUpdate(['city' => $this->fixtureData['location']], 'Location');
	}

	/**
	 * Saves fixture meta-data
	 */
	protected function saveFixture()
	{
		$fixture = $this->fixtureData['fixture'];
		$locationId = $this->getRelationValue('city', $fixture['location'], 'Location');

		if (!empty($locationId)) {
			unset($fixture['location']);
			$fixture['location_id'] = $locationId;
			$this->saveOrUpdate($fixture, 'Fixture');
		}
	}

	/**
	 * Saves fixtures's players
	 */
	protected function saveFixturePlayers()
	{
		if (($fixtureId = $this->getFixtureId()) == null) {
			return;
		}	

		foreach ((array) $this->fixtureData['team_players'] as $team => $players) {
			$teamId = $this->getRelationValue('name', $team, 'Team');
			if (empty($teamId)) {
				continue;
			}

			foreach ($players as $player) {
				$playerId = $this->getRelationValue('player_name', $player, 'TeamPlayers');
				$fixturePlayer = ['player_id' => $playerId, 'team_id' => $teamId, 'fixtureId' => $fixtureId];
				$record = $this->saveOrUpdate($fixturePlayer, 'FixturePlayers');
			}
		}
	}

	/**
	 * Gets fixture's id
	 * @return int|null
	 */
	private getFixtureId()
	{
		if (!isset($this->relationKeys['Fixture'][0]['id'])) {
			return null;
		}

		$fixtureId = $this->relationKeys['Fixture'][0]['id'];
		return $fixtureId;
	}

	/**
	 * Saves the fixture's goals
	 */
	protected function saveFixtureGoals()
	{
		if (($fixtureId = $this->getFixtureId()) == null) {
			return;
		}	
		
		foreach ((array) $this->fixtureData['fixture_goals'] as $team => $goals) {
			$teamId = $this->getRelationValue('name', $team, 'Team');
			if (empty($teamId)) {
				continue;
			}

			foreach ($goals as $goalData) {
				$playerId = $this->getRelationValue('player_name', $goalData['player'], 'TeamPlayers');
				if (empty($playerId)) {
					continue;
				}

				$params = ['fixture_id' => $fixtureId, 'teamId' => $teamId, 'playerId' => $playerId];
				$record = FixturePlayers::findOne($params);
				if ($record != null) {
					$data = ['fixture_player_id' => $record->id, 'scored_at' => $goalData['goal_time']];
					$record = $this->saveOrUpdate($data, 'FixtureGoals');
					$this->sendEventSMS($goalData['player'], $team, $goalData['goal_time']);
				}				
			}
		}
	}

	/**
	 * Fetches the cached relation's foregin key value
	 */
	protected getRelationValue($recordKey, $recordValue, $model, $relationKey = 'id')
	{
		$relationValue = null;

		foreach ((array) $this->relationKeys[$model] as $idx => $modelParams) {
			foreach ($modelParams as $key => $value) {
				if ($recordKey == $key && $recordValue == $value) {
					$relationValue = $this->relationKeys[$model][$idx][$relationKey];
					return $relationValue;
				}	
			}
		}

		return $relationValue;
	}

	/**
	 * Decides wether to save or update a record. 
	 */
	protected function saveOrUpdate($params, $model, $primaryKey = 'id')
	{
		$record = {$model}::findOne($params);

		if ($record != null && !empty($record->{$primaryKey})) {
		
		} else {
			$modelInstance = new $model();
			$modelInstance->created = date('Y-m-d H:i:s');

			foreach ($params as $key => $value) {
				$modelInstance->{$key} = $value;
			}

			if ($modelInstance->save()) {
				$record = $modelInstance;
			}
		}

		$params[$primaryKey] = $record->{$primaryKey};
		$this->relationKeys[$model][] = $params;
	}

	/**
	 * Stub for sending fixture event SMS
	 */
	protected function sendEventSMS($player, $team, $eventTime, $eventType = 'goal')
	{

	}
}