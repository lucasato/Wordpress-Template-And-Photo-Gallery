jQuery(function($)
{
  $('.gallery_file_upload').uploadFile({
				url:ajax_url,
				fileName:"file",
				multiple:false,
				autoSubmit:true, //SOLO TRUE PARA EL COVER
				uploadButtonClass:"submit",
				allowedTypes:"jpg,jpeg,gif,png",
				formData:{'action':'processFileGallery',id_post: $('#id-post').val()},
				onError: function(files,status,errMsg,pd)
				{
					alert('ERROR: CONNECTION LOST');
				},
				onSuccess:function(files,resp,xhr)
				{
          var obj = jQuery.parseJSON(resp);
					if(obj.status == 'true'){
            $('#preview-shortcode').val('[video width="606" height="348" mp4="'+obj.preview+'"][/video]');
            $('#purchase-shortcode').val('[purchase_link id="'+obj.id_edd+'" style="button" color="" text="Añadir al Carro"]');
					}else{
						alert('ERROR: '+resp);
					}
				}
		});


    $('.edd_custom_file_delete').click(function()
    {
      var id = parseInt($(this).attr('data-id'));

      if(id === parseInt(id, 10))
      {
        $.ajax({
          url: ajax_url,
          method: 'post',
          data: { action: 'deleteFile', id: id }
        }).done(function(resp)
        {
          if( resp  ==  'file_deleted')
          {
            alert('borrado');
            $('#edd_row_'+id).hide();
          }else if( resp  ==  'file_error')
          {
            alert('error, no se pudo borrar');
          }else{
            alert('no se pudo eliminar el post');
          }
        });
      }else{
        alert('Wrong POST ID');
      }
    });


});
