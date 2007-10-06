<?php
class Objective extends ScormAppModel {

	var $name = 'Objective';
	var $validate = null;
	var $primaryKey = 'id';
	var $table = 'objectives';
	var $hasOne = array(
			'MapInfo' => array('className' => 'MapInfo',
								'foreignKey' => 'objective_id',
								'dependent' => true)
	);
	
	function __construct() {
		$this->validate = array(
			'objectiveID' => array(
				'required' =>  array(
					'rule' => VALID_NOT_EMPTY,
					'message' => __('scormplugin.objective.objectiveid.empty', true),
					'required' => true,
				)
			),
			'satisfiedByMeasure' => array(
				'required' =>  array(
					'rule' => IS_BOOLEAN,
					'message' => __('scormplugin.objective.satisfiedbymeasure.boolean', true),
					'required' => false
				)
			),
			'minNormalizedMeasure' => array(
				'required' =>  array(
					'rule' => 'decimal',
					'message' => __('scormplugin.objective.minnormalizedmeasure.decimal', true),
					'required' => false),
				'greater' =>  array(
					'rule' => array('comparison','>=',-1),
					'message' => __('scormplugin.objective.minnormalizedmeasure.between', true),
					'required' => false),
				'less' =>  array(
					'rule' => array('comparison','<=',1),
					'message' => __('scormplugin.objective.minnormalizedmeasure.between', true),
					'required' => false)
			)	
		);
	}
}
?>