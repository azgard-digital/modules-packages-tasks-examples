<?php
/**
 * Created by PhpStorm.
 * User: Igor Naydyonnyy
 * Date: 13.06.18
 * Time: 18:07
 */

namespace components\multiLevelsCache\behaviors;

use components\ActiveRecordBehavior;
use components\ActiveRecord;

/**
 * Class ClearCache
 * @package components\multiLevelsCache\behaviors
 * @author Igor Naydyonnyy
 */
class ClearCache extends ActiveRecordBehavior
{
    public function events()
    {
        return array_merge(parent::events(), [
            'onAfterDelete' => 'afterDelete',
            'onAfterSave' => 'afterSave',
        ]);
    }

    public function afterSave($event)
    {
        /** @var ActiveRecord $model */
        $model = $event->sender;
        $model->clearModelCache($model->getPrimaryKey());
    }

    public function afterDelete($event)
    {
        /** @var ActiveRecord $model */
        $model = $event->sender;
        $model->clearModelCache($model->getPrimaryKey());
    }
}