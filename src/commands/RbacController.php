<?php
namespace app\commands;

use app\rbac\AuthorRule;
use app\rbac\UserPrivilegeRule;
use Yii;
use yii\console\Controller;
use yii\rbac\Rule;

class RbacController extends Controller
{
    /**
     * Syntax
     * ./yii rbac/init
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // add the rule "author"
        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        // add "createPost" permission
        $createPost = $this->createPermission('createPost', 'Create a post');

        // add "updatePost" permission
        $updatePost = $this->createPermission('updatePost', 'Update a post');

        // add "updateOwnPost" permission
        $updateOwnPost = $this->createPermission('updateOwnPost', 'Update own post', $authorRule);

        // "updateOwnPost" will be used from "updatePost"
        $auth->addChild($updateOwnPost, $updatePost);
        
        // add "author" role and give this role the "createPost", "updateOwnPost" permissions
        $author = $this->createRole('author', [$createPost, $updateOwnPost]);
        
        // add the rule "userPrivilege"
        $userPrivilegeRule = new UserPrivilegeRule();
        $auth->add($userPrivilegeRule);

        // add "manageUser" permission
        $manageUser = $this->createPermission('manageUser', 'Manage user');

        // add "admin" role and give this role the "manageUser", "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $this->createRole('admin', [$manageUser, $updatePost, $author], $userPrivilegeRule);
    }

    /**
     * Create a permission.
     * @param string $name Permission name.
     * @param string $description
     * @param Rule $rule Rule object attacheed with permission.
     * @return Permission
     */
    private function createPermission($name, $description, Rule $rule = null)
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission($name);
        $permission->description = $description;
        $auth->add($permission);
        // associate the rule with permission.
        if ($rule) {
            $permission->ruleName = $rule->name;
            $auth->add($permission);
        }
        return $permission;
    }

    /**
     * Create a role.
     * @param string $name Role name.
     * @param mixed $children A Permission or Role or an array of them that is attached which created permission/role.
     * @param Rule $rule Rule object attacheed with permission.
     * @return Role
     */
    private function createRole($name, $children = null, Rule $rule = null)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->createRole($name);
        $auth->add($role);
        // associate the rule with role.
        if ($rule) {
            $role->ruleName = $rule->name;
            $auth->add($role);
        }
        // Add permission and another role as child.
        if ($children) {
            // Assure $children is an array.
            if (!is_array($children)) {
                $children = [$children];
            }
            foreach ($children as $child) {
                $auth->addChild($role, $child);
            }
        }
        return $role;
    }
}