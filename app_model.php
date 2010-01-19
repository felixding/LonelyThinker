<?php
/* SVN FILE:  $Id: app_model.php 39 2009-07-16 10:03:24Z  $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 5
 *
 * Licensed under The BSD License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2007-2009, Felix Ding (http://dingyu.me)
 * @link          http://lonelythinker.org Project LonelyThinker
 * @package       LonelyThinker
 * @author		  $LastChangedBy: $
 * @version       $Revision: 39 $
 * @modifiedby    $LastChangedBy: $
 * @lastmodified  $Date: 2009-07-16 18:03:24 +0800 (Thu, 16 Jul 2009) $
 * @license       http://www.opensource.org/licenses/bsd-license.php The BSD License
 */
class AppModel extends Model
{
	/**
	 * have all of the validation error messages translated by default
	 *
	 * @date 2009-07-03
	 */
	function invalidate($field, $value = true) {
		return parent::invalidate($field, __($value, true));
	}


	/**
	 * clean the cache after saving
	 *
	 * @date 2009-03-14
	 */
	public function afterSave()
	{
		Cache::clear();
	}
    
	/**
	 * othAuth component uses this function
	 */
	function unbindAll($params = array())
	{
		foreach($this->__associations as $ass)
		{
			if(!empty($this->{$ass}))
			{
				$this->__backAssociation[$ass] = $this->{$ass};
				if(isset($params[$ass]))
				{
					foreach($this->{$ass} as $model => $detail)
					{
						if(!in_array($model,$params[$ass]))
						{
							$this->__backAssociation = array_merge($this->__backAssociation, $this->{$ass});
							unset($this->{$ass}[$model]);
						}
					}
				}
				else
				{
					$this->__backAssociation = array_merge($this->__backAssociation, $this->{$ass});
					$this->{$ass} = array();
				}

			}
		}
		return true;
	}

	/**
	 * othAuth component uses this function
	 */
	 
	function useModel($params = array())
	{
		if( !is_array($params) )
		$params = array($params);
			
		$classname = get_class($this); // for debug output
			
		foreach($this->__associations as $ass)
		{
			if(!empty($this->{$ass}))
			{
				// This model has an association '$ass' defined (like 'hasMany', ...)
					
				$this->__backAssociation[$ass] = $this->{$ass};

				foreach($this->{$ass} as $model => $detail)
				{
					if(!in_array($model,$params))
					{
						//debug("Ignoring association $classname <i>$ass</i> $model... ");
						$this->__backAssociation = array_merge($this->__backAssociation, $this->{$ass});
						unset($this->{$ass}[$model]);
					}

				}
					
			}
		}
			
		return true;
	}
}
?>