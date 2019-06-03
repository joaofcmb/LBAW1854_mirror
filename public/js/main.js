///////////////
// BOOTSTRAP //
///////////////

// $('.main-tab > .collapse').on('show.bs.collapse', function() {
//     $(this).parent().css('background-color', '#f1f2f3')
// })

// $('.main-tab > .collapse').on('hide.bs.collapse', function() {
//     $(this).parent().css('background-color', '#f8f9fa')
// })

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

let edit_biography = document.getElementById('edit-biography');

if(edit_biography !== null) {
    edit_biography.addEventListener('click', function (event) {
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

        textArea.addEventListener( 'keyup', function(event) {
            if(event.keyCode == '13') {
                let id_user = textArea.getAttribute('id')
                let biography = textArea.value;

                sendAjaxRequest.call(this, 'post', '/profile/' + id_user + '/edit', {biography: biography}, editBiography);
            }
        })
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

// TASKS //

let update_progress = document.querySelector('button.update-progress');

if(update_progress != null)
    update_progress.addEventListener('click', function() {
        let info = this.getAttribute('id').split('-');
        let progress = document.querySelector('input#progressValue').value;

        sendAjaxRequest.call(this, 'post', '/project/' + info[1] + '/tasks/' + info[2] + '/updateprogress', {progress: progress}, updateProgressHandler);
    })

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

    let createTaskRoute = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" +
        taskgroup.id_project + "/tasks/createtask/" + taskgroup.id + "/";

    group_div.innerHTML = '<div id="task-group-hover" class="mx-auto mb-1"> <a href="' + createTaskRoute + '"><i class="fas fa-plus fa-fw hover-icon mr-2"></i></a> ' +
        '<button id="editTaskGroup-' + taskgroup.id + '" type="button" data-toggle="modal" data-target="#editTaskGroupModal" '+
        'data-whatever="' + taskgroup.title + '" class="editTaskGroupButton mx-2 px-0"><i class="far fa-edit fa-fw hover-icon"></i></button> ' +
        '<i id="removeTaskGroup-' + taskgroup.id_project + '-' + taskgroup.id + '" class="remove-task-group far fa-trash-alt fa-fw hover-icon ml-2"></i> ' +
        '</div> <div class="d-flex flex-shrink-0 text-center my-1 mx-auto"> <h3>' + taskgroup.title + '</h3> </div>' +
        '<div class="px-3 overflow-auto pr-2 pl-2 mt-1 mb-2"> </div> </div>';

    let add_group_div = document.getElementById('add-group').parentElement;

    document.getElementById('task-groups').insertBefore(group_div, add_group_div);

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
    document.getElementById('group-' + info[1] + '-' + info[2]).remove();
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

function removeTaskHandler() {
    if(this.status !== 200) return;

    let info = this.prototype.getAttribute('id').split('-');
    let task = document.getElementById('task-' + info[1] + '-' + info[2]);
    task.remove();
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
            let edit_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") +
                "/project/" + task.id_project + "/tasks/" + task.id + "/edit";
            let assign_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") +
                "/project/" + task.id_project + "/tasks/" + task.id + "/assign";

            html += '<div class="mx-auto mb-1"> <a href="' + edit_route + '"><i class="far fa-edit hover-icon mr-2"></i></a> ' +
                    '<a href="' + assign_route + '"><i class="fas fa-link fa-fw hover-icon mx-2"></i></a>' +
                    '<i id="removeTask-' + task.id_project + '-' + task.id + '" class="remove-task far fa-trash-alt fa-fw hover-icon ml-2"></i> </div>';
        }

        let task_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") +
            "/project/" + task.id_project + "/tasks/" + task.id;

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

//////////
// AJAX //
//////////

// Documentar funçoes criadas no search controller e ajustar estas do js para os sitios certos
// Asssociado ao display pode ser necessario acrescentar atributos a serem retornados
// Display do HTML

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

    if(this.nextElementSibling.getAttribute('id') !== 'action-button')
        manager_id = Number(this.nextElementSibling.getAttribute('id'));

    sendAjaxRequest.call(this, 'post', '/api/search/data', {'query': query, 'data': 'Users', 'constraints': [manager_id]}, editSearch)
}

function teamAssign() {
    let teams = []
    let query = this.firstElementChild.firstElementChild.value;

    if(this.parentElement.children.length > 1) {
        for(let index = 1; index < this.parentElement.children.length; index++)
            teams.push(Number(this.parentElement.children[index].getAttribute('id')))
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
                printUsers(container, response, false);
            else
                printProjects(container, response, false);
            break;
        case 'adminUsers':
            container.innerHTML = '';
            // 'adminView' => true
            printUsers(container, response, true)
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
            // 'isLeader' => false,
            // 'user' => $user,
            // 'manageTeam' => true
            printUsers(container, response)
            break;
        case 'manageProject':

            break;
        case 'teamAssign':
            // clear non checked teams on display
            printTeamsInput(container, response);
            break;
    }

}

function printUsers(container, users, isAdminView) {
    /* @isset ($adminView)
    <div id="{{ $user->id }}" class="row justify-content-center pb-4">
        <div class="col-11 col-md-8 ali">
            @if(!$user->is_active)
                <div id="card-{{ $user->id }}" class="restore card">
            @else
                <div id="card-{{ $user->id }}" class="card">
            @endif
                <div class="card-body p-2">
                    @if($user->is_active)
                        <a href="{{ route('profile', ['id' => $user->id]) }}">
                    @endif
                        <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                                class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">
                        <span class="pl-2 pl-sm-4">{{ $user->username }}</span>
                    @if($user->is_active)
                        </a>
                    @endif
                    <a id="{{ $user->id }}" class="{{ $user->is_active? 'remove' : 'restore' }}-user float-right pt-2 pr-2">
                        @if(!$user->is_active)
                            <span>Restore</span>
                            <i class="fas fa-trash-restore"></i>
                        @else
                            <i class="fas fa-times"></i>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    @if($isLeader)
        <div id="{{ $leader->id }}"class="profile card my-3">
            <div class="card-body text-center">
                <a href="{{ route('profile', ['id' => $leader->id]) }}">
                    <img src="{{ asset('img/profile.png') }}" width="125px" height="125px"
                            class="profile-img-team d-inline-block rounded-circle align-self-center my-3 my-md-1 p-md-0 p-lg-3 p-xl-0 "
                            alt="User photo">
                    <p class="m-0 pt-2">{{ $leader->username }}</p>
                </a>
                <a class="float-right" style="cursor: pointer;">
                    @if($leader->id != Auth::user()->getAuthIdentifier())
                        <i id="user-{{ $leader->id }}" class="follow {{ $leader->follow ? 'fas' : 'far' }} fa-star"></i>
                    @endif
                </a>
            </div>
        </div>
    @else
        @isset($manager)
            @isset($teamMember)
                <div id="{{ isset($user->id_user) ? $user->id_user : $user->id }}" class="profile card my-3">
            @else
                <div id="{{ isset($user->id_user) ? $user->id_user : $user->id }}" class="profile card my-3 col-sm-12 col-md-6 pl-0">
            @endisset
        @else
            <div class="profile card my-3">
        @endisset
            <div class="card-body p-2">
                @isset($user->id_user)
                    <a href="{{ route('profile', ['id' => $user->id_user]) }}">
                @else
                    <a href="{{ route('profile', ['id' => $user->id]) }}">
                @endisset
                    <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                            class="d-inline-block rounded-circle align-self-center my-auto"
                            alt="User photo">
                    <span class="pl-2 pl-sm-4">{{ $user->username }}</span>
                </a>
                <a class="float-right pt-2 pr-2">
                    @isset($manager)
                        @isset($teamMember)
                            @if($teamMember)
                                <i class="fas fa-user-tie" style="color:grey;"></i>
                            @endif
                        @endisset
                        <i class="fas fa-fw fa-times text-danger"></i>
                    @else
                        @isset($manageTeam)
                            <i class="fas fa-plus"></i>
                        @else
                            @if($user->id != Auth::user()->getAuthIdentifier())
                                <i id="user-{{ $user->id }}" class="follow {{ $follow ? 'fas' : 'far' }} fa-star" style="cursor: pointer;"></i>
                            @endif
                        @endisset
                    @endisset
                </a>
            </div>
        </div>
    @endif
@endisset */

}

function printProjects(container, projects, isAdminView) {

    // for (const project of projects) {
    //     let card = document.createElement('div');
    //     card.setAttribute('class','card py-2 px-3 mt-4 mx-3 mx-sm-5 mb-2');
    //     card.setAttribute('style','border-top-width: 0.25em; border-top-color: ' + project.color + ';');

    //     let overview_route = project.isLocked ? '' : 
    //         "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/project/" + project.id_project;
        
    //     let icons;

    //     if(isAdminView) {
    //         let edit_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + 
    //             "/admin/projects/" + project.id_project + '/edit';
    //         icons = '<a href="' + edit_route + '"><i class="far fa-edit"></i> </a>' + '<a class="pl-2"> <i class="far fa-trash-alt"></i></a>';
    //     }
    //     else
    //         icons = '<a><i id="project-' + project.id + '" class="favorite ' + project.favorite ? 'fas' : 'far' + ' fa-star" ' + 
    //             'style="cursor: pointer;" aria-hidden="true"></i></a> <i class="pl-1 fa fa-' + project.isLocked ? 'lock' : 'unlock' + 
    //             '" aria-hidden="true"></i>';

    //     let manager_route = "http://" + window.location.hostname + (window.location.port != ""? ":"+window.location.port : "") + "/profile/" + project.id_manager;
    //     card.innerHTML = '<div class="d-flex justify-content-between"> <a href="' + overview_route + '"> <h5 class="card-title my-1">' +
    //         project.name + '</h5> </a> <h5 class="flex-grow-1 d-flex justify-content-end align-items-center"> ' + icons + 
    //         '</h5> </div> <div class="row"> <div class="col-sm-7"> Project Manager: <a href="' + manager_route + '"> <h6 class="d-inline-block mb-3">' +
    //         project.manager + '</h6> </a> <br> Brief Description: <h6 class="d-inline">' + project.description + '</h6> </div>' +
    //         '<div class="col-sm-5 mt-3 mt-sm-0"> Statistics <h6> <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>' + project.teams + 
    //         ' Teams involved</p> <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>' + sizeof(project.tasks_done) + 
    //         ' Tasks concluded</p> <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>' + (project.tasks_ongoing.length + project.tasks_todo.length) +
    //         ' Tasks remaining</p> </h6> </div> </div> </div>';

    //     container.appendChild(card);
    // }
}

function printTeams(container, teams) {
    /*
    <div class="col-sm-4 my-3">
        <div class="card text-center">      
        <div class="card-header" style="clear: both;">
            <p id="team-name" class="m-0" style="float: left;">{{ $team->name }}</p>
            <p class="m-0" style="float: right;">{{ $team->skill == null ? '' : $team->skill }}</p>
        </div>
        <div class="card-body">
            <a href="{{ route('profile', ['id' => $team->leader->id]) }}">
                <p style="font-weight: bold;">{{ $team->leader->username }}</p>
            </a>
            <div class="mt-3">
            @foreach($team->members as $member)
                <a href="{{ route('profile', ['id' => $member->id]) }}">
                    <p>{{ $member->username }}</p>
                </a>
            @endforeach
            </div>
                <a id="edit-button" href="{{ route('admin-edit-team', ['id' => $team->id]) }}" class="btn mt-3" role="button">Edit</a>
                <a id="edit-button" href="" class="btn mt-3" role="button">Remove</a>
            </div>
        </div>
    </div> 
    */
}

function printTeamsInput(container, teams) {
    /* 
    <div id="{{ $team->id }}" class="card open flex-row justify-content-between p-2 mx-3 my-2">
        <div class="custom-control custom-checkbox">
            <input checked type="checkbox" class="custom-control-input" id="team{{ $team->id }}">
            <label class="custom-control-label team-name" for="team{{ $team->id }}">{{ $team->name }}</label>
        </div>
        {{ $team->skill == null ? '' : $team->skill }}
    </div> 
    */
}

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

// DRAG AND DROP (Task Groups) //
let tasks = document.getElementsByClassName('draggable');
let taskGroups = document.getElementsByClassName('task-group');

for (const task of tasks) {
    task.addEventListener('dragstart', function(ev) {
        ev.dataTransfer.setData("text/plain", ev.target.id);
    });
}

for (const taskGroup of taskGroups) {
    taskGroup.addEventListener('dragover', function(ev) {
        ev.preventDefault();
    });

    taskGroup.addEventListener('drop', function(ev) {
        ev.preventDefault();

        let movedTask = document.getElementById(ev.dataTransfer.getData("text/plain"));
        let taskId = movedTask.id.split('-')[2];
        let groupIds = taskGroup.id.split('-');
        sendAjaxRequest('post', '/project/' + groupIds[1] + '/tasks/' + taskId + '/assign-group/' + groupIds[2], {}, new function() {
            ev.target.getElementsByClassName('drop-area')[0].appendChild(movedTask);
        });
    })
}



