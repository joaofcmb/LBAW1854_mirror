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
    return redirect('index');
});

Route::view('/teams/create/help','pages.create-team-help',['page'])->name('create-team-help');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login-action');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register-action');
Route::post('register/validateusername', 'Auth\RegisterController@validateUsername')->name('validate-username');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Home, Search and Static pages
Route::View('index', 'pages.index')->name('index');
Route::get('home', 'HomeController@show')->name('home');
Route::View('search', 'pages.search')->name('search');
Route::View('/404', 'pages.404')->name('404');
Route::get('/follow/{id_user}', 'ProfileController@follow');
Route::get('/favorites/{id_project}', 'ProfileController@favorite');

// Forums
Route::get('companyforum', 'ForumController@showCompanyForum')->name('companyforum');
Route::get('companyforum/thread/{id_thread}', 'ThreadController@show')->name('companyforum-thread');
Route::view('/companyforum/createthread', 'pages.forum.createThread', ['isProjectForum' => false])->name('company-forum-create-thread');
Route::post('/companyforum/createthread', 'ThreadController@create')->name('company-forum-create-thread-action');
Route::post('/companyforum/thread/{id_thread}/delete', 'ThreadController@delete');
Route::post('/companyforum/thread/{id_thread}/addcomment', 'ThreadController@addComment');
Route::post('/companyforum/thread/{id_thread}/deletecomment/{id_comment}', 'ThreadController@deleteComment');
Route::post('/companyforum/thread/{id_thread}/editcomment/{id_comment}', 'ThreadController@editComment');

Route::get('/project/{id}/forum', 'ProjectController@showForum')->name('project-forum');
Route::get('/project/{id_project}/forum/thread/{id_thread}', 'ProjectController@showForumThread')->name('forum-thread');
Route::get('/project/{id}/forum/createthread', 'ProjectController@createForumThread')->name('forum-create-thread');
Route::post('/project/{id_project}/forum/createthread', 'ProjectController@createForumThreadAction')->name('forum-create-thread-action');
Route::post('/project/{id_project}/forum/thread/{id_thread}/delete', 'ProjectController@deleteForumThread');
Route::post('/project/{id_project}/forum/thread/{id_thread}/addcomment', 'ProjectController@addThreadComment');
Route::post('/project/{id_project}/forum/thread/{id_thread}/deletecomment/{id_comment}', 'ProjectController@deleteThreadComment');
Route::post('/project/{id_project}/forum/thread/{id_thread}/editcomment/{id_comment}', 'ProjectController@editThreadComment');

// Project
Route::get('/project/{id_project}', 'ProjectController@show')->name('project-overview');
Route::get('/project/{id_project}/roadmap', 'ProjectController@showRoadmap')->name('project-roadmap');
Route::get('/project/{id}/tasks', 'ProjectController@showTasks')->name('project-tasks');
Route::get('/project/{id_project}/tasks/createtask/{id_taskgroup?}', 'ProjectController@createTask')->name('task-create');
Route::post('/project/{id_project}/tasks/createtask/{id_taskgroup?}', 'ProjectController@createTaskAction')->name('task-create-action');
Route::get('/project/{id_project}/close', 'ProjectController@closeProject')->name('close-project');
Route::post('/project/{id_project}/roadmap/createmilestone', 'ProjectController@createMilestone')->name('create-milestone-action');
Route::post('/project/{id_project}/roadmap/{id_milestone}/update', 'ProjectController@updateMilestone');
Route::delete('/project/{id_project}/roadmap/{id_milestone}/remove', 'ProjectController@deleteMilestone');
Route::post('/project/{id_project}/tasks/creategroup','ProjectController@createTaskGroup');
Route::post('/project/{id_project}/tasks/taskgroup/{id_taskgroup}/update','ProjectController@updateTaskGroup');
Route::delete('/project/{id_project}/tasks/taskgroup/{id_taskgroup}/remove', 'ProjectController@deleteTaskGroup');

// Tasks
Route::get('/project/{id_project}/tasks/{id_task}', 'TaskController@show')->name('task');
Route::get('/project/{id_project}/tasks/{id_task}/edit', 'TaskController@edit')->name('task-edit');
Route::post('/project/{id_project}/tasks/{id_task}/edit', 'TaskController@editAction')->name('task-edit-action');
Route::delete('/project/{id_project}/tasks/{id_task}/delete', 'TaskController@delete');
Route::post('/project/{id_project}/tasks/{id_task}/updateprogress', 'TaskController@updateProgress');

Route::get('/project/{id_project}/tasks/{id_task}/assign', 'TaskController@showAssign')->name('task-assign');
Route::post('/project/{id_project}/tasks/{id_task}/assign-group/{id_group?}', 'TaskController@group');

Route::post('/project/{id_project}/tasks/{id_task}/addcomment', 'TaskController@addTaskComment');
Route::delete('/project/{id_project}/tasks/{id_task}/deletecomment/{id_comment}', 'TaskController@deleteTaskComment');
Route::post('/project/{id_project}/tasks/{id_task}/editcomment/{id_comment}', 'TaskController@editTaskComment');

// Profile
Route::get('/profile/{id}', 'ProfileController@show')->name('profile');
Route::get('/profile/{id}/team', 'ProfileController@showTeam')->name('profile-team');
Route::get('/profile/{id}/favorites', 'ProfileController@showFavorites')->name('profile-favorites');
Route::get('/profile/{id}/followers', 'ProfileController@showFollowers')->name('profile-followers');
Route::get('/profile/{id}/following', 'ProfileController@showFollowing')->name('profile-following');
Route::post('/profile/{id}/edit', 'ProfileController@editProfile');
Route::get('/profile/{id}/remove', 'ProfileController@remove')->name('remove-user');
Route::post('profile/uploadpicture', 'ProfileController@uploadPicture')->name('upload-picture');

// Administrator
Route::get('/admin/users', 'AdministratorController@showUsers')->name('admin-users');
Route::get('/admin/users/{id}/remove', 'AdministratorController@removeUser');
Route::get('/admin/users/{id}/restore', 'AdministratorController@restoreUser');
Route::get('/admin/teams', 'AdministratorController@showTeams')->name('admin-teams');
Route::get('/admin/teams/create', 'AdministratorController@createTeam')->name('admin-create-team');
Route::post('/admin/teams/create', 'AdministratorController@createTeamAction')->name('admin-create-team-action');
Route::get('/admin/teams/{id}/edit', 'AdministratorController@editTeam')->name('admin-edit-team');
Route::post('/admin/teams/{id}/edit', 'AdministratorController@editTeamAction')->name('admin-edit-team-action');
Route::delete('/admin/teams/{id}/remove', 'AdministratorController@removeTeam');
Route::get('/admin/projects', 'AdministratorController@showProjects')->name('admin-projects');
Route::get('/admin/projects/create', 'AdministratorController@createProject')->name('admin-create-project');
Route::post('/admin/projects/create', 'AdministratorController@createProjectAction')->name('admin-create-project-action');
Route::get('/admin/projects/{id}/edit', 'AdministratorController@editProject')->name('admin-edit-project');
Route::post('/admin/projects/{id}/edit', 'AdministratorController@editProjectAction')->name('admin-edit-project-action');
Route::delete('/admin/project/{id}/cancel', 'AdministratorController@cancelProject');


// API - Project
Route::post('/api/project/{id_project}/roadmap/changeview', 'ProjectController@changeMilestoneView');
Route::post('/api/project/{id_project}/tasks/{id_task}/assign', 'TaskController@assign');
Route::post('/api/search/data', 'SearchController@search');