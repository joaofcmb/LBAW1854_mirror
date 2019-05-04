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

for(let i = 0; i < deleteThread.length; i++) {
    deleteThread[i].addEventListener('click', function () {
        let id_thread = deleteThread[i].getAttribute('id').split('-')[1];
        console.log(id_thread)
        //sendAjaxRequest.call(this, 'get', '/favorites/' + id_project, null, favoriteHandler);
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
