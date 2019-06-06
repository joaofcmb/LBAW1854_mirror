///////////////
// BOOTSTRAP //
///////////////

$('#progress-form input').on('input', function() {
    $("#progress-form label").text($(this)[0].value);
})

$('.border-hover').hover(
    function() {$(this).find('>:first-child .hover-icon').css('display', 'inline-block')},
    function() {$(this).find('>:first-child .hover-icon').css('display', 'none')}
)

$('#side-forum .sticky').on('resize', function() {
    if ($(this).prop('scrollHeight') > $(this).outerHeight) {
        $(this).css('display', 'none')
        console.log('yay')
    }
})

let milestoneCount = $('.milestone').length
let milestoneDoneCount = $('.milestone > .fa-dot-circle').length

if (milestoneCount > 0) {
    let leftWidth = (milestoneDoneCount + .5) * 100 / (milestoneCount + 1)

    $('.roadmap-left').css('width', leftWidth + "%")
    $('.roadmap-right').css({"left": leftWidth + "%", "width": 100 - leftWidth + "%"})
    $('.milestone-description').css('left', (milestoneDoneCount + 1) * 100 / (milestoneCount + 1) - 50 + "%")
}

/////////////////////
// EVENT LISTENERS //
/////////////////////

let typingTimer;                //timer identifier
let doneTypingInterval = 500;  //time in ms, 5 second for example

let registerFormUsername = document.getElementById('register-form-username')

let finishedTyping = function () {
    sendAjaxRequest.call(this, 'post', 'register/validateusername', {username: this.value}, validateUsername);
};

if(registerFormUsername != null) {
    registerFormUsername.addEventListener('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(finishedTyping.bind(registerFormUsername), doneTypingInterval);
    });

    registerFormUsername.addEventListener('keydown', function () {
        clearTimeout(typingTimer);
    });
}

// PROFILE //
let edit_profile_info = document.getElementsByClassName('edit-profile-info')[0];

if (edit_profile_info !== undefined) {
    edit_profile_info.addEventListener('click', function (event) {
        event.preventDefault();

        let button = edit_profile_info.getElementsByClassName('btn')[0];
        let id = button.getAttribute('id');
        let email = document.getElementById('email');
        let oldPassword = document.getElementById('old-password');
        let newPassword = document.getElementById('new-password');
        let confirmPassword = document.getElementById('confirm-password');

        let to_send = {};
        to_send.email = email.value;

        if(!validateEmail(email.value))
            blockHelpNode(email.parentElement, "Invalid email !", "red")
        else if(newPassword.value !== confirmPassword.value)
            blockHelpNode(newPassword.parentElement, "Passwords do not match !", "red")
        else if(newPassword.value !== "" && confirmPassword.value !== "" && oldPassword.value === "")
            blockHelpNode(newPassword.parentElement, "Old Password field cannot be empty !", "red")
        else if(newPassword.value !== "" && newPassword.value.length < 6)
            blockHelpNode(newPassword.parentElement, "New password must be at least 6 characters !", "red")
        else
            sendAjaxRequest.call(this, 'post', '/profile/' + id + '/edit', {email: email.value, oldPassword: oldPassword.value, newPassword: newPassword.value}, editProfileInformation);
    })
}

let edit_biography = document.getElementById('edit-biography');

if(edit_biography !== null) {
    function editBiographyListener(event) {
        event.preventDefault();

        let biography_div = document.getElementById('biography');
        let biography_text = biography_div.getElementsByTagName('p')[0];
        let textArea = document.createElement('textArea');

        textArea.value = biography_text.textContent;
        textArea.className = biography_text.className + 'form-control';
        textArea.setAttribute('rows', 6);
        textArea.setAttribute('cols', 30);
        textArea.setAttribute('placeholder', 'Describe yourself ...');
        textArea.setAttribute('id', biography_text.getAttribute('id'));

        biography_div.replaceChild(textArea, biography_text);

        let save_biography = document.createElement('i');
        save_biography.setAttribute('id', 'edit-biography');
        save_biography.setAttribute('class', 'fas fa-check ml-2 float-right');

        biography_div.firstElementChild.replaceChild(save_biography, this);

        save_biography.addEventListener('click', function() {
            let id_user = textArea.getAttribute('id')
            let biography = textArea.value;

            sendAjaxRequest.call(this, 'post', '/profile/' + id_user + '/edit', {biography: biography}, editBiography);
        })
    }

    editBiographyListener.bind(edit_biography);
    edit_biography.addEventListener('click', editBiographyListener)
}

let uploadProfilePicture = document.getElementById('upload-profile-picture')
let uploadProfilePictureFile = document.getElementById('upload-profile-picture-file')
let uploadProfilePictureSubmit = document.getElementById('upload-profile-picture-form-submit')

if(uploadProfilePicture != null) {
    uploadProfilePicture.addEventListener('click', function() {
        uploadProfilePictureFile.click()
    })
}

if(uploadProfilePictureFile != null) {
    uploadProfilePictureFile.addEventListener('change', function () {
        uploadProfilePictureSubmit.click()
    })
}

let uploadProfileBackground = document.getElementById('background-button')
let uploadProfileBackgroundFile = document.getElementById('upload-profile-background-file')
let uploadProfileBackgroundSubmit = document.getElementById('upload-profile-background-form-submit')

if(uploadProfileBackground != null) {
    uploadProfileBackground.addEventListener('click', function() {
        uploadProfileBackgroundFile.click()
    })
}

if(uploadProfileBackgroundFile != null) {
    uploadProfileBackgroundFile.addEventListener('change', function () {
        uploadProfileBackgroundSubmit.click()
    })
}

let follow = document.getElementsByClassName('follow');

for(let i = 0; i < follow.length; i++) {
    follow[i].addEventListener('click', function () {
        let id_user = follow[i].getAttribute('id').split('-')[1];
        sendAjaxRequest.call(this, 'get', '/follow/' + id_user, null, followHandler);
    })
}

let favorite = document.getElementsByClassName('favorite');

for(let i = 0; i < favorite.length; i++) {
    favorite[i].addEventListener('click', function () {
        let id_project = favorite[i].getAttribute('id').split('-')[1];
        sendAjaxRequest.call(this, 'get', '/favorites/' + id_project, null, favoriteHandler);
    })
}

// FORUM //

let deleteThread = document.getElementsByClassName('thread-delete')

let deleteThreadListener = function (event) {
    event.preventDefault();

    let thread_id_info = this.getAttribute('id').split('-');
    let id_thread = thread_id_info[1];
    let id_project = thread_id_info[2];

    if(this.hasAttribute('belongsToProject'))
        sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/forum/thread/' + id_thread + '/delete', null, deleteThreadHandler);
    else
        sendAjaxRequest.call(this, 'post', '/companyforum/thread/' + id_thread + '/delete', null, deleteThreadHandler);
};

for(let i = 0; i < deleteThread.length; i++) {
    deleteThreadListener.bind(deleteThread[i])
    deleteThread[i].addEventListener('click', deleteThreadListener)
}

let addThreadComment = document.getElementsByClassName('add-comment')[0];

if(addThreadComment != null) {
    addThreadComment.addEventListener('submit', function (event) {
        event.preventDefault();

        let thread_id_info = addThreadComment.getAttribute('id').split('-');
        let id_thread = thread_id_info[2];
        let id_project = thread_id_info[3];

        let commentContent = document.getElementById('commentContent')

        let comment_content = commentContent.value;
        commentContent.value = "";

        if(addThreadComment.hasAttribute('belongstoproject'))
            sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/forum/thread/' + id_thread +'/addcomment', {text: comment_content}, addThreadCommentHandler);
        else
            sendAjaxRequest.call(this, 'post', '/companyforum/thread/' + id_thread + '/addcomment', {text: comment_content}, addThreadCommentHandler);
    });
}

let editThreadComment = document.getElementsByClassName('comment-edit');

let editThreadCommentListener = function (event) {
    let info = this.getAttribute('id').split('-');
    let comment_div = document.getElementById(info[0] + '-' + info[2]);
    let text = comment_div.querySelector('p');
    let input_text = document.createElement('textarea');

    input_text.value = text.textContent;
    input_text.className = text.className + ' mb-3 form-control';
    input_text.setAttribute('placeholder', 'Comment ...');
    input_text.setAttribute('id', this.getAttribute('id'));

    comment_div.replaceChild(input_text, text);

    input_text.addEventListener('keyup', function(event) {
        if(event.keyCode == '13') {
            let info = input_text.getAttribute('id').split('-');
            let id_comment = info[2];
            let id_thread = info[3];
            let id_project = info[4];

            if(this.hasAttribute('belongstoproject'))
                sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/forum/thread/' + id_thread
                    + '/editcomment/' + id_comment, {text: input_text.value}, editThreadCommentHandler);
            else
                sendAjaxRequest.call(this, 'post', '/companyforum/thread/' + id_thread + '/editcomment/'
                    + id_comment, {text: input_text.value}, editThreadCommentHandler);
        }
    });

    this.removeEventListener('click', editThreadCommentListener);
    this.addEventListener('click', function() {
        let info = input_text.getAttribute('id').split('-');
        let id_comment = info[2];
        let id_thread = info[3];
        let id_project = info[4];

        if(this.hasAttribute('belongstoproject'))
            sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/forum/thread/' + id_thread
                + '/editcomment/' + id_comment, {text: input_text.value}, editThreadCommentHandler);
        else
            sendAjaxRequest.call(this, 'post', '/companyforum/thread/' + id_thread + '/editcomment/'
                + id_comment, {text: input_text.value}, editThreadCommentHandler);
    });

    event.preventDefault();
}

for (let i = 0; i< editThreadComment.length; i++) {
    editThreadCommentListener.bind(editThreadComment[i]);
    editThreadComment[i].addEventListener('click', editThreadCommentListener);
}

let deleteThreadComment = document.getElementsByClassName('comment-delete');

let deleteThreadCommentListener = function () {
    let thread_id_info = this.getAttribute('id').split('-');

    let id_comment = thread_id_info[1];
    let id_thread = thread_id_info[2];
    let id_project = thread_id_info[3];

    if(this.hasAttribute('belongstoproject'))
        sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/forum/thread/' + id_thread + '/deletecomment/' + id_comment, null, deleteThreadCommentHandler);
    else
        sendAjaxRequest.call(this, 'post', '/companyforum/thread/' + id_thread + '/deletecomment/' + id_comment, null, deleteThreadCommentHandler);
};

for(let i = 0; i < deleteThreadComment.length; i++) {
    deleteThreadCommentListener.bind(deleteThreadComment[i])
    deleteThreadComment[i].addEventListener('click', deleteThreadCommentListener)
}

// ROADMAP //
let milestones = document.getElementsByClassName('milestone-switch');

let milestoneSwitchListener = function() {
    let milestone = this.getAttribute('id').split('-');
    let id_project = milestone[0];
    let id_milestone = milestone[2];

    sendAjaxRequest.call(this, 'post', '/api/project/' + id_project + '/roadmap/changeview', {milestone: id_milestone}, changeMilestoneHandler);
}

for(let i = 0; i < milestones.length; i++){
    milestoneSwitchListener.bind(milestones[i]);
    milestones[i].addEventListener('click', milestoneSwitchListener)
}

let editMilestone = document.getElementById('editMilestoneModal');

if(editMilestone != null) {
    let save = editMilestone.querySelector('.update-milestone');
    save.addEventListener('click', function() {
        let info = save.getAttribute('id').split('-');
        let input_name = editMilestone.querySelector('input#milestone-name');
        let input_deadline = editMilestone.querySelector('input#milestone-deadline');

        sendAjaxRequest.call(this, 'post', '/project/' + info[1] + '/roadmap/' + info[2] + '/update',
            {name: input_name.value, deadline: input_deadline.value}, editMilestoneHandler);
    })
}

let removeMilestone = document.querySelector('i.remove-milestone');

if(removeMilestone != null) {
    removeMilestone.addEventListener('click', function() {
        let info = removeMilestone.getAttribute('id').split('-');

        sendAjaxRequest.call(this, 'delete', '/project/' + info[1] + '/roadmap/' + info[2] + '/remove', null, removeMilestoneHandler);
    })
}

// TASKS GROUP //

let addTaskGroup = document.querySelector('button.add-group');

if(addTaskGroup != null) {
    addTaskGroup.addEventListener('click', function() {
        let id_project = this.getAttribute('id').split('-')[1];
        let title = document.querySelector('#task-group-title').value;

        sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/tasks/creategroup', {title: title}, createTaskGroupHandler);
    })
}

$('#editTaskGroupModal').on('show.bs.modal', function () {
    let modal = this;
    let button = document.getElementsByClassName('editTaskGroupButton')[0];
    let title = button.getAttribute('data-whatever');
    let id_taskgroup = button.getAttribute('id').split('-')[1];
    modal.querySelector('.modal-body input').value = title;

    let save = modal.querySelector('button.edit-group');
    let id_project = save.getAttribute('id').split('-')[1];
    save.setAttribute('id', 'editGroup-' + id_project + '-' + id_taskgroup);

    save.addEventListener('click', function() {
        let new_title = modal.querySelector('input#task-group-title-edit').value;
        sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/tasks/taskgroup/' + id_taskgroup + '/update',
            {title: new_title}, editTaskGroupHandler);
    })
})

let removeTaskGroup = document.getElementsByClassName('remove-task-group');

let removeTaskGroupListener = function() {
    let info = this.getAttribute('id').split('-');
    sendAjaxRequest.call(this, 'delete', '/project/' + info[1] + '/tasks/taskgroup/' + info[2] + '/remove', null, removeTaskGroupHandler);
}

for(let i = 0; i < removeTaskGroup.length; i++) {
    removeTaskGroupListener.bind(removeTaskGroup[i]);
    removeTaskGroup[i].addEventListener('click', removeTaskGroupListener);
}

// DRAG AND DROP (Task Groups) //
let tasks = document.getElementsByClassName('draggable');

let taskGroups = document.getElementsByClassName('task-group');

for (const task of tasks) {
    task.addEventListener('dragstart', function(ev) {
        ev.dataTransfer.setData("text/plain", ev.target.id);
    });
}

for (const taskGroup of taskGroups) {
    taskGroup.addEventListener('dragover', ev => ev.preventDefault());
    taskGroup.addEventListener('drop', taskGroupDropListener(taskGroup));
}

function taskGroupDropListener(taskGroup) {
    return function (ev) {
        ev.preventDefault();

        let movedTask = document.getElementById(ev.dataTransfer.getData("text/plain"));
        let taskId = movedTask.id.split('-')[2];
        let groupIds = taskGroup.id.split('-');
        let route = '/project/' + groupIds[1] + '/tasks/' + taskId + '/assign-group' + (groupIds[2] != undefined? '/' + groupIds[2] : '');

        sendAjaxRequest.call(this, 'post', route, null, function () { ev.target.getElementsByClassName('drop-area')[0].appendChild(movedTask); });
    };
}

// TASKS //

let update_progress = document.querySelector('button.update-progress');

if(update_progress != null)
    update_progress.addEventListener('click', function() {
        let info = this.getAttribute('id').split('-');
        let progress = document.querySelector('input#progressValue').value;

        sendAjaxRequest.call(this, 'post', '/project/' + info[1] + '/tasks/' + info[2] + '/updateprogress', {progress: progress}, updateProgressHandler);
    })

let addTaskComment = document.getElementsByClassName('add-task-comment')[0];

if(addTaskComment != null) {
    addTaskComment.addEventListener('submit', function (event) {
        event.preventDefault();

        let info = addTaskComment.getAttribute('id').split('-');
        let commentContent = document.getElementById('commentContent');
        let comment_content = commentContent.value;
        commentContent.value = "";

        sendAjaxRequest.call(this, 'post', '/project/' + info[2] + '/tasks/' + info[1] +'/addcomment', {text: comment_content}, addTaskCommentHandler);
    });
}

let editTaskComment = document.getElementsByClassName('task-comment-edit');

let editTaskCommentListener = function (event) {
    let info = this.getAttribute('id').split('-');
    let comment_div = document.getElementById('comment-' + info[1]);
    let text = comment_div.querySelector('p');
    let input_text = document.createElement('textarea');

    input_text.value = text.textContent;
    input_text.className = text.className + ' mb-3 form-control';
    input_text.setAttribute('placeholder', 'Comment ...');
    input_text.setAttribute('id', this.getAttribute('id'));

    comment_div.replaceChild(input_text, text);

    input_text.addEventListener('keyup', function(event) {
        if(event.keyCode == '13') {
            let info = input_text.getAttribute('id').split('-');
            let id_comment = info[1];
            let id_task = info[2];
            let id_project = info[3];

            sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/tasks/' + id_task
                + '/editcomment/' + id_comment, {text: input_text.value}, editTaskCommentHandler);
        }
    });

    this.removeEventListener('click', editTaskCommentListener);
    this.addEventListener('click', function() {
        let info = input_text.getAttribute('id').split('-');
        let id_comment = info[1];
        let id_task = info[2];
        let id_project = info[3];

        sendAjaxRequest.call(this, 'post', '/project/' + id_project + '/tasks/' + id_task
                + '/editcomment/' + id_comment, {text: input_text.value}, editTaskCommentHandler);
    });

    event.preventDefault();
}

for (let i = 0; i< editTaskComment.length; i++) {
    editTaskCommentListener.bind(editTaskComment[i]);
    editTaskComment[i].addEventListener('click', editTaskCommentListener);
}

let deleteTaskComment = document.getElementsByClassName('task-comment-delete');

let deleteTaskCommentListener = function () {
    let info = this.getAttribute('id').split('-');

    let id_comment = info[1];
    let id_task = info[2];
    let id_project = info[3];

    sendAjaxRequest.call(this, 'delete', '/project/' + id_project + '/tasks/' + id_task + '/deletecomment/' + id_comment, null, deleteTaskCommentHandler);
};

for(let i = 0; i < deleteTaskComment.length; i++) {
    deleteTaskCommentListener.bind(deleteTaskComment[i])
    deleteTaskComment[i].addEventListener('click', deleteTaskCommentListener)
}

let assign = document.querySelector('a.assign-button');

if(assign != null) {
    assign.addEventListener('click', function() {
        let info = assign.getAttribute('id').split('-');

        let teams = document.querySelectorAll('input[name="team"]:checked');
        let teams_id = [];
        
        for (const team of teams)
            teams_id.push(team.id.split('-')[1]);
        
        let milestone_id = document.querySelector('input[name="milestone"]:checked').getAttribute('id').split('-')[1];

        sendAjaxRequest.call(this, 'post', '/api/project/' + info[1] + '/tasks/' + info[2] + '/assign', 
            {teams: teams_id, milestone: milestone_id}, assignTaskHandler);
    })
}

let remove_task = document.getElementsByClassName('remove-task');

let removeTaskListener = function() {
    let info = this.getAttribute('id').split('-');
    sendAjaxRequest.call(this, 'delete', '/project/' + info[1] + '/tasks/' + info[2] + '/delete', null, removeTaskHandler);
}

for(let i = 0; i< remove_task.length; i++) {
    removeTaskListener.bind(remove_task[i]);
    remove_task[i].addEventListener('click', removeTaskListener);
}

// ADMINISTRATION //

let remove_user = document.getElementsByClassName('remove-user');

function removeUserListener(event) {
    let id = this.getAttribute('id');
    sendAjaxRequest.call(this, 'get', '/admin/users/'+ id + '/remove', null, removeUserHandler);
    event.preventDefault();
}

for(let i = 0; i < remove_user.length; i++) {
    removeUserListener.bind(remove_user[i]);
    remove_user[i].addEventListener('click', removeUserListener)
}

let restore_user = document.getElementsByClassName('restore-user');

function restoreUserListener(event) {
    let id = this.getAttribute('id');
    sendAjaxRequest.call(this, 'get', '/admin/users/'+ id + '/restore', null, restoreUserHandler);
    event.preventDefault();
}

for(let i = 0; i < restore_user.length; i++) {
    restoreUserListener.bind(restore_user[i]);
    restore_user[i].addEventListener('click', restoreUserListener);
}

let remove_team = document.getElementsByClassName('remove-team');

function removeTeamListener() {
    let id = this.getAttribute('id').split('-')[1];
    sendAjaxRequest.call(this, 'delete', '/admin/teams/' + id + '/remove', null, removeTeamHandler);
}

for(let i = 0; i < remove_team.length; i++) {
    removeTeamListener.bind(remove_team[i]);
    remove_team[i].addEventListener('click', removeTeamListener);
}

let remove_project = document.getElementsByClassName('remove-project');

function removeProjectListener() {
    let id = this.getAttribute('id').split('-')[1];
    sendAjaxRequest.call(this, 'delete', '/admin/project/' + id + '/cancel', null, removeProjectHandler);
}

for(let i = 0; i < remove_project.length; i++) {
    removeProjectListener.bind(remove_project[i]);
    remove_project[i].addEventListener('click', removeProjectListener);
}

let manage_team_submit = document.getElementsByClassName('manage-team')[0];

if(manage_team_submit != null) {
    manage_team_submit.addEventListener('click', function() {
        let form = document.getElementsByTagName('form')[0];
        let team_name = form.querySelector('#teamName').value;
        let leader = document.getElementById('Leader').children;

        if(team_name == ""){
            blockHelpNode(form.querySelector('#teamName').parentElement, 'A team must have a name', "red");
            return;
        }
        if(leader.length == 1){
            blockHelpNode(leader[0].parentElement, 'A team must have a leader', "red");
            return;
        }

        let leader_id = leader[1].getAttribute('id');
        let member_container = document.getElementById('Members').children;
        let members_id = [];

        for (let i = 1; i < member_container.length; i++) {
            members_id.push(member_container[i].getAttribute('id'));            
        }       

        document.getElementById('teamLeader').value = leader_id;
        document.getElementById('teamMembers').value = members_id;
        
        document.getElementById('submit').click();
    })
}

function addMemberListener() {
    let id_user = this.parentElement.parentElement.parentElement.getAttribute('id');
    let user_card = document.getElementById(id_user);
    user_card.remove();

    let remove_button = document.createElement('i');
    remove_button.className = 'remove-member fas fa-fw fa-times text-danger';

    let icons = user_card.querySelector('a.float-right');
    icons.replaceChild(remove_button, user_card.querySelector('i.add-member'));
    
    let search_content = document.getElementById('search-display');

    if(search_content.parentElement.childElementCount == 3) {
        let promote_button = document.createElement('i');
        promote_button.className = 'promote-leader fas fa-user-tie';
        promote_button.setAttribute('style', "color:grey;");

        icons.insertBefore(promote_button, remove_button);
        
        let members_div = document.getElementById('Members');
        members_div.appendChild(user_card);

        promoteLeaderListener.bind(promote_button);
        promote_button.addEventListener('click', promoteLeaderListener);
    }       
    else {
        let manager = search_content.firstElementChild;
        
        if(manager != null) {
            search_content.insertBefore(user_card,manager);
            let manager_icon = manager.querySelector('a.float-right');

            if(manager_icon.querySelector('i.remove-member') != null) {
                let add_button = document.createElement('i');
                add_button.className = 'add-member fas fa-plus';

                manager_icon.replaceChild(add_button, manager_icon.querySelector('i.remove-member'));

                addMemberListener.bind(add_button);
                add_button.addEventListener('click', addMemberListener);
            }
        }
        else
            search_content.appendChild(user_card);
    }

    removeMemberListener.bind(remove_button);
    remove_button.addEventListener('click', removeMemberListener);
}

let add_member = document.getElementsByClassName('add-member');

for (const add of add_member) {
    addMemberListener.bind(add);
    add.addEventListener('click', addMemberListener);
}

function promoteLeaderListener() {
    let user_card = this.parentElement.parentElement.parentElement;
    let leader_div = document.getElementById('Leader');
    let members_div = document.getElementById('Members');
    
    user_card.querySelector('i.promote-leader').remove();
    user_card.remove();
    leader_div.appendChild(user_card);

    if(leader_div.childElementCount > 2) {
        let old_leader = leader_div.children[1];
        old_leader.remove();

        let promote_button = document.createElement('i');
        promote_button.className = 'promote-leader fas fa-user-tie';
        promote_button.setAttribute('style', "color:grey;");

        let icons = old_leader.querySelector('a.float-right');
        icons.insertBefore(promote_button, icons.querySelector('.remove-member'));

        members_div.appendChild(old_leader);

        promoteLeaderListener.bind(promote_button);
        promote_button.addEventListener('click', promoteLeaderListener);
    }
}

let promote_leader = document.getElementsByClassName('promote-leader');

for (const promote of promote_leader) {
    promoteLeaderListener.bind(promote);
    promote.addEventListener('click', promoteLeaderListener);
}

function removeMemberListener() {
    let user_card = this.parentElement.parentElement.parentElement;
    
    let add_button = document.createElement('i');
    add_button.className = 'add-member fas fa-plus';

    let icons = user_card.querySelector('a.float-right');
    icons.replaceChild(add_button, user_card.querySelector('i.remove-member'));
    
    let search_content = document.getElementById('search-display');

    if(search_content.parentElement.childElementCount == 3) {
        user_card.remove();

        if(icons.childElementCount > 1)
            icons.children[0].remove();

        search_content.appendChild(user_card);
    }

    addMemberListener.bind(add_button);
    add_button.addEventListener('click', addMemberListener);
}

let remove_member = document.getElementsByClassName('remove-member');

for (const remove of remove_member) {
    removeMemberListener.bind(remove);
    remove.addEventListener('click', removeMemberListener);
}

// SEARCH //

let searchBar = document.getElementsByClassName('search-bar')

let doneTyping = function () {

    let regex = /\w/gm;

    if(this.firstElementChild.firstElementChild.value.search(new RegExp(regex)) === -1)
        return

    let page = this.getAttribute('class').split(' ')[1];

    switch (page) {
        case 'globalSearch':
            search.call(this, document.getElementsByClassName('btn active')[0].lastChild.textContent);
            break;
        case 'adminUsers':
            search.call(this, 'Users');
            break;
        case 'adminTeams':
            search.call(this, 'Teams');
            break;
        case 'adminProjects':
            search.call(this, 'Projects');
            break;
        case 'manageTeam':
            manageTeam.call(this);
            break;
        case 'manageProject':
            manageProject.call(this);
            break;
        case 'teamAssign':
            teamAssign.call(this);
            break;
    }
};

function search(data) {
    let query = this.firstElementChild.firstElementChild.value;

    sendAjaxRequest.call(this, 'post', '/api/search/data', {'query': query, 'data': data, 'constraints': []}, editSearch)
}

function manageTeam() {
    let team = [];
    let query = this.firstElementChild.firstElementChild.value;

    let leader = document.getElementById('Leader');
    let members = document.getElementById('Members')

    if(leader.children.length > 1)
        team.push(Number(leader.children[1].getAttribute('id')));

    if(members.children.length > 1) {
        for(let index = 1; index < members.children.length; index++)
            team.push(Number(members.children[index].getAttribute('id')))
    }

    sendAjaxRequest.call(this, 'post', '/api/search/data', {'query': query, 'data': 'Users', 'constraints': team}, editSearch)
}

function manageProject() {
    let manager_id = null;
    let query = this.firstElementChild.firstElementChild.value;

    let search_content = this.parentElement.querySelector('#search-display');
    if(search_content.children.length > 0)
        manager_id = Number(search_content.firstElementChild.getAttribute('id'));

    sendAjaxRequest.call(this, 'post', '/api/search/data', {'query': query, 'data': 'Users', 'constraints': [manager_id], 'manager': true}, editSearch)
}

function teamAssign() {
    let teams = []
    let query = this.firstElementChild.firstElementChild.value;

    let search_content = this.parentElement.querySelector('#search-content');
    if(search_content.children.length > 1) {
        for(let index = 0; index < search_content.children.length; index++)
            if(search_content.children[index].querySelector('input').checked)
                teams.push(Number(search_content.children[index].getAttribute('id')))
    }

    sendAjaxRequest.call(this, 'post', '/api/search/data', {'query': query, 'data': 'Teams', 'constraints': teams}, editSearch)
}

for(let i = 0; i < searchBar.length; i++) {
    searchBar[i].addEventListener('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping.bind(searchBar[i]), doneTypingInterval);
    });

    searchBar[i].addEventListener('keydown', function () {
        clearTimeout(typingTimer);
    });
}

//////////////
// HANDLERS //
//////////////

function blockHelpNode(element, message, color) {
    let blockHelp = document.getElementById('block-help')

    if(blockHelp != null)
        document.getElementById('block-help').remove();

    let node = document.createElement("p");
    node.setAttribute('id', 'block-help')
    node.setAttribute('class', 'pt-3 my-0')
    node.setAttribute('style', 'color: ' + color + '; font-family: \'Comfortaa\', sans-serif;')
    node.textContent = message
    element.appendChild(node)
}

function editProfileInformation() {
    if (this.status !== 200) return;

    let item = JSON.parse(this.responseText);

    if(item['status'] === "FAILED")
        blockHelpNode(document.getElementById('new-password').parentElement, "Old Password field does not match our records !", "red");
    else if(item['status'] === "SUCCESS")
        blockHelpNode(document.getElementById('new-password').parentElement, "Information updated with success !", "green");
}

function validateUsername() {
    if (this.status !== 200) return;

    let item = JSON.parse(this.responseText);

    if(item['status'] === true)
        blockHelpNode(document.getElementById('register-form-username').parentElement.parentElement, "This username already exists !", "red");
    else
        blockHelpNode(document.getElementById('register-form-username').parentElement.parentElement, "This username is available !", "green");
}

function editBiography() {
    let biography_div = document.getElementById('biography');
    let textArea = biography_div.getElementsByTagName('textarea')[0];
    let biography_text = document.createElement('p');

    biography_text.textContent = textArea.value.replace(/(\r\n|\n|\r)/gm, "");
    biography_text.className = "pt-2 mb-0";
    biography_text.setAttribute('id', textArea.getAttribute('id'));

    biography_div.replaceChild(biography_text, textArea);

    let save_biography = document.getElementById('edit-biography');
    let edit = document.createElement('i');
    edit.setAttribute('id', 'edit-biography');
    edit.setAttribute('class', 'fas fa-edit ml-2 float-right');

    biography_div.firstElementChild.replaceChild(edit, save_biography);
    
    editBiographyListener.bind(edit);
    edit.addEventListener('click', editBiographyListener);
}

function followHandler() {
    if (this.status !== 200) return;

    let item_id = this.prototype.getAttribute('id').split('-')[1];
    let follow_class = document.getElementById('user-' + item_id).getAttribute('class');

    document.getElementById('user-' + item_id).setAttribute('class', (follow_class === 'follow far fa-star') ? 'follow fas fa-star' : 'follow far fa-star');
}

function favoriteHandler() {
    if (this.status !== 200) return;

    let item_id = this.prototype.getAttribute('id').split('-')[1];
    let favorite_class = document.getElementById('project-' + item_id).getAttribute('class');

    document.getElementById('project-' + item_id).setAttribute('class', (favorite_class === 'favorite far fa-star') ? 'favorite fas fa-star' : 'favorite far fa-star');
}

function deleteThreadHandler() {
    if (this.status !== 200) return;

    document.getElementById('thread-' + this.prototype.getAttribute('id').split('-')[1]).remove();
}

function addThreadCommentHandler() {
    if (this.status !== 200) return;

    let item = JSON.parse(this.responseText);

    let id_project = this.prototype.getAttribute('id').split('-')[3];

    let thread = document.querySelector('#thread-content');
    let add_comment = document.querySelector('form.add-comment');

    let new_comment = document.createElement('div');

    let profile_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + item.id_author;

    let edit_button = '<i id="comment-edit-' + item.id + '-' + item.id_thread + '-' + id_project + '" ';
    let delete_button = '<i id="comment-' + item.id + '-' + item.id_thread + '-' + id_project + '" ';

    if(id_project != 0) {
        edit_button += "belongsToProject=\"true\"";
        delete_button += "belongsToProject=\"true\"";
    }

    edit_button += ' class="comment-edit fas fa-pen mx-2"></i>';
    delete_button += ' class="comment-delete fas fa-trash-alt mx-2" style="cursor: pointer;"></i>';

    new_comment.id = 'comment-' + item.id;
    new_comment.className = "card pb-0 px-3 pt-3 my-3";
    new_comment.innerHTML = '<div class="row"><div class="col"><a class="d-flex flex-row pt-1" href="'+
        profile_route + '"><i class="fas fa-user mr-1"></i><h6>' +
        item.author_name + '</h6></a></div><div class="col text-right"> ' +
        '<a style="cursor: pointer;"> ' + edit_button + ' </a> <a style="cursor: pointer;">' +
        delete_button + '</a></div></div><p class="mt-2">' + item.text + '</p>';

    let edit_comment = new_comment.querySelector('i.comment-edit');
    editThreadCommentListener.bind(edit_comment);
    edit_comment.addEventListener('click', editThreadCommentListener);

    let deletecomment = new_comment.querySelector('i.comment-delete');
    deleteThreadCommentListener.bind(deletecomment);
    deletecomment.addEventListener('click', deleteThreadCommentListener);

    thread.insertBefore(new_comment, add_comment);
}

function editThreadCommentHandler() {
    if (this.status !== 200) return;

    let id = this.prototype.getAttribute('id');
    let info = id.split('-');
    let comment_div = document.getElementById(info[0] + '-' + info[2]);
    let input = comment_div.querySelector('textarea');
    let text = document.createElement('p');

    text.textContent = input.value.replace(/(\r\n|\n|\r)/gm, "");
    text.className = 'mt-2';
    text.setAttribute('id', id);

    comment_div.replaceChild(text, input);

    let old_button = comment_div.querySelector('#'+id);
    let new_button = old_button.cloneNode(true);
    old_button.parentElement.replaceChild(new_button,old_button);

    editThreadCommentListener.bind(new_button);
    new_button.addEventListener('click', editThreadCommentListener);
}

function deleteThreadCommentHandler() {
    if(this.status !== 200) return;

    document.getElementById('comment-' + this.prototype.getAttribute('id').split('-')[1]).remove();
}

function changeMilestoneHandler() {
    if (this.status !== 200) return;
    let item = JSON.parse(this.responseText);

    changeRoadmapInfo(document.getElementById('all-milestones').children, item);
}

function editMilestoneHandler() {
    if (this.status !== 200) return;

    let item = JSON.parse(this.responseText);
    let roadmap_bar = document.querySelector('div.roadmap-diagram');
    let roadmap_descrip = document.getElementById('all-milestones');
    let milestone_content = document.getElementById('milestone'+ item.currentMilestone.id);
    let milestone_name = milestone_content.querySelector('h3');
    let milestone_tasks = milestone_content.querySelector('div.mx-auto');
    let milestones = item.milestones;

    roadmap_bar.innerHTML = '<div class="p-1"></div> ';
    roadmap_descrip.innerHTML = '<div class="p-4"></div> ';

    for (let i = 0; i < milestones.length; i++) {
        roadmap_bar.innerHTML += '<a id="' + milestones[i].id_project + '-milestone1-' + milestones[i].id + '" data-toggle="collapse" ' +
            'class="milestone-switch milestone py-2"><i class="far fa-' + (new Date(milestones[i].deadline) < new Date(item.date) ? 'dot-' : '' ) +
            'circle align-middle"></i></a> ';
        roadmap_descrip.innerHTML += '<a id="' + milestones[i].id_project + '-milestone-' + milestones[i].id + '" data-toggle="collapse" ' +
            (milestones[i].id == item.currentMilestone.id ? 'aria-expanded=true' : '') +
            ' class="milestone-switch milestone-info ' + (milestones[i].id == item.currentMilestone.id ? 'active' : 'collapsed') + ' text-center pb-3" ' +
            'style="border-color: rgb(12, 116, 214);"> <h6 class="mb-1">' + milestones[i].deadline.substr(0,10) + '</h6> ' +
            (new Date(milestones[i].deadline) < new Date(item.date) ? 'Elapsed' : milestones[i].timeLeft + ' days left') + ' </a> ';
        milestones[i];
    }

    roadmap_bar.innerHTML += '<div class="p-1"></div> ';
    roadmap_descrip.innerHTML += '<div class="p-4"></div> ';

    let milestones_button = document.getElementsByClassName('milestone-switch');
    for(let i = 0; i < milestones_button.length; i++){
        milestoneSwitchListener.bind(milestones_button[i]);
        milestones_button[i].addEventListener('click', milestoneSwitchListener)
    }

    milestone_name.innerHTML = item.currentMilestone.name +
        ' <button type="button" data-toggle="modal" data-target="#editMilestoneModal"> <i class="far fa-edit ml-2"></i> </button> ' +
        '<i id="removeMilestone-' + item.currentMilestone.id_project + '-' + item.currentMilestone.id + '" class="remove-milestone far fa-trash-alt"></i>';

    let removeMilestone = milestone_name.querySelector('i.remove-milestone');
    removeMilestone.addEventListener('click', function() {
        let info = removeMilestone.getAttribute('id').split('-');
        sendAjaxRequest.call(this, 'delete', '/project/' + info[1] + '/roadmap/' + info[2] + '/remove', null, removeMilestoneHandler);
    })

    milestone_tasks.innerHTML = createTaskHtml(item.currentMilestone.tasks, item.isProjectManager);
}

function removeMilestoneHandler() {
    if(this.status !== 200) return;

    let info = this.prototype.getAttribute('id').split('-');
    document.getElementById(info[1] + '-milestone1-' + info[2]).remove();
    document.getElementById(info[1] + '-milestone-' + info[2]).remove();
    document.getElementById('milestone' + info[2]).remove();
}

function createTaskGroupHandler() {
    if(this.status !== 200) return;

    let taskgroup = JSON.parse(this.responseText);
    let group_div = document.createElement('div');
    group_div.setAttribute('class', 'main-tab task-group border-hover flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-5');
    group_div.setAttribute('id', 'group-' + taskgroup.id_project + '-' + taskgroup.id);

    let createTaskRoute = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" + taskgroup.id_project + "/tasks/createtask/" + taskgroup.id + "/";

    group_div.innerHTML = '<div id="task-group-hover" class="mx-auto mb-1"> <a href="' + createTaskRoute + '"><i class="fas fa-plus fa-fw hover-icon mr-2"></i></a> ' +
        '<button id="editTaskGroup-' + taskgroup.id + '" type="button" data-toggle="modal" data-target="#editTaskGroupModal" '+
        'data-whatever="' + taskgroup.title + '" class="editTaskGroupButton mx-2 px-0"><i class="far fa-edit fa-fw hover-icon"></i></button> ' +
        '<i id="removeTaskGroup-' + taskgroup.id_project + '-' + taskgroup.id + '" class="remove-task-group far fa-trash-alt fa-fw hover-icon ml-2"></i> ' +
        '</div> <div class="d-flex flex-shrink-0 text-center my-1 mx-auto"> <h3>' + taskgroup.title + '</h3> </div>' +
        '<div class="drop-area px-3 overflow-auto pr-2 pl-2 mt-1 mb-2"> </div> </div>';

    let add_group_div = document.getElementById('add-group').parentElement;

    document.getElementById('task-groups').insertBefore(group_div, add_group_div);
    group_div.addEventListener('dragover', ev => ev.preventDefault());
    group_div.addEventListener('drop', taskGroupDropListener(group_div));

    let removeTaskGroup = document.getElementById('removeTaskGroup-' + taskgroup.id_project + '-' + taskgroup.id);
    removeTaskGroupListener.bind(removeTaskGroup);
    removeTaskGroup.addEventListener('click', removeTaskGroupListener);

    $('.border-hover').hover(
        function() {$(this).find('>:first-child .hover-icon').css('display', 'inline-block')},
        function() {$(this).find('>:first-child .hover-icon').css('display', 'none')}
    )
}

function editTaskGroupHandler() {
    if(this.status !== 200) return;

    let info = this.prototype.getAttribute('id').split('-');
    let group = document.getElementById('group-' + info[1] + '-' + info[2]);
    let title = document.querySelector('input#task-group-title-edit').value;
    group.querySelector('h3').textContent = title;
    group.querySelector('button#editTaskGroup-' + info[2]).setAttribute('data-whatever', title);
}

function removeTaskGroupHandler() {
    if(this.status !== 200) return;

    let info = this.prototype.getAttribute('id').split('-');


    let taskGroup = document.getElementById('group-' + info[1] + '-' + info[2]);

    for (let task of taskGroup.getElementsByClassName('task')) {
        document.getElementsByClassName('drop-area')[0].appendChild(task);
    }
    taskGroup.remove();
}


function updateProgressHandler() {
    if(this.status !== 200) return;

    let progress_value = document.querySelector('input#progressValue').value;
    let progress_code = document.querySelector('div.work-progress');
    let label = progress_code.querySelector('h6');
    let progress_bar = progress_code.querySelector('div.progress-bar');

    label.innerHTML = '<i class="fas fa-chart-line mr-1"></i>' + progress_value + '% done';
    progress_bar.setAttribute('style', 'width:' + progress_value + '%');
    progress_bar.setAttribute('aria-valuenow', progress_value);
}

function addTaskCommentHandler() {
    if (this.status !== 200) return;

    let item = JSON.parse(this.responseText);

    let id_project = this.prototype.getAttribute('id').split('-')[2];

    let discussion = document.querySelector('#discussion').firstElementChild;
    let add_comment = document.querySelector('form.add-task-comment');
    let new_comment = document.createElement('section');

    let profile_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + item.id_author;
    let edit_button = '<i id="edit-' + item.id + '-' + item.id_task + '-' + id_project + '" class="task-comment-edit far fa-edit"></i>';
    let delete_button = '<i id="comment-' + item.id + '-' + item.id_task + '-' + id_project + '" class="task-comment-delete far fa-trash-alt"></i>';

    new_comment.id = 'comment-' + item.id;
    new_comment.className = "card float-sm-left p-2 m-2 mt-3";
    new_comment.innerHTML = '<div class="d-flex justify-content-between" id="comment-header"> <h6 class="mb-2"><a href="' + profile_route + 
        '"><i class="fa fa-user" aria-hidden="true"></i> ' + item.author_name + '</a></h6> <h6 id="discussion-icons"> ' + edit_button + ' ' +
        delete_button + ' </h6> </div> <p class="mb-1">' + item.text + '</p>';

    let edit_comment = new_comment.querySelector('i.task-comment-edit');
    editTaskCommentListener.bind(edit_comment);
    edit_comment.addEventListener('click', editTaskCommentListener);

    let deletecomment = new_comment.querySelector('i.task-comment-delete');
    deleteTaskCommentListener.bind(deletecomment);
    deletecomment.addEventListener('click', deleteTaskCommentListener);

    discussion.insertBefore(new_comment, add_comment);
}

function editTaskCommentHandler() {
    if (this.status !== 200) return;

    let id = this.prototype.getAttribute('id');
    let info = id.split('-');
    let comment_div = document.getElementById('comment-' + info[1]);
    let input = comment_div.querySelector('textarea');
    let text = document.createElement('p');

    text.textContent = input.value.replace(/(\r\n|\n|\r)/gm, "");
    text.className = 'mb-1';
    text.setAttribute('id', id);

    comment_div.replaceChild(text, input);

    let old_button = comment_div.querySelector('#'+id);
    let new_button = old_button.cloneNode(true);
    old_button.parentElement.replaceChild(new_button,old_button);

    editTaskCommentListener.bind(new_button);
    new_button.addEventListener('click', editTaskCommentListener);
}

function deleteTaskCommentHandler() {
    if(this.status !== 200) return;

    document.getElementById('comment-' + this.prototype.getAttribute('id').split('-')[1]).remove();
}

function removeTaskHandler() {
    if(this.status !== 200) return;

    let info = this.prototype.getAttribute('id').split('-');
    let task = document.getElementById('task-' + info[1] + '-' + info[2]);
    
    let task_counter = task.parentElement.parentElement.querySelector('span.font-weight-light.mr-2');
    let counter = Number(task_counter.textContent.split(' ')[0]);

    switch(task_counter.parentElement.children.length) {
        case 1:
        case 3:              
            task_counter.textContent = (counter-1) + ' Tasks';
            break;
        case 2:            
            task_counter.textContent = (counter-1) + ' remaining';
            break;
    }

    task.remove();
}

function assignTaskHandler() {
    if(this.status !== 200) return;

    let item = JSON.parse(this.responseText);
    let search_bar = document.getElementsByClassName('search-bar')[0];
    let task = document.getElementsByClassName('sticky')[0];
    
    if(search_bar != null) {
        search_bar.querySelector('input').value = '';
        removeNotCheckedTeam(document.getElementById('search-content'));
    }

    if(task != null) {
        task.querySelector('p.teams').textContent = item.teams.length + ' Teams';
        task.querySelector('p.developers').textContent = item.developers + ' Developers';
    } else
        task = document.getElementById('content');

    let time_bar = task.querySelector('div.time-progress');
    time_bar.querySelector('h6').innerHTML = '<i class="far fa-clock mr-1"></i>' + item.timeLeft + ' days left';
    
    time_bar = time_bar.querySelector('.progress-bar');
    time_bar.className = 'progress-bar progress-bar-striped bg-' + (item.timePercentage == 100 ? 'danger' : 'warning') + ' progress-bar-animated';
    time_bar.setAttribute('style', "width:" + item.timePercentage + "%");
    time_bar.setAttribute('aria-valuenow', item.timePercentage);
}

function removeUserHandler() {
    if (this.status === 400) {
        let container = document.querySelector('#content');
        let alert = document.querySelector('div.alert');
        let message =
            "ERROR: This user cannot be removed because he’s a Team Leader or Project Manager." +
            " You must first reassign his role in order to be able to remove him." ;

        if(alert === null) {
            let error_div = document.createElement('div');

            error_div.className = "alert alert-danger";
            error_div.innerHTML = '<ul class="mb-0"> <li>' + message + '</li> </ul>';
            container.parentElement.insertBefore(error_div, container);
        }
        else
            alert.innerHTML = '<ul class="mb-0"> <li>' + message + '</li> </ul>';
        return;
    }

    if (this.status !== 200) return;

    let alert = document.querySelector('div.alert');
    if(!(alert === null)) alert.remove();

    let id_user = this.prototype.getAttribute('id');
    let card = document.getElementById("card-" + id_user);
    let container = card.parentElement;
    let image = card.getElementsByTagName('img')[0];
    let username = card.getElementsByTagName('span')[0];

    let new_card = document.createElement('div');
    new_card.setAttribute('id', "card-" + id_user);
    new_card.className = 'restore card';
    new_card.innerHTML = '<div class="card-body p-2">' +
        '<img src="' + image.src + '" width="50" height="50"' +
        'class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo"> '
        + '<span class="pl-2 pl-sm-4">' + username.textContent + '</span> <a id="' +
        id_user + '" class="restore-user float-right pt-2 pr-2">' +
        '<span>Restore</span> <i class="fas fa-trash-restore"></i> </a>' ;

    container.replaceChild(new_card, card);

    let new_restore = new_card.getElementsByClassName('restore-user')[0];
    restoreUserListener.bind(new_restore);
    new_restore.addEventListener('click', restoreUserListener);
}

function restoreUserHandler() {
    if (this.status !== 200) return;

    let alert = document.querySelector('div.alert');
    if(!(alert === null)) alert.remove();

    let id_user = this.prototype.getAttribute('id');
    let card = document.getElementById("card-" + id_user);
    let container = card.parentElement;
    let image = card.getElementsByTagName('img')[0];
    let username = card.getElementsByTagName('span')[0];

    let profile_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + id_user;

    let new_card = document.createElement('div');
    new_card.setAttribute('id', "card-" + id_user);
    new_card.className = 'card';
    new_card.innerHTML = '<div class="card-body p-2"> <a href="' + profile_route +
        '" > <img src="' + image.src + '" width="50" height="50"' +
        'class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">'
        + ' <span class="pl-2 pl-sm-4">' + username.textContent + '</span></a> <a id="' +
        id_user + '" class="remove-user float-right pt-2 pr-2">' +
        '<i class="fas fa-times"></i> </a>' ;

    container.replaceChild(new_card, card);

    let new_remove = new_card.getElementsByClassName('remove-user')[0];
    removeUserListener.bind(new_remove);
    new_remove.addEventListener('click', removeUserListener);
}

function removeTeamHandler() {
    if(this.status !== 200) return;

    document.getElementById('team-' + this.prototype.getAttribute('id').split('-')[1]).remove();
}

function removeProjectHandler() {
    if(this.status !== 200) return;

    document.getElementById('project -' + this.prototype.getAttribute('id').split('-')[1]).remove();
}

function editSearch() {
    if(this.status !== 200) return;

    let response = JSON.parse(this.responseText);

    let page = this.prototype.getAttribute('class').split(' ')[1];
    let container = document.getElementById('search-content');
    console.log(response)
    switch (page) {
        case 'globalSearch':
            container.innerHTML = '';
            if(document.getElementsByClassName('btn active')[0].lastChild.textContent == 'Users')
                printUsers(container, response, false, false, false);
            else
                printProjects(container, response, false);
            break;
        case 'adminUsers':
            container.innerHTML = '';
            printUsers(container, response, true, false, false);
            break;
        case 'adminTeams':
            container = container.firstElementChild;
            container.innerHTML = '';
            printTeams(container, response)
            break;
        case 'adminProjects':
            container.innerHTML = '';
            printProjects(container, response, true)
            break;
        case 'manageTeam':
            container = container.querySelector('#search-display');
            container.innerHTML = '';
            printUsers(container, response, false, true, false)
            break;
        case 'manageProject':
            container = container.querySelector('#search-display');
            let first_child = container.firstElementChild;
            container.innerHTML = '';

            if(first_child != null)
                container.appendChild(first_child);
            
            printUsers(container, response, false, false, true)
            break;
        case 'teamAssign':
            removeNotCheckedTeam(container);
            printTeamsInput(container, response);
            break;
    }
}

////////////
// OTHERS //
////////////

function changeRoadmapInfo(milestones, currentMilestone) {

    let old_milestone_id;

    for(let i = 1; i < milestones.length - 1; i++) {
        if(milestones[i].hasAttribute('aria-expanded')) {
            milestones[i].removeAttribute('aria-expanded');
            milestones[i].setAttribute('class', 'milestone-switch collapsed milestone-info text-center pb-3');

            milestoneSwitchListener.bind(milestones[i]);
            milestones[i].addEventListener('click', milestoneSwitchListener);
            old_milestone_id = milestones[i].getAttribute('id').split('-')[2];
        }
        else if(milestones[i].getAttribute('id').includes('milestone-' + currentMilestone['id'])) {
            milestones[i].setAttribute('aria-expanded', 'true');
            milestones[i].setAttribute('class', 'milestone-info active text-center pb-3');
        }
    }

    let milestone_content = document.getElementById('milestone' + old_milestone_id);
    let isProjectManager = (document.getElementById('create') !== null);

    if(milestone_content !== null) {
        if(old_milestone_id == currentMilestone.id){
            milestone_content.remove();
            return;
        }

        milestone_content.setAttribute('id', 'milestone' + currentMilestone.id);
        let header = milestone_content.querySelector('div.d-flex');
        let milestone_name = header.querySelector('h3');
        milestone_name.innerHTML = currentMilestone.name;

        if(isProjectManager) {
            milestone_name.innerHTML += ' <button type="button" data-toggle="modal" data-target="#editMilestoneModal"> ' +
                '<i class="far fa-edit ml-2"></i> </button> <i id="removeMilestone-' +
                currentMilestone.id_project + '-' + currentMilestone.id + '" class="remove-milestone far fa-trash-alt"></i>';

            let removeMilestone = milestone_name.querySelector('i.remove-milestone');
            removeMilestone.addEventListener('click', function() {
                let info = removeMilestone.getAttribute('id').split('-');
                sendAjaxRequest.call(this, 'delete', '/project/' + info[1] + '/roadmap/' + info[2] + '/remove', null, removeMilestoneHandler);
            })

            document.querySelector('#editMilestoneModal input#milestone-name').value = currentMilestone.name;
            document.querySelector('#editMilestoneModal input#milestone-deadline').value = currentMilestone.deadline.substr(0, 10);
            document.querySelector('#editMilestoneModal .update-milestone').setAttribute('id',
                'editMilestone-' + currentMilestone.id_project + '-' + currentMilestone.id);
        }

        header.querySelector('span').textContent = currentMilestone.tasks.length + ' remaining';

        let tasks_content = milestone_content.querySelector('div.mx-auto');
        tasks_content.innerHTML = createTaskHtml(currentMilestone.tasks, isProjectManager);
    }
    else {
        milestone_content = document.createElement('div');
        milestone_content.setAttribute('id', 'milestone' + currentMilestone.id);
        milestone_content.setAttribute('data-parent', '#content');
        milestone_content.className = "collapse show main-tab card border-left-0 border-right-0 rounded-0 p-2";
        milestone_content.innerHTML = ' <div class="d-flex justify-content-between align-items-center">' + '<h3>' + currentMilestone.name +
            (isProjectManager ?
                ' <button type="button" data-toggle="modal" data-target="#editMilestoneModal"> <i class="far fa-edit ml-2"></i> </button> <i id="removeMilestone-' +
                currentMilestone.id_project + '-' + currentMilestone.id + '" class="remove-milestone far fa-trash-alt"></i> '
                : ''
            ) + '</h3> <span class="font-weight-light mr-2 flex-shrink-0">' + currentMilestone.tasks.length +
            ' remaining</span></div> <div class="mx-auto"> ' + createTaskHtml(currentMilestone.tasks, isProjectManager) + ' </div> </div>';

        if(isProjectManager) {
            let removeMilestone = milestone_content.querySelector('i.remove-milestone');
            removeMilestone.addEventListener('click', function() {
                let info = removeMilestone.getAttribute('id').split('-');
                sendAjaxRequest.call(this, 'delete', '/project/' + info[1] + '/roadmap/' + info[2] + '/remove', null, removeMilestoneHandler);
            })

            milestone_content.innerHTML += createEditMilestoneModal(currentMilestone);
        }

        document.getElementById('content').appendChild(milestone_content);
    }

    let remove_task = document.getElementsByClassName('remove-task');

    for(let i = 0; i< remove_task.length; i++) {
        removeTaskListener.bind(remove_task[i]);
        remove_task[i].addEventListener('click', removeTaskListener);
    }

    $('.border-hover').hover(
        function() {$(this).find('>:first-child .hover-icon').css('display', 'inline-block')},
        function() {$(this).find('>:first-child .hover-icon').css('display', 'none')}
    )
}

function createEditMilestoneModal(currentMilestone) {
    let modal = '<div class="modal fade" id="editMilestoneModal" tabindex="-1" role="dialog" aria-labelledby="editMilestoneModalTitle" ' +
        'aria-hidden="true"> <div class="modal-dialog modal-dialog-centered" role="document"> <div class="modal-content"> ' +
        '<div class="modal-header"> <h5 class="modal-title" id="editMilestoneModalTitle">Edit Milestone</h5> ' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> ' +
        '</div> <div class="modal-body"> <div class="form-group"> <label for="milestone-name" class="col-form-label">Name:</label> ' +
        '<input type="text" class="form-control" id="milestone-name" value="' + currentMilestone.name + '"> ' +
        '<label for="milestone-deadline" class="col-form-label">Deadline:</label> <input type="date" class="form-control" id="milestone-deadline" ' +
        'min="' + new Date().toDateString().substr(0,10) + '" value="' + currentMilestone.deadline.substr(0, 10) + '"> </div> </div> ' +
        '<div id="brand-btn" class="modal-footer"> <button id="editMilestone-' + currentMilestone.id_project + '-' + currentMilestone.id +
        '" type="button" class="update-milestone btn btn-primary" data-dismiss="modal">Save changes</button> </div> </div> </div> </div>';

    return modal;
}

function createTaskHtml(tasks, isProjectManager) {
    let html = '';

    for(let i = 0; i < tasks.length; i++) {
        let task = tasks[i];
        html += ' <section id="task-' + task.id_project + '-' + task.id + '" class="task card border-hover float-sm-left p-2 m-2 mt-3"> ';

        if(isProjectManager) {
            let edit_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" + task.id_project + "/tasks/" + task.id + "/edit";
            let assign_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" + task.id_project + "/tasks/" + task.id + "/assign";

            html += '<div class="mx-auto mb-1"> <a href="' + edit_route + '"><i class="far fa-edit hover-icon mr-2"></i></a> ' +
                    '<a href="' + assign_route + '"><i class="fas fa-link fa-fw hover-icon mx-2"></i></a>' +
                    '<i id="removeTask-' + task.id_project + '-' + task.id + '" class="remove-task far fa-trash-alt fa-fw hover-icon ml-2"></i> </div>';
        }

        let task_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" + task.id_project + "/tasks/" + task.id;

        html += '<h6 class="text-center mb-auto"><a href="' + task_route + '">' + task.title + '</a></h6> ' +
                '<p class="ml-1 m-0">' + task.teams.length + ' Teams</p>' +
                '<p class="ml-1 mb-2">' + task.developers + ' Developers</p>' +
                '<div class="work-progress mx-2 mb-1"> <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>' +
                task.progress + '% done</h6> <div class="progress"> ' +
                '<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" ' +
                    'role="progressbar" style="width:' + task.progress + '%" aria-valuenow="' +
                    task.progress + '" aria-valuemin="0" aria-valuemax="100"></div> </div> </div> ' +
                '<div class="time-progress mx-2 my-1"> <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>' +
                task.timeLeft + ' days left</h6> <div class="progress"> ' +
                '<div class="progress-bar progress-bar-striped bg-' + (task.timePercentage == 100 ? 'danger' : 'warning') +
                    ' progress-bar-animated" role="progressbar" style="width:' + task.timePercentage + '%" aria-valuenow="' +
                    task.timePercentage + '" aria-valuemin="0" aria-valuemax="100"> </div> </div> </div> </section>';
    }

    return html;
}

function printUsers(container, users, isAdminView, manageTeam, manageProject) {

    for (const user of users) {
        let card = document.createElement('div');
        let profile_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + user.id;
        let image_src = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/img/profile.png";

        if(checkImage("http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/img/profile/" + user.id + '.jpg'))
            image_src = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/img/profile/" + user.id + '.jpg';
        else if(checkImage("http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/img/profile/" + user.id + '.png'))
            image_src = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/img/profile/" + user.id + '.png';

        if(isAdminView) {
            card.setAttribute('id', user.id);
            card.setAttribute('class', 'row justify-content-center pb-4');

            card.innerHTML = '<div class="col-11 col-md-8 ali"> <div id="card-' + user.id + '" class="' + (user.is_active? '':'restore') +
                ' card"> <div class="card-body p-2"> ' + (user.is_active? '<a href="' + profile_route + '">':'') + '<img src="' + image_src +
                '" width="50" height="50" class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">' +
                ' <span class="pl-2 pl-sm-4">' + user.first_name + ' ' + user.last_name + '</span>' + (user.is_active? '</a>':'') +
                ' <a id="' + user.id + '" class="' + (user.is_active? 'remove':'restore') + '-user float-right pt-2 pr-2">' +
                (user.is_active? '<i class="fas fa-times"></i>':'<span>Restore</span> <i class="fas fa-trash-restore"></i>') +
                ' </a> </div> </div> </div> </div> ';
        }
        else {
            let icons = '';
            card.setAttribute('id', user.id);

            if(manageProject) {                
                card.setAttribute('class', 'profile card my-3 col-sm-12 col-md-6 pl-0');

                icons = '<i class="add-member fas fa-plus"></i>';
            } else {
                card.setAttribute('class', 'profile card my-3');

                if(manageTeam)
                    icons = '<i class="add-member fas fa-plus"></i>';
                else
                    icons = '<i id="user-' + user.id + '" class="follow ' + (user.follow ? 'fas' : 'far') + ' fa-star" style="cursor: pointer;"></i>';
            }

            card.innerHTML = '<div class="card-body p-2"> <a href="' + profile_route + '"> <img src="' + image_src +
                '" width="50" height="50" class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">' +
                ' <span class="pl-2 pl-sm-4">' + user.first_name + ' ' + user.last_name + '</span></a> <a class="float-right pt-2 pr-2"> ' +
                icons + ' </a> </div> </div>';
        }

        container.appendChild(card);
    }

    let add_member = document.getElementsByClassName('add-member');
    for (const add of add_member) {
        addMemberListener.bind(add);
        add.addEventListener('click', addMemberListener);
    }

    let follow = document.getElementsByClassName('follow');
    for(let i = 0; i < follow.length; i++) {
        follow[i].addEventListener('click', function () {
            let id_user = follow[i].getAttribute('id').split('-')[1];
            sendAjaxRequest.call(this, 'get', '/follow/' + id_user, null, followHandler);
        })
    }

    let remove_user = document.getElementsByClassName('remove-user');
    for(let i = 0; i < remove_user.length; i++) {
        removeUserListener.bind(remove_user[i]);
        remove_user[i].addEventListener('click', removeUserListener)
    }

    let restore_user = document.getElementsByClassName('restore-user');
    for(let i = 0; i < restore_user.length; i++) {
        restoreUserListener.bind(restore_user[i]);
        restore_user[i].addEventListener('click', restoreUserListener);
    }
}

function checkImage(url) {
    var http = new XMLHttpRequest();

    http.open('HEAD', url, false);
    http.send();

    return http.status != 404; 
}

function printProjects(container, projects, isAdminView) {

    for (const project of projects) {
        let card = document.createElement('div');
        card.setAttribute('id','project');
        card.setAttribute('class','card py-2 px-3 mt-4 mx-3 mx-sm-5 mb-2');
        card.setAttribute('style','border-top-width: 0.25em; border-top-color: ' + project.color + ';');

        let overview_route = project.isLocked ? '' : "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" + project.id;
        
        let icons;

        if(isAdminView) {
            let edit_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/admin/projects/" + project.id + '/edit';
            icons = '<a href="' + edit_route + '"><i class="far fa-edit"></i> </a>' + '<a class="pl-2"> <i class="far fa-trash-alt"></i></a>';
        }
        else
            icons = '<a><i id="project-' + project.id + '" class="favorite ' + (project.favorite ? 'fas' : 'far') + ' fa-star" ' +
                'style="cursor: pointer;" aria-hidden="true"></i></a> <i class="pl-1 fa fa-' + (project.isLocked ? 'lock' : 'unlock') +
                '" aria-hidden="true"></i>';

        let manager_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + project.id_manager;
        card.innerHTML = '<div class="d-flex justify-content-between"> <a ' + (project.isLocked ? '' : 'href="' + overview_route) + '"> <h5 class="card-title my-1">' +
            project.name + '</h5> </a> <h5 class="flex-grow-1 d-flex justify-content-end align-items-center"> ' + icons +
            ' </h5> </div> <div class="row"> <div class="col-sm-7"> Project Manager: <a href="' + manager_route + '"> <h6 class="d-inline-block mb-3">' +
            project.manager + '</h6> </a> <br> Brief Description: <h6 class="d-inline">' + project.description + '</h6> </div>' +
            '<div class="col-sm-5 mt-3 mt-sm-0"> Statistics <h6> <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>' + project.teams +
            ' Teams involved</p> <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>' + project.tasks_done.length +
            ' Tasks concluded</p> <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>' + (project.tasks_ongoing.length + project.tasks_todo.length) +
            ' Tasks remaining</p> </h6> </div> </div> </div>';

        container.appendChild(card);
    }

// TODO: ADD Remove Project Listeners


    let favorite = document.getElementsByClassName('favorite');

    for(let i = 0; i < favorite.length; i++) {
        favorite[i].addEventListener('click', function () {
            let id_project = favorite[i].getAttribute('id').split('-')[1];
            sendAjaxRequest.call(this, 'get', '/favorites/' + id_project, null, favoriteHandler);
        })
    }
}

function printTeams(container, teams) {

    for (const team of teams) {
        let card = document.createElement('div');
        card.setAttribute('class','col-lg-4 col-sm-6 my-3');
        
        let members = '';
        for (const member of team.members) {
            members += ' <a href="http://' + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + '/profile/' + member.id + '"> <p>' + member.first_name + ' ' + member.last_name + '</p> </a>';
        }

        let leader_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + team.leader.id;
        let edit_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/admin/teams/" + team.id + "/edit";

        card.innerHTML = '<div class="card text-center"> <div class="card-header" style="clear: both;"> <p id="team-name" class="m-0" style="float: left;">'
            + team.name + '</p> <p class="m-0" style="float: right;">' + (team.skill == null ? '' : team.skill) + '</p> </div> <div class="card-body">' +
            '<a href="' + leader_route + '"><p style="font-weight: bold;">' + team.leader.first_name + ' ' + team.leader.last_name +
            '</p></a> <div class="mt-3"> ' + members + '</div> <a id="edit-button" href="' + edit_route + '" class="btn mt-3" role="button">Edit</a>' +
            ' <a id="edit-button" class="btn mt-3" role="button">Remove</a> </div> </div> </div>';

        container.appendChild(card);
    }

    let remove_team = document.getElementsByClassName('remove-team');

    for(let i = 0; i < remove_team.length; i++) {
        removeTeamListener.bind(remove_team[i]);
        remove_team[i].addEventListener('click', removeTeamListener);
    }
}

function removeNotCheckedTeam(container) {

    for (let i = 0; i< container.children.length; i++) {
        let input = container.children[i].querySelector('input');

        if(input.checked != true){
            container.children[i].remove();
            i--;
        }
    }
}

function printTeamsInput(container, teams) {
    let first_element = container.firstElementChild;

    for (const team of teams) {

        let card = document.createElement('div');
        card.setAttribute('id', team.id);
        card.setAttribute('class', 'card open flex-row justify-content-between p-2 mx-3 my-2');

        card.innerHTML = '<div class="custom-control custom-checkbox"> <input type="checkbox" name="team" class="custom-control-input" id="team-' +
            team.id + '"> <label class="custom-control-label team-name" for="team-' + team.id + '">' + team.name + '</label></div>' +
            (team.skill == null ? '' : team.skill) + ' </div>';

        if(first_element == null)
            container.appendChild(card);
        else
            container.insertBefore(card, first_element);
    }
}

let submitForm = document.getElementById('submit-project-form')
let submitManageProjectForm = document.getElementById('submit-manage-project-form')

if(submitManageProjectForm != null) {
    submitManageProjectForm.addEventListener('click', function () {
        let name = document.getElementById('projectName')
        let description = document.getElementById('projectDescription')
        let managerID = document.getElementsByClassName('remove-member')[0]
        let searchBar = document.getElementsByClassName('search-bar')[0]

        if(name.value === "")
            blockHelpNode(name.parentElement, "Project name must be defined !", "red")
        else if(description.value === "")
            blockHelpNode(description.parentElement, "Project description must be defined !", "red")
        else if(managerID == null)
            blockHelpNode(searchBar, "Project manager must be defined !", "red");
        else {
            document.getElementById('project-manager-ID').setAttribute('value', managerID.parentElement.parentElement.parentElement.getAttribute('id'))
            submitForm.click()
        }
    })
}

//////////
// AJAX //
//////////

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.prototype = this;
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}


function validateEmail(email) {
    let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
