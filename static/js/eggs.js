var rtime = 8000;

function stripslashes (str) {
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Ates Goral (http://magnetiq.com)
    // +      fixed by: Mick@el
    // +   improved by: marrtins
    // +   bugfixed by: Onno Marsman
    // +   improved by: rezna
    // +   input by: Rick Waldron
    // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +   input by: Brant Messenger (http://www.brantmessenger.com/)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: stripslashes('Kevin\'s code');
    // *     returns 1: "Kevin's code"
    // *     example 2: stripslashes('Kevin\\\'s code');
    // *     returns 2: "Kevin\'s code"
    return (str + '').replace(/\\(.?)/g, function (s, n1) {
        switch (n1) {
        case '\\':
            return '\\';
        case '0':
            return '\u0000';
        case '':
            return '';
        default:
            return n1;
        }
    });
}

$(document).ready(function(){
	rotateTests();
	rotateImages();
	readyContactForm();
});


function rotateTests(){
	var i = 1;
	var test = $('.testimonials-carousel .testimonial').eq(0).fadeIn();
	test.delay(rtime-1000).fadeOut();
	setInterval(function(){
		var test = $('.testimonials-carousel .testimonial').eq(i);
		test.fadeIn();
		test.delay(rtime - 1000).fadeOut();
		i++;
		if (i >= $('.testimonials-carousel .testimonial').length){
			i = 0;
		}	
	}, rtime);
}

function readyContactForm(){
	$('#send-comment').click(function(e){
		e.preventDefault();
		var form = $(this).closest('form');
		var path = form.attr('action');
		var email = $('#email').val();
		var name = $('#name').val();
		var comment = $('#comment').val();
		if (!comment.length){
			alert("Don't forget to enter a message...");
			return;
		}
		if (!email.length){
			alert("Don't forget to enter your email address so I can respond");
			return;
		}
		var subject = $('#subject').val();
		$.post(path, {email:email, name:name, comment:comment, subject:subject});
		form.fadeOut(500, function(){
			$('#contact-thanks').fadeIn();
		});
	});
}

function decode(str) {
     return unescape(str.replace(/\+/g, " "));
}

function rotateImages(){
	$('.cycler').cycle({ 
		fx:     'fade', 
		speed:   300, 
		timeout: 3000, 
		next:   '.cycler', 
		pause:   1,
		after:	function(){
			var cap = unescape(this.alt);
			cap = cap.replace('"\"', '"');
			cap = cap.replace('\"', '"');
			cap = cap.replace('/>', '">');
			$('#gallery-caption').html(stripslashes(cap));
		}
	});
}