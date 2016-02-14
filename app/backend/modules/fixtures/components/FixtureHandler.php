<?php

namespace backend\modules\fixtures\components;

use Yii;
use yii\base\Component;

class FixtureHandler extends Component
{
	private $relationKeys = [];
	private $fixtureData = [];

	function _construct()
	{
	}

	public function process($jsonFeed)
	{
		$fixtureParser = new FixtureParser($jsonFeed);
		$this->fixtureData = $fixtureParser->parse();
		$this->saveModels();
	}

	protected function saveModels()
	{
		$this->saveTeams();
	}

	protected function saveTeams()
	{
		foreach ($this->fixtureData['teams'] as $team) {
			$record = $this->saveOrUpdate(['name' => $team], 'Team');

		}
	}

	protected function saveOrUpdate($params, $model, $primaryKey = 'id')
	{
		$record = {$model}::findOne($params);

		if ($record != null && !empty($record->{$primaryKey})) {
			$params[$primaryKey] = $record->{$primaryKey};
			$this->relationKeys[$model] = $params;
		} else {
			$modelInstance = new $model();
			foreach ($params as $key => $value) {
				$modelInstance->{$key} = $value;
			}

			$modelInstance->save();
		}
	}
}