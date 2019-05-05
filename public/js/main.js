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


let milestones = document.getElementsByClassName('milestone-switch')

for(let i = 0; i < milestones.length; i++) {
    milestones[i].addEventListener('click', function () {
        let milestone = milestones[i].getAttribute('id').split('-');
        let id_project = milestone[0];
        let id_milestone = milestone[2];

        //sendAjaxRequest.call(this, 'post', '/api/project/' + id_project + '/roadmap/changeview', {milestone: id_milestone}, changeMilestoneHandler);
    })
}


//////////////
// HANDLERS //
//////////////

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

    let delete_button = '<i id="comment-' + item.id + '-' + item.id_thread + '-0"  class="comment-delete fas fa-trash-alt mx-2"></i>';

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
