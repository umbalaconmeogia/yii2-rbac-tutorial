<?php
namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BaseAppModel extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * Find one object that match $condition.
     * If not exist, create new one with specified condition.
     * @param array  $condition
     * @param boolean $saveDb Save record into DB or not incase create new.
     * @return \batsg\models\BaseModel
     */
    public static function findOneCreateNew($condition, $saveDb = FALSE, $className = null)
    {
        if (!$className) {
            $className = static::className();
        }
        $result = $className::findOne($condition);
        if (!$result) {
            $result = \Yii::createObject($className);
            \Yii::configure($result, $condition);
            if ($saveDb) {
                self::saveThrowErrorModel($result);
            }
        }
        return $result;
    }

    /**
     * Save a model, write error to log and throw exception if error occurs.
     * @param ActiveRecord $model
     * @param string $errorMessage
     * @throws \Exception
     */
    public static function saveThrowErrorModel($model, $errorMessage = NULL)
    {
        if ($errorMessage === NULL) {
            $errorMessage = "Error while saving " . self::toStringModel($model);
        }
        if (!self::saveLogErrorModel($model, $errorMessage)) {
            throw new \Exception($errorMessage);
        }
    }

   /**
     * Save this model, write error to log and throw exception if error occurs.
     * @param string $errorMessage
     * @throws \Exception
     */
    public function saveThrowError($errorMessage = NULL)
    {
        return self::saveThrowErrorModel($this, $errorMessage);
    }

    /**
     * Save a model, write error to log if error occurs.
     * @param ActiveRecord $model
     * @param string $errorMessage
     * @return boolean
     */
    public static function saveLogErrorModel($model, $errorMessage = NULL)
    {
        if ($errorMessage === NULL) {
            $errorMessage = "Error while saving " . self::toStringModel($model);
        }
        $result = $model->save();
        if (!$result) {
            self::logErrorModel($model, $errorMessage);
        }
        return $result;
    }

    /**
     * Log error of a model.
     * @param ActiveRecord $model
     * @param string $message The message to be exported first.
     * @param string $category
     */
    public static function logErrorModel($model, $message = NULL, $category = 'application')
    {
        if ($message) {
            Yii::error($message, $category);
        }
        Yii::error($model->tableName() . " " . print_r($model->attributes, TRUE), $category);
        Yii::error(print_r(self::getErrorMessagesModel($model), TRUE), $category);
    }

    /**
     * Create a string that describe all fields of an model object.
     * @param ActiveRecord $model
     * @param mixed $fields String or string array. If NULL, all attributes are used.
     * @return string.
     */
    public static function toStringModel($model, $fields = NULL)
    {
        // Get attributes.
        if ($fields === NULL) {
            $fields = array_keys($model->attributes);
        }
        if (!is_array($fields)) {
            $fields = array($fields);
        }
        $info = [];
        foreach ($fields as $field) {
            $info[] = "$field: {$model->$field}";
        }

        // Get class name.
        $className = get_class($model);
        $className = ($pos = strrpos($className, '\\')) === FALSE ? $className : substr($className, $pos + 1);

        return "$className(" . join(', ', $info) . ')';

    }
    /**
     * Get all errors on a model.
     * @param ActiveRecord $model
     * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
     * @return array errors for all attributes or the specified attribute. Empty array is returned if no error.
     */
    public static function getErrorMessagesModel($model, $attribute = NULL)
    {
        if ($attribute === NULL) {
            $attribute = $model->attributes();
        }
        if (!is_array($attribute)) {
            $attribute = array($attribute);
        }
        $errors = array();
        foreach ($attribute as $attr) {
            if ($model->hasErrors($attr)) {
                $errors = array_merge($errors, array_values($model->getErrors($attr)));
            }
        }
        return $errors;
    }
}