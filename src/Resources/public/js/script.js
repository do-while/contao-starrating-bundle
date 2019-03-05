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
        posting.done(function( response ) {
            // console.log($('.starability-result').data('rating'));
            if(response.new) {
                $('.rating').hide();
                $('.no-rating').show();
                $('.average').text(response.average);
                $('.starability-result').data('rating',response.average);
                $('.rating-microdata').find('[itemprop=ratingValue]').prop('content',response.average);
                $('.rating-microdata').find('[itemprop=worstRating]').prop('content',response.min);
                $('.rating-microdata').find('[itemprop=bestRating]').prop('content',response.max);
                $('.rating-microdata').find('[itemprop=ratingCount]').prop('content',response.count);

            }
        });
    });
    // console.log($('input[name=rating]'));
});
