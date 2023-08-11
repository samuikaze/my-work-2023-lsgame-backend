<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    // 取得最新消息清單
    $router->get('/news', 'NewsController@getNewsList');
    // 取得消息內容
    $router->get('/news/{id}', 'NewsController@getNews');
    // 取得作品清單
    $router->get('/products', 'ProductController@getProductList');
    // 取得商品列表
    $router->get('/goods', 'GoodController@getGoodsList');
    // 取得商品資料
    $router->get('/goods/{id}', 'GoodController@getGood');
    // 以 ID 取得購物車內商品詳細資料
    $router->post('/goods/cart', 'GoodController@getCartGoodsByIds');
    // 取得討論板清單
    $router->get('/forums/boards', 'ForumController@getForumBoardsList');
    // 取得討論版文章
    $router->get('/forums/boards/{id}', 'ForumController@getBoardPosts');
    // 取得文章本體
    $router->get('/forums/boards/{board_id}/posts/{post_id}', 'ForumController@getPost');
    // 取得指定文章底下的回應
    $router->get('/forums/boards/{board_id}/posts/{post_id}/replies', 'ForumController@getReplies');
    // 取得指定的回應
    $router->get('/forums/boards/{board_id}/posts/{post_id}/replies/{reply_id}', 'ForumController@getReplyById');
    // 取得所有文章種類
    $router->get('/forums/commons/post/types', 'ForumController@getPostTypeList');
    // 需要經過驗證的路由
    $router->group(['middleware' => ['verify.auth']], function () use ($router) {
        // 清除購物車
        $router->delete('/goods/cart/{user_id}', 'GoodController@resetCart');
        // 儲存購物車內容
        $router->post('/goods/cart/save', 'GoodController@saveCart');
        // 以帳號 ID 取得已儲存購物車內容
        $router->get('/goods/cart/saved', 'GoodController@getSavedCart');
        // 發布新文章
        $router->post('/forums/boards/{board_id}/posts', 'ForumController@createPost');
        // 發布新回應
        $router->post('/forums/boards/{board_id}/post/{post_id}/replies', 'ForumController@createReply');
        // 編輯文章
        $router->patch('/forums/boards/{board_id}/post/{post_id}', 'ForumController@editPost');
        // 編輯回應
        $router->patch('/forums/boards/{board_id}/post/{post_id}/reply/{reply_id}', 'ForumController@editReply');
    });
});
