/**
 * [ADMIN] Slug
 * 
 * スラッグ用のJS処理
 */
$(function(){
	$('#SlugCheckNameResult').hide();
	var $SlugId = $("#SlugId").val();
	var $BlogPostBlogContentId = $("#BlogPostBlogContentId").val();

	// keyupイベント
	$("#SlugName").keyup(slugNameValueChengeHandler);
	//$("#SlugName").change(slugNameValueChengeHandler);

	// スラッグを探索し、重複があればメッセージを表示する
	function slugNameValueChengeHandler() {
		var options = {};
		options = {
			"data[Slug][id]": $SlugId,
			"data[Slug][name]": $("#SlugName").val(),
			"data[Slug][blog_content_id]": $BlogPostBlogContentId
		};
		$.ajax({
			type: "POST",
			data: options,
			url: $("#AjaxSlugCheckNameUrl").html(),
			dataType: "html",
			cache: false,
			success: function(result, status, xhr) {
				if(status === 'success') {
					if(!result) {
						$('#SlugCheckNameResult').show('fast');
					} else {
						$('#SlugCheckNameResult').hide('fast');
					}
				}
			}
		});
	}
});
