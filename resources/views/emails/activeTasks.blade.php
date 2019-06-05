@component('mail::message')
<p>Good morning <strong> {{ $user->first_name . " " . $user->last_name}}</strong>,</p>

This message serves as quick remainder of your team's current remaining work. You and your team have the following active tasks:

<br>

<h1 style="text-align: center;"><strong>TEAM TASKS</strong></h1>
***
@foreach($teamTasks as $task)
@component('mail::table')
        | ***Task Fields***       | ***Description***         |
        | ------------- |:-------------:|
        | **Project Name**  | {{ $task->project_name }}     |
        | **Task Name**     | {{ $task->title }} |
        | **Teams Involved** | {{ sizeof($task->teams) }} teams |
        | **Developers Involved** | {{ $task->developers }} developers |
        | **Progress**     | {{ $task->progress }} %   |
        | **Deadline**     | {{ $task->timeLeft }} days left     |


@endcomponent
***
@endforeach


@component('mail::button', ['url' => 'http://localhost:8000/', 'color' => 'green'])
Navigate to EPMA
@endcomponent



## Have a productive day,<br>
## {{ config('app.name') }}
@endcomponent
