$('.main-tab > .collapse').on('show.bs.collapse', function() {
    $(this).parent().css('background-color', '#f1f2f3');
})

$('.main-tab > .collapse').on('hide.bs.collapse', function() {
    $(this).parent().css('background-color', '#f8f9fa');
})