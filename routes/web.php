<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 路由参数
Route::get('user/{id?}', function ($id = 1) {
    return view('user.profile', ['id' => $id]);
})->name('user.profile');

Route::get('page/{id}', function ($id) {
    return view('page.show', ['id' => $id]);
})->where('id', '[0-9]+');

Route::get('page/css', function () {
    return view('page.style');
});

Route::get('page/{name}', function ($name) {
    return '页面名称: ' . $name;
})->where('username', '[A-Za-z]+');

Route::get('page/{id}/{slug}', function ($id, $slug) {
    return $id . ':' . $slug;
})->where(['id' => '[0-9]+', 'slug' => '[A-Za-z]+']);

// 路由分组
Route::group([], function () {
    Route::get('hello', function () { return 'Hello'; });
    Route::get('world', function () { return 'World'; });
});

// 中间件
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    });
    Route::get('account', function () {
        return view('account');
    });
});

// 路由前缀
Route::prefix('api')->group(function () {
    Route::get('/', function () {
        // 处理 /api 路由
    })->name('api.index');
    Route::get('users', function () {
        // 处理 /api/users 路由
    })->name('api.users');
});

// 子域名
Route::domain('api.blog.test')->group(function () {
    Route::get('/', function () {
        // 处理 http://api.blog.test 路由
    });
});

Route::domain('{account}.blog.test')->group(function () {
    Route::get('/', function ($account) {
        //
    });
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});

// 命名空间

// 路由命名+路径前缀
/*Route::name('user.')->prefix('user')->group(function () {
    Route::get('{id?}', function ($id = 1) {
        // 处理 /user/{id} 路由，路由命名为 user.show
        return route('user.show');
    })->name('show');
    Route::get('posts', function () {
        // 处理 /user/posts 路由，路由命名为 user.posts
    })->name('posts');
});*/

Route::get('test', function () {
    return route('user.show', [100]);
});

Route::get('task', 'TaskController@index');
Route::get('task/create', 'TaskController@create');
Route::post('task', 'TaskController@store');

Route::resource('post', 'PostController');

Route::get('task/{id}', function ($id) {
    $task = \App\Models\Task::findOrFail($id);
});

Route::get('task/{task}', function (\App\Models\Task $task) {
    dd($task);
});

Route::get('task/model/{task_model}', function (\App\Models\Task $task) {
    dd($task);
});

Route::get('task/{id}/delete', function ($id) {
    return '<form method="post" action="' . route('task.delete', [$id]) . '">
                <input type="hidden" name="_method" value="DELETE"> 
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <button type="submit">删除任务</button>
            </form>';
});

Route::delete('task/{id}', function ($id) {
    return 'Delete Task ' . $id;
})->name('task.delete');

Route::get('form', 'RequestController@formPage');
Route::post('form', 'RequestController@form')->name('form.submit');
Route::post('form/file_upload', 'RequestController@fileUpload');

Route::get('/reflection', function () {
    $reflection = new ReflectionClass(\App\Services\StripeBiller::class);
    dump($reflection->getMethods());  # 获取 StripeBiller 类中的所有方法
    dump($reflection->getNamespaceName());  # 获取 StripeBiller 的命名空间
    dump($reflection->getProperties());  # 获取 StripeBiller 上的所有属性
});

Route::get('users', 'UserController@getIndex');

Route::get('test_artisan', function () {
    $exitCode = Artisan::call('welcome:message', [
        'name' => '学院君',
        '--city' => '杭州'
    ]);
});

Route::get('test_db', function () {
    //$name = '学院君';
    //$users = DB::select('select * from `users` where `name` = :name', ['name' => $name]);
    /*$name = str_random(10);
    $email = str_random(10) . '@163.com';
    $password = bcrypt('secret');
    $flag = DB::insert('insert into `users` (`name`, `email`, `password`) values (?, ?, ?)', [$name, $email, $password]);
    */
    /*$name = str_random(8);
    $id = 8;
    $affectedRows = DB::update('update `users` set `name` = ? where id = ?', [$name, $id]);*/
    $id = 8;
    $affectedRows = DB::delete('delete from `users` where id = ?', [$id]);
    dd($affectedRows);
});

Route::get('test_query_builder', function () {
    //$users = DB::table('users')->get();
    // $name = '学院君';
    // $user = DB::table('users')->select('id', 'name', 'email')->where('name', $name)->first();
    /*$flag = DB::table('users')->insert([
        'name' => str_random(10),
        'email' => str_random(8) . '@163.com',
        'password' => bcrypt('secret')
    ]);*/
    /*$userId = DB::table('users')->insertGetId([
        'name' => str_random(10),
        'email' => str_random(8) . '@qq.com',
        'password' => bcrypt('secret')
    ]);*/
    /*DB::table('users')->insert([
        ['name' => str_random(10), 'email' => str_random(8) . '@qq.com', 'password' => bcrypt('123')],
        ['name' => str_random(10), 'email' => 'smlQYzUg@qq.com', 'password' => bcrypt('456')],
        ['name' => str_random(10), 'email' => str_random(8) . '@qq.com', 'password' => bcrypt('789')],
    ]);*/

    /*$id = 11;
    $affectedRows = DB::table('users')->where('id', '>', $id)->update(['name' => str_random(8)]);*/
    $id = 11;
    $affectedRows = DB::table('users')->where('id', '>=', $id)->delete();
    dd($affectedRows);
});

Route::fallback(function () {
    return '我是最后的屏障';
});