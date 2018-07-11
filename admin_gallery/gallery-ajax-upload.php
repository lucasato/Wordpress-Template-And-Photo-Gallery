<?php

add_action('wp_ajax_processFileGallery', 'processFileGallery');
add_action('wp_ajax_createGallery', 'createGallery');
add_action('wp_ajax_deleteFileGallery', 'deleteFileGallery');
add_action('wp_ajax_updateGallery', 'updateGallery');
add_action('wp_ajax_featuredFileGallery', 'setFeaturedImage');

require_once get_template_directory().'/admin_gallery/Class/DirectoryClass.php';
require_once get_template_directory().'/admin_gallery/Class/FileUpload.php';
require_once get_template_directory().'/admin_gallery/Class/Response.php';
require_once get_template_directory().'/admin_gallery/Class/Gallery.php';

function processFileGallery()
{
    $galleryClass = new Gallery();
    $postId = isset($_POST['id_post']) ? $_POST['id_post'] : null;
    $galleryClass->upload($postId);
}

function deleteFileGallery()
{
  $galleryClass = new Gallery();
  $postId = isset($_POST['id_post']) ? $_POST['id_post'] : null;
    $galleryClass->delete($postId);
}

function updateGallery()
{
  $galleryClass = new Gallery();
  $postId = isset($_POST['id_post']) ? $_POST['id_post'] : null;
    $galleryClass->update($postId);
}


function createGallery()
{
    $galleryClass = new Gallery();
    $galleryName      = $_POST['galleryName'];
    $galleryPassword  = $_POST['galleryPassword'];

    $galleryClass->create($galleryName, $galleryPassword);
}

function setFeaturedImage()
{
    $galleryClass = new Gallery();
    $postId = isset($_POST['id_post']) ? $_POST['id_post'] : null;
    $attachmentId  = $_POST['id_attachment'];
    $galleryClass->featured($postId, $attachmentId);
}
