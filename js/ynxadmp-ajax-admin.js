jQuery(document).ready(function($) {

	$('#btn_ynxadmp_create_posts').click(function(event){
		
		event.preventDefault();
		
		var titlesArea = $('#ynxadmp_titles_texarea').val();
		
		if ( titlesArea !== '' ) {
		
		var linesTitles = $('textarea[name=ynxadmp_titles_texarea]').val().split('\n');
		linesCount = linesTitles.length;
		var totalCount = 1;
		var countUpTimer;
		var countUp_number = -1;
		var deferred = $.Deferred();
		var promise = deferred.promise();
		
		$("#ynxadmp_progressbar").progressbar();
		$("#ynxadmp_progressbar").css({'background': 'rgb(255, 255, 255)','border-radius': '4px'});
		$("#ynxadmp_progressbar > div").css({'background': 'rgb(41, 133, 189)','height': '26px','border-radius': '4px'});

		function ynxadmp_inProgress() {
			$("#ynxadmp_progressbar").progressbar({
				value: countUp_number,
				max: linesCount
			});
			$("#ynxadmp_progressbar > span").html(YnxadmpAdminAjax.processingText + " " + countUp_number + " " + YnxadmpAdminAjax.postsCreatedText).css({'position':'absolute','color':'rgb(225, 243, 255)','font-weight':'600','text-align':'center','text-shadow':'2px 1px 5px #000','margin':'3px 0 0 2%'});
		}

		function ynxadmpCountUp() {
			if (countUp_number < linesCount) {
				countUp_number += totalCount;
				deferred.notify();
			} else {
				deferred.resolve();
			}
		}
		
		promise.progress(ynxadmp_inProgress);
		ynxadmpCountUp();
		
		$.each(linesTitles, function(indexInArray) {
			
			var dataYnxToCreatePosts = {
				action			: 'addposts_ajaxfunc',
				sendlocNonce	: YnxadmpAdminAjax.ynxadmpNonce,
				typeEntry		: $('#ynxadmp_post_type').val(),
				ynxadmpTitle	: this,
				ynxadmpContent	: tinyMCE.get('ynxadmp_content_texarea').getContent(),
				ynxadmpCategory	: $('#cat[name=cat]').val(),
				ynxadmpParent	: $('#page_id[name=page_id]').val(),
				statusEntry		: $('#ynxadmp_post_status').val(),
				ynxadmpAuthor	: $('select[name=author_id] option:selected').val()
			};
		
			setTimeout(function(){
				$.post(YnxadmpAdminAjax.adminynxadmpajax, dataYnxToCreatePosts, function(response) {
					ynxadmpCountUp();					
				});
			}, indexInArray * 300);
			
		});
		
		} else {
			$('#msg_notitles').slideDown(500).delay(2000).slideUp(500);
		}
		
	});
	
	ynxadmp_pageopts = $('.ynxadmp_page-options').remove();
	ynxadmp_postopts = $('.ynxadmp_post-options').remove();
	if ($('#ynxadmp_post_type').val() == 'ynxadmp_type_post') {
		$('#ynxadmp_titles_section').after(ynxadmp_postopts);
	} else if ($('#ynxadmp_post_type').val() == 'ynxadmp_type_page') {
		$('#ynxadmp_titles_section').after(ynxadmp_pageopts);
	}
	
	$('#ynxadmp_post_type').change( function() {
		if ($(this).val() == 'ynxadmp_type_post') {
			$('.ynxadmp_page-options').remove();
			$('#ynxadmp_titles_section').after(ynxadmp_postopts);
		} else if ($(this).val() == 'ynxadmp_type_page') {
			$('.ynxadmp_post-options').remove();
			$('#ynxadmp_titles_section').after(ynxadmp_pageopts);
		}
	});
	
	$('#ynxadmp_progressbar').on('progressbarcomplete', function() {
		$('#btn_ynxadmp_create_posts').fadeOut(1000);
		$('#msg_finished').html('<p>' + YnxadmpAdminAjax.processFinishText + ' <b>' + linesCount + '</b> ' + YnxadmpAdminAjax.postsCreatedText + '.</p>').slideDown('slow').delay(5000).slideUp('slow');
		setTimeout(function(){
			$('#ynxadmp_progressbar > span').html(YnxadmpAdminAjax.finishedWithText + ' ' + linesCount + ' ' + YnxadmpAdminAjax.postsCreatedText).css({'position':'absolute','color':'rgb(225, 243, 255)','font-weight':'600','text-align':'center','text-shadow':'2px 1px 5px #000','margin':'3px 0 0 2%'});
		}, 500);
	});

/* ===  Yonox Add Multiple Posts RESET FORM  === */	
	$('#btn_ynxadmp_reset_form').click(function(event){
		event.preventDefault();	
		window.location.reload(true);
	});
		
});