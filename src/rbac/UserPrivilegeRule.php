<?php
namespace app\rbac;

use app\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Checks if user privilege matches
 */
class UserPrivilegeRule extends Rule
{
    public $name = 'userPrivilege';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $privilege = Yii::$app->user->identity->privilege;
            if ($item->name === 'admin') {
                return $privilege == User::PRIVILEGE_ADMIN;
            } elseif ($item->name === 'author') {
                return $privilege == User::PRIVILEGE_ADMIN || $privilege == User::PRIVILEGE_NORMAL;
            }
        }
        return false;
    }
}