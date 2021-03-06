<?php
/* SVN FILE: $Id$ */
/**
 * Ósmosis LMS: <http://www.osmosislms.org/>
 * Copyright 2008, Ósmosis LMS
 *
 * This file is part of Ósmosis LMS.
 * Ósmosis LMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Ósmosis LMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Ósmosis LMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @filesource
 * @copyright		Copyright 2008, Ósmosis LMS
 * @link			http://www.osmosislms.org/
 * @package			org.osmosislms
 * @subpackage		org.osmosislms.app
 * @since			Version 2.0 
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 */
class HookableBehavior extends ModelBehavior {
	
	/**
	 * Setups the behavior
	 *
	 * @param object $model the model that will use the behavior
	 * @param array $config array with config option for the behavior
	 * @return void
	 */
	
	function setup(&$model, $config = array()) {
        if( !is_null( $config ) && is_array( $config ) ) {
            $this->settings = array_merge( $this->settings, $config );
        }
     }

	/**
	 * Find plugin's comoponent classes that contains a $hookname method
	 *
	 * @param object $model the model that executes the callback
	 * @param string $hookName name of the callback function to find
	 * @return array with instantiated component objects
	 * @access private
	 */
	
	function __getHookObjects(&$model,$hookName){
		$hooks = array();
		$plugins = Configure::listObjects('plugin');
		foreach ($plugins as $key => $plug) {
			$className = $plug . $model->name . 'Hook';
			if(ClassRegistry::isKeySet($className.'Component') || App::import('Component',$plug . '.' . $className)) {
				$className = $className. 'Component';
				if(ClassRegistry::isKeySet($className)) {
					$hookClass =& ClassRegistry::getObject($className);
				}else {
					$hookClass =& new $className;
					ClassRegistry::addObject($className,$hookClass);
				}
				if(method_exists($hookClass,$hookName)) {
					$hooks[] =& $hookClass;
				}
			}
		}
		return $hooks;
	}
	
	/**
	 * Fuction called before validating model data
	 *
	 * @param object $model the model that executes the callback
	 * @return boolean
	 * @see ModelBehavior
	 */
	
	function beforeValidate(&$model) {
		$return = true;
		$hooks = $this->__getHookObjects($model,'beforeValidate');
		foreach ($hooks as $hook){
			$return = $return && $hook->beforeValidate($model);	
		}
		return $return;
	}
	
	/**
	 * Fuction called before savind model data and after validating it.
	 *
	 * @param object $model the model that executes the callback
	 * @return boolean
	 * @see ModelBehavior
	 */
	function beforeSave(&$model) {
		$return = true;
		$hooks = $this->__getHookObjects($model,'beforeSave');
		foreach ($hooks as $hook){
			$return = $return && $hook->beforeSave($model);			
		}
		return $return;
	}

	/**
	 * Fuction called before executing a find command
	 *
	 * @param object $model the model that executes the callback
	 * @param array $query Query information like conditions, order, etc.
	 * @return boolean
	 * @see ModelBehavior
	 */
	function beforeFind(&$model, $query) {
		$return = true;
		$hooks = $this->__getHookObjects($model,'beforeFind');
		foreach ($hooks as $hook){
			$return = $return && $hook->beforeFind($model, $query);			
		}
		return $return;
	}

	/**
	 * Fuction called before executing a delete command
	 *
	 * @param object $model the model that executes the callback
	 * @param boolean $cascade if the query command will cascade to related models
	 * @return boolean
	 * @see ModelBehavior
	 */
	function beforeDelete(&$model, $cascade) {
		$return = true;
		$hooks = $this->__getHookObjects($model,'beforeDelete');
		foreach ($hooks as $hook){
			$return = $hook->beforeDelete($model, $cascade) && $return;			
		}
		return $return;
	}

	/**
	 * Fuction called after executing a save command
	 *
	 * @param object $model the model that executes the callback
	 * @param boolean $created if the data was just created or updated
	 * @return boolean
	 * @see ModelBehavior
	 */
	function afterSave(&$model, $created) {
		$hooks = $this->__getHookObjects($model,'afterSave');
		foreach ($hooks as $hook){
			$hook->afterSave($model, $created);	
		}
	}

	/**
	 * Fuction called after executing a find command
	 *
	 * @param object $model the model that executes the callback
	 * @param array $results results of the find command
	 * @param $primary if the model was queried directly or as an association
	 * @return array resutls of the find command
	 * @see ModelBehavior
	 */
	function afterFind(&$model, $results, $primary) {
		$hooks = $this->__getHookObjects($model,'afterFind');
		foreach ($hooks as $hook){
			$results = $hook->afterFind($model, $results, $primary) && $return;			
		}
		return $results;
	}
	
	/**
	 * Fuction called after executing a delete command
	 *
	 * @param object $model the model that executes the callback
	 * @see ModelBehavior
	 */
	function afterDelete(&$model) {
		$hooks = $this->__getHookObjects($model,'afterDelete');
		foreach ($hooks as $hook){
			$hook->afterDelete($model);			
		}
	}
}
?>
