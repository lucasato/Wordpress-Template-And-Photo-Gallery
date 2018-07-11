jQuery(document).ready(function(jQuery)
{
    jQuery('#btn_new_gallery').click(function(e)
    {
        //Modal click
        jQuery('#'+jQuery(this).attr('data-id-modal')).dialog({
            title: globalGalleryTranslate['gallery_new'],
            dialogClass: 'wp-dialog',
            autoOpen: false,
            draggable: false,
            width: 350,
            modal: true,
            resizable: false,
            closeOnEscape: true,
            position: {
                my: "center",
                at: "center",
                of: window
            },
            open: function () {
                // close dialog by clicking the overlay behind it
                jQuery('.ui-widget-overlay').bind('click', function(){
                    jQuery('#my-dialog').dialog('close');
                })
            },
            create: function () {
                // style fix for WordPress admin
                jQuery('.ui-dialog-titlebar-close').addClass('ui-button');
            },
        });

        e.preventDefault();
        jQuery('#my-dialog').dialog('open');
    });
});

function ajax(data, callback)
{
    jQuery.LoadingOverlay("show");
    jQuery.ajax({
        url: ajax_url,
        method: 'POST',
        dataType: 'json',
        data: data
    }).done(function(resp) {
        jQuery.LoadingOverlay("hide");
        if (resp.status  ==  true) {
            eval(callback);
        } else if (resp.status == false) {
            alert(resp.msj);
        } else {
            alert('Unknowed error: '+resp);
        }
    });
}

// Set featured image
function featuredGalleryPhoto(id, id_attachment)
{
    var data = {
        action: 'featuredFileGallery',
        id_post: id,
        id_attachment: id_attachment
    }
    ajax(data, "jQuery('#featuredPhoto').attr('src',  jQuery('#photo_"+id_attachment+" img').attr('src'))");
}

//Create gallery
function createGallery()
{
    if (jQuery('#galleryName').val() == '') {
        jQuery('#galleryAnswer').val(globalGalleryTranslate['gallery_validate_name']);
        return false;
    }
    var data = {
        action: 'createGallery',
        galleryName: jQuery('#galleryName').val(),
        galleryPassword: jQuery('#galleryPassword').val()
    };
    ajax(data, "window.location.href = resp.redirect");
}

function deleteGallery(id)
{
    jQuery('#modal-window-delete').html(
        '<p>'+globalGalleryTranslate['gallery_delete_sure']+'.</p>\
        <a href="admin.php?page=photo-gallery&delete='+id+'" class="button button-primary button-hero">'+globalGalleryTranslate['gallery_delete_confirm']+'</a>'
    )
}

function deletePhoto(id)
{
    var data = {
        action: 'deleteFileGallery',
        id_post: id
    };
    var callback = "jQuery('#photo_"+id+"').slideUp('slow', function(){  jQuery(this).remove();  })";
    ajax(data, callback);
}

// TODO 07/07
function updateGallery(id)
{
    var name = jQuery('#edit-gallery-post-name');
    var password = jQuery('#edit-gallery-post-password');
    if (name.val() == '') {
        name.focus();
        return false;
    }

    var data = {
        action: 'updateGallery',
        id_post: id,
        galleryName: name.val(),
        galleryPassword: password.val(),
    };

    var callback = "jQuery('#photo_"+id+"').slideUp('slow', function(){  jQuery(this).remove();  })";
    ajax(data, callback);
}

function showUploader(id)
{
      jQuery('.custom_file_gallery_upload').html(globalGalleryTranslate['gallery_upload_title']);
      jQuery('.custom_file_gallery_upload').uploadFile({
          url:ajax_url,
          fileName:"file",
          multiple:true,
          autoSubmit:true,
          type: 'JSON',
          contentType: "application/json; charset=utf-8",
          traditional: true,
          uploadButtonClass:"submit",
          allowedTypes:"jpeg,jpg,png,gif",
          formData:{'action':'processFileGallery','id_post':id},
          onError: function(files,status,errMsg,pd)
          {
              alert('ERROR: CONNECTION LOST');
          },
          onSuccess:function(files,resp,xhr)
          {
              if (resp.status == true) {
                  jQuery('#featuredPhoto').attr('src', resp.thumbnail);
                  jQuery('#galleryList').prepend('\
                    <article id="photo_'+resp.id_attachment+'">\
                      <img src="'+resp.thumbnail+'" class="img-fluid" />\
                      <p>\
                      <input type="button" onclick="featuredGalleryPhoto('+resp.id_post+','+resp.id_attachment+')" class="button" value="'+globalGalleryTranslate['gallery_btn_featured']+'" />\
                      <input type="button" onclick="deletePhoto('+resp.id_attachment+')" class="button-primary delete" value="'+globalGalleryTranslate['gallery_btn_remove']+'" />\
                      </p>\
                    </article>\
                  ');
              } else {
                alert('ERROR: '+resp);
              }
          }
      });
  }
