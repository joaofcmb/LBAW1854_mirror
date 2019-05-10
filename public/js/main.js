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
        
        // TODO: Password change
        // if (oldPassword.value != "" && newPassword != "" &&
        //     newPassword.value === confirmPassword.value) {
        //     to_send.new_password =  newPassword.value;
        // }

        // oldPassword.value = "";
        // newPassword.value = "";
        // confirmPassword.value = "";

        sendAjaxRequest.call(this, 'post', '/profile/' + id + '/edit', to_send, null);
    })
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
let milestones = document.getElementsByClassName('milestone-switch')

for(let i = 0; i < milestones.length; i++) {
    milestones[i].addEventListener('click', function () {
        let milestone = milestones[i].getAttribute('id').split('-');
        let id_project = milestone[0];
        let id_milestone = milestone[2];

        //sendAjaxRequest.call(this, 'post', '/api/project/' + id_project + '/roadmap/changeview', {milestone: id_milestone}, changeMilestoneHandler);
    })
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
    restore_user[i].addEventListener('click', restoreUserListener)
}

//////////////
// HANDLERS //
//////////////

function editBiography() {
    let biography_div = document.getElementById('biography');
    let textArea = biography_div.getElementsByTagName('textarea')[0];
    let biography_text = document.createElement('p');
    
    biography_text.textContent = textArea.value;
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
    
    let thread = document.querySelector('#thread-content');
    let add_comment = document.querySelector('form.add-comment');

    let new_comment = document.createElement('div');

    let profile_route = "{{ route('profile', ['id' => " + item.id_author + " ]) }}"

    let delete_button = '<i id="comment-' + item.id + '-' + item.id_thread + '-0"  class="comment-delete fas fa-trash-alt mx-2" style="cursor: pointer;"></i>';

    new_comment.id = 'comment-' + item.id;
    new_comment.className = "card pb-0 px-3 pt-3 my-3";
    new_comment.innerHTML = '<div class="row"><div class="col"><a class="d-flex flex-row pt-1" href="'+
        profile_route + '"><i class="fas fa-user mr-1"></i><h6>' +
        item.author_name + '</h6></a></div><div class="col text-right"><a style="cursor: pointer;">' +
        '<a style="cursor: pointer;"><i class="fas fa-pen mx-3"></i></a>' +
        delete_button + '</a></div></div><p class="mt-2">' + item.text + '</p>';

    let deletecomment = new_comment.querySelector('i.comment-delete');
    deleteThreadCommentListener.bind(deletecomment);
    deletecomment.addEventListener('click', deleteThreadCommentListener);
    
    thread.insertBefore(new_comment, add_comment);
}

function deleteThreadCommentHandler() {
    document.getElementById('comment-' + this.prototype.getAttribute('id').split('-')[1]).remove();
}

function changeMilestoneHandler() {
    let item = JSON.parse(this.responseText);
    let currentMilestone = item[0];

    changeRoadmapInfo(document.getElementById(this.prototype.getAttribute('id')).parentElement.children, currentMilestone);
}

function removeUserHandler() {
    if (this.status !== 200) return;

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
        'class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">'
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

    let id_user = this.prototype.getAttribute('id');
    let card = document.getElementById("card-" + id_user);
    let container = card.parentElement;
    let image = card.getElementsByTagName('img')[0];
    let username = card.getElementsByTagName('span')[0];

    let profile_route = "{{ route('profile', ['id' => " + id_user + "]) }}";

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

            old_milestone_id = milestones[i].getAttribute('id').split('-')[2];
        }
        else if(milestones[i].getAttribute('id').includes('milestone-' + currentMilestone['id'])) {
            milestones[i].setAttribute('aria-expanded', 'true');
            milestones[i].setAttribute('class', 'milestone-info active text-center pb-3');
        }
    }

    return old_milestone_id;
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
    return Object.keys(data).map(function(k){
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}
