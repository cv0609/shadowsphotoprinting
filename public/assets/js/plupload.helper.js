jQuery(document).ready(function() {
	var ww = jQuery(window).width();
	jQuery('.fcsg-wrap .fcsg-approve').click(function(){
		var gname = jQuery('.fcsg-wrap .fcsg-gname').val();
		if (gname != '') {
			fupload_folder = fupload_get_folder();
			jQuery('.fcsg-wrap .gname-row').slideUp(500);
			jQuery('.fcsg-wrap .fupload-area').slideDown(500);
		}
		return false;
	});
	jQuery('.fcsg-wrap .fcsg-form').submit(function(){
		if (fuploaded) {
			jQuery('.fupload-process .fcsg-process-total').html(fuploaded_files.length);
			fupload_process(0);
		}
		return false;
	});
	jQuery('input.fcsg-upload-new-image').click(function(){
		jQuery('#fupload-fileslist').html('').hide();
		jQuery('#uploadfiles').hide();
		jQuery('.fupload-process').hide();
		jQuery('.fupload-loading').css('visibility', 'hidden');

		jQuery.colorbox({inline:true, href:"#fcsg-upload-images", maxWidth: '90%'});
	});
	jQuery('input.fcsg-select-from-gallery').click(function(){
		jQuery.colorbox({inline:true, href:"#fcsg-galleries-images", maxWidth: '90%'});
	});
	jQuery('.fcsg-galleries-images-popup ul li a').click(function(){
		var attach_id = jQuery(this).attr('href').replace('#', '');
		fupload_fie_process(attach_id);
		return false;
	});
	jQuery('.fcsg-gallery-links small a').click(function(){
		var gpid = jQuery(this).data('gpid');
		if (jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' span').is(':visible')) {
			jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' span').hide();
		} else {
			jQuery('.fcsg-gallery-links .fcsg-link span').hide();
			jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' span').fadeIn(200);
		}
		return false;
	});
	jQuery('.fcsg-gallery-links span .r-gname-save').click(function(){
		var gpid = jQuery(this).data('gpid');
		var gname = jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' .r-gname').val();
		var rerror = jQuery('.fcsg-gallery-links').data('error');
		var js_siteurl = jQuery('.fcsg-gallery-links').data('siteurl');
		if (gname != '') {
			jQuery('.fcsg-gallery-links-wrap .fcsg-glinks-loading').show();
			jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' .r-loading').show();
			jQuery.post(
				js_siteurl,
				{
					fcsg_ajax_action: 'gallery-rename',
					gallery_gpid: gpid,
					gallery_name: gname
				},
				function(data) {
					jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' .a-link').html(data);
					jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' .r-gname').val(data);
					jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' span').hide();
					jQuery('.fcsg-gallery-links-wrap .fcsg-glinks-loading').hide();
					jQuery('.fcsg-gallery-links .fcsg-link-'+gpid+' .r-loading').hide();
				}
			);
		} else {
			alert(rerror);
		}
		return false;
	});
	if (jQuery('.envira-gallery-wrap').length && jQuery('.fw-area').length) {
		var gpw = jQuery('.envira-gallery-wrap').parent().width();
		var spwd = (ww - gpw) / 2;
		var gwidth = ww - 400 - spwd;
		if (jQuery('.fw-area').css('position') == 'fixed') {
			jQuery('.envira-gallery-wrap').parent().width(gwidth);
		}
	}
});

var fcsg_attaches = [];
function fupload_process(fnum) {
	var js_siteurl = jQuery('.fcsg-form').attr('action');
	var process_num = fnum + 1;
	var file_path = fuploaded_files[fnum];
	var fparts = file_path.split('/');
	var ofname = fparts[fparts.length-1];

	jQuery('.fupload-process .fcsg-process-num').html(process_num);
	jQuery('.fupload-process .fcsg-process-name').html(ofname);
	jQuery('.fupload-process').fadeIn();

	jQuery.post(
		js_siteurl,
		{
			fcsg_ajax_action: 'create-attachment',
			file_path: file_path
		},
		function(data) {
			if (data == 'ERROR') {
				alert('PROCESS ERROR');
			} else {
				fcsg_attaches[fcsg_attaches.length] = data;
				if (process_num < fuploaded_files.length) {
					fupload_process(process_num);
				} else {
					jQuery('.fupload-process').hide();
					fupload_complete();
				}
			}
		}
	);
}

function fupload_complete() {
	var js_siteurl = jQuery('.fcsg-form').attr('action');
	var is_single = jQuery('.fcsg-form').data('issingle');
	jQuery.post(
		js_siteurl,
		{
			fcsg_ajax_action: 'gallery-submit',
			gallery_name: jQuery('.fcsg-wrap .fcsg-gname').val(),
			gallery_files: fuploaded_files.join(';'),
			gallery_attaches: fcsg_attaches.join(';'),
			issingle: is_single
		},
		function(data) {
			//jQuery('.fupload-loading').css('visibility', 'hidden');
			if (data == 'PROCESS ERROR') {
			} else {
				if (is_single) {
					fupload_fie_process(data);
				} else {
					window.location.href = data;
				}
			}
		}
	);
}

function fupload_fie_process(attach_id) {
	var fie_use = parseInt(jQuery('.fcsg-form').data('fie_use'));
	fie_quantity = jQuery('form.cart input.qty').val();
	fie_product_id = jQuery('.fcsg-form input.product-id').val();
	fie_variation_id = 0;
	fie_cpage = fie_product_id;
	fie_gallery_item_id = attach_id;
	jQuery('form.cart input.fcsg-attached-file').val(attach_id);
	jQuery('.fie-popup .fie-use-asis').addClass('fcsg-useasis');
	fie_process_image_editor(fie_use);
}

function fupload_get_folder() {
	var gname = jQuery('.fcsg-wrap .fcsg-gname').val();
	if (fupload_s3path != '') {
		return fupload_s3path+'/raw/'+fupload_sanitize(gname)+'-'+fupload_get_date();
	} else {
		return 'raw/'+fupload_sanitize(gname)+'-'+fupload_get_date();
	}
}
function fupload_get_date() {
	var dnow = new Date();
	var y = dnow.getFullYear();
	var m = dnow.getMonth() + 1;
	var d = dnow.getDate();
	var h = dnow.getHours();
	var i = dnow.getMinutes();
	var s = dnow.getSeconds();
	return y+'-'+fdate(m)+'-'+fdate(d)+'-'+fdate(h)+'-'+fdate(i)+'-'+fdate(s);
}
function fdate(d) {
	d = parseInt(d); if (d < 10) { d = '0' + d; }
	return d;
}
function fupload_sanitize(name) {
	name = name.toLowerCase();
	name = utf2ascii(name);
	name = name.replace(/\s+/g, '-');
	name = fupload_transliterate(name);
	name = name.replace(/[^a-z0-9\.]+/gi,'-');
	if (name == '' || name == '-') {
		name = Date.now();
	} else if (name.substring(0,1) == '-' || name.substring(0,1) == '.') {
		name = Date.now()+name;
	}
	return name;
}
function utf2ascii(str) {
	var asciistr = '';
	for(var i = 0; i < str.length; i++){
		var ascii = unicodearray[str[i]];
		if (ascii != undefined) {
			asciistr += ascii;
		} else {
			asciistr += str[i];
		}
	}
	return asciistr;
}

var fupload_transliterate = function(text) {
    text = text
        .replace(/\u042A/g, '-')
        .replace(/\u0451/g, 'yo')
        .replace(/\u0439/g, 'i')
        .replace(/\u0446/g, 'ts')
        .replace(/\u0443/g, 'u')
        .replace(/\u043A/g, 'k')
        .replace(/\u0435/g, 'e')
        .replace(/\u043D/g, 'n')
        .replace(/\u0433/g, 'g')
        .replace(/\u0448/g, 'sh')
        .replace(/\u0449/g, 'sch')
        .replace(/\u0437/g, 'z')
        .replace(/\u0445/g, 'h')
        .replace(/\u044A/g, '-')
        .replace(/\u0410/g, 'a')
        .replace(/\u0444/g, 'f')
        .replace(/\u044B/g, 'i')
        .replace(/\u0432/g, 'v')
        .replace(/\u0430/g, 'a')
        .replace(/\u043F/g, 'p')
        .replace(/\u0440/g, 'r')
        .replace(/\u043E/g, 'o')
        .replace(/\u043B/g, 'l')
        .replace(/\u0434/g, 'd')
        .replace(/\u0436/g, 'zh')
        .replace(/\u044D/g, 'e')
        .replace(/\u042C/g, '-')
        .replace(/\u044F/g, 'ya')
        .replace(/\u0447/g, 'ch')
        .replace(/\u0441/g, 's')
        .replace(/\u043C/g, 'm')
        .replace(/\u0438/g, 'i')
        .replace(/\u0442/g, 't')
        .replace(/\u044C/g, '-')
        .replace(/\u0431/g, 'b')
        .replace(/\u044E/g, 'yu');

    return text;
};