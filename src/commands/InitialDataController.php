<?php
namespace app\commands;

use app\models\Post;
use app\models\User;
use Faker\Provider\Lorem;
use yii\console\Controller;

class InitialDataController extends Controller
{
    const USER_NUM = 10;
    const USER_POST_NUM = 10;

    /**
     * Syntax:
     * ./yii initial-data
     */
    public function actionIndex()
    {
        $this->initUser();
        $this->initPost();
        echo "DONE";
    }

    private function initUser()
    {
        User::getDb()->transaction(function() {
            for ($userIndex = 1; $userIndex < self::USER_NUM; $userIndex++) {
                $username = "user$userIndex";
                $user = User::findOneCreateNew(['username' => $username]);
                $user->password = "password$userIndex";
                $user->privilege = $userIndex == 1 ? User::PRIVILEGE_ADMIN : User::PRIVILEGE_NORMAL;
                $user->email = "{$username}@example.com";
                $user->generateAuthKey();
                $user->saveThrowError();
            }
        });
    }

    private function initPost()
    {
        User::getDb()->transaction(function() {
            for ($userIndex = 1; $userIndex < self::USER_NUM; $userIndex++) {
                $username = "user$userIndex";
                $user = User::findOneCreateNew(['username' => $username]);
                for ($postIndex = 1; $postIndex < self::USER_POST_NUM; $postIndex++) {
                    $title = "Post {$userIndex}-{$postIndex}";
                    $content = "{$userIndex}-{$postIndex} " . Lorem::text();
                    $post = Post::findOneCreateNew(['title' => $title]);
                    $post->content = $content;
                    $post->created_by = $user->id;
                    $post->updated_by = $user->id;
                    $post->saveThrowError();
                }
            }
        });
    }
}

