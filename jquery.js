// https://learn.jquery.com/
// https://jqueryui.com/

$(document).ready(function() {
    $('#button').click(function() {
        var toAdd = $('input[name=checkListItem]').val();
        $('.list').append('<div class="item">' + toAdd + '</div>').click(function() {
            $(this).remove();
        });
    });
});

var $p = $("<p>I'm a paragraph!</p>")

.fadeOut('fast' / 'slow', (op 0-1)) / .fadeIn("")
.hide() / .show()
.toggle()
.children("")
.next() / .prev()
.first()
.append("") / .prepend("")
.before("") / .after("")
.empty() / .remove()
.addClass("") / .removeClass("")
.toggleClass("")
.height("") / .width("")
.css("", "")
.html("")
.val() //for form inputs
.html() //any HTML elements
.text() //some text
.animate({left / top: '+/-=(px)'}, (ms))
.slideDown(ms) / .slideUp(ms)
.effect('explode / bounce / slide / ...', ...) //jQuery UI
.accordion({...}) //jQuery UI
.draggable() //jQuery UI
.resizable() //jQuery UI
.selectable() //jQuery UI
.sortable() //jQuery UI

.click(function() {});

.dblclick(function() {});

.hover(
    function() {
        $(this).addClass('active');
    }, function() {
        $(this).removeClass('active');
    }
);

.focus(function() {});

.keydown(function() {});

.keypress(function() {});

.keyup(function() {});


var main = function() {
    $('.article').click(function() {
        $('.article').removeClass('current');
        $('.description').hide();
        $(this).addClass('current');
        $(this).children('.description').show();
    });
    $(document).keypress(function(event) {
        if (event.which === 111) {
            $('.current').children('.description').toggle();
        } else if (event.which === 110) {
            var currentArticle = $('.current');
            var nextArticle = currentArticle.next();
            currentArticle.removeClass('current');
            nextArticle.addClass('current');
        }
    });
};

$(document).ready(main);


var main = function() {
    $('.btn').click(function() {
        var post = $('.status-box').val();
        $('<li></li>').text(post).prependTo('.posts');
        $('.status-box').val('');
        $('.counter').text(140);
        $('.btn').addClass('disabled');
    });
    $('.status-box').keyup(function() {
        var postLength = $(this).val().length;
        var charactersLeft = 140 - postLength;
        $('.counter').text(charactersLeft);
        if (charactersLeft < 0) {
            $('.btn').addClass('disabled');
        } else if (charactersLeft === 140) {
            $('.btn').addClass('disabled');
        } else {
            $('.btn').removeClass('disabled');
        }
    });
    $('.btn').addClass('disabled');
};

$(document).ready(main);


var main = function() {
    $('.dropdown-toggle').click(function() {
        $('.dropdown-menu').toggle();
    });
    $('.arrow-next').click(function() {
        var currentSlide = $('.active-slide');
        var nextSlide = $('.active-slide').next();
        if (nextSlide.length === 0) {
            nextSlide = $('.slide').first();
        }
        currentSlide.fadeOut(600).removeClass('active-slide');
        nextSlide.fadeIn(600).addClass('active-slide');
        
        var currentDot = $('.active-dot');
        var nextDot = $('.active-dot').next();
        if (nextDot.length === 0) {
            nextDot = $('.dot').first();
        }
        currentDot.removeClass('active-dot');
        nextDot.addClass('active-dot');
    });
    $('.arrow-prev').click(function() {
        var currentSlide = $('.active-slide');
        var prevSlide = $('.active-slide').prev();
        if (prevSlide.length === 0) {
            prevSlide = $('.slide').last();
        }
        currentSlide.fadeOut(600).removeClass('active-slide');
        prevSlide.fadeIn(600).addClass('active-slide');
        
        var currentDot = $('.active-dot');
        var prevDot = $('.active-dot').prev();
        if (prevDot.length === 0) {
            prevDot = $('.dot').last();
        }
        currentDot.removeClass('active-dot');
        prevDot.addClass('active-dot');
    });
};

$(document).ready(main);

jQuery.ajax({
    url: ajaxUrl,
    method: 'GET',
    cache: true,
    data: {term: someterm},
    success: function (data, status, xhr) {},
    error: function (xhr, status, error) {},
    complete: function(xhr, status) {}
});
