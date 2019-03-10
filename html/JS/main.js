$('.main-tab > .collapse').on('show.bs.collapse', function() {
    $(this).parent().css('background-color', '#f1f2f3');
})

$('.main-tab > .collapse').on('hide.bs.collapse', function() {
    $(this).parent().css('background-color', '#f8f9fa');
})


let milestoneCount = $('.milestone').length;
let milestoneDoneCount = $('.milestone > .fa-dot-circle').length;

if (milestoneCount > 0) {
    let leftWidth = (milestoneDoneCount + .5) * 100 / (milestoneCount + 1);

    $('.roadmap-left').css('width', leftWidth + "%");
    $('.roadmap-right').css({"left": leftWidth + "%", "width": 100 - leftWidth + "%"});
}