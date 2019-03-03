// Attach a submit handler to the form
$(function() {
    $(".rating input").click(function (event) {

        // Stop form from submitting normally
        event.preventDefault();

        // Get some values from elements on the page:
        var $form = $(this).parents('form'),
            vote = $(this).val(),
            url = $form.attr("action")
            pageId = $form.find('input[name=pageID]').val()
            settingId = $form.find('input[name=settingID]').val();

        var params = JSON.stringify({v: vote ,u: window.location.href, p: pageId, s: settingId});

        // Send the data using post
        var posting = $.post( url, { p: params } );

        // Put the results in a div
        posting.done(function( data ) {
            console.log(data);
        });
    });
    // console.log($('input[name=rating]'));
});
