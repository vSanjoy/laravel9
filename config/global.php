<?php
/*****************************************************/
# Purpose : for global contants
/*****************************************************/

return [
    'LOADER'                            => 'loader.gif',                            // 'loader.gif' Loading image
    'TABLE_LIST_LOADER'                 => 'loading.gif',                           // 'loading.gif' Loading image
    'NO_IMAGE'                          => 'no-image.png',                          // no image
    'USER_NO_IMAGE'                     => 'user-no-image.png',                     // user no image
    'LATEST_NEWS_NO_IMAGE'              => 'latest-news-no-image.jpg',              // latest news no image
    'POST_NO_IMAGE'                     => 'post-no-image.jpg',                     // post no image
    'ADMIN_IMAGE'                       => 'avatar5.png',                           // Admin image
    'EMAIL_REGEX'                       => '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',   // email regex
    'PASSWORD_REGEX'                    => '/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',   // password regex
    'VALID_AMOUNT_REGEX'                => '/^[0-9]\d*(\.\d+)?$/',                  // Amount regex
    'VALID_AMOUNT_REGEX_EXCEPT_ZERO'    => '/^[1-9]\d*(\.\d+)?$/',                  // Amount regex
    'VALID_ALPHA_NUMERIC'               => '/^[a-zA-Z0-9]+$/',                      // Alpha numeric regex
    'MAX_UPLOAD_IMAGE_SIZE'             => '5242880',                               // 1048576 => 1 mb, maximum upload size, used in javascript
    'IMAGE_MAX_UPLOAD_SIZE'             => 5120,                                    // Image upload max size (5mb) used in php
    'FILE_MAX_UPLOAD_SIZE'              => 3072,                                    // File upload max size (3mb) used in php
    'IMAGE_FILE_TYPES'                  => 'jpeg,jpg,png,svg,bmp,WebP',             // Allowed image file types
    'IMAGE_FILE_TYPES_ARRAY'            => ['jpeg,jpg,png,svg,bmp,WebP'],
    'IMAGE_CONTAINER'                   => 300,                                     // Image container for cropping
    'THUMB_IMAGE_WIDTH'                 => [
                                            'Category'          => 100,              // Category,Testimonial ... => This name should be same as Model name
                                            'Account'           => 100,
                                            'User'              => 215,
                                            'SubAdmin'          => 215,
                                            'Event'             => 373,
                                            'Advertiser'        => 215,
                                            'Agent'             => 215,
                                        ],
    'THUMB_IMAGE_HEIGHT'                => [
                                            'Category'          => 100,
                                            'Account'           => 100,
                                            'User'              => 215,
                                            'SubAdmin'          => 215,
                                            'Event'             => 328,
                                            'Advertiser'        => 215,
                                            'Agent'             => 215,
                                        ],
    'GALLERY_MAX_IMAGE_UPLOAD_AT_ONCE'  => 10,                                      // Number of images upload at once
    'GALLERY_MAX_IMAGE_UPLOAD_SIZE'     => 5120,                                    // Maximum upload images
    'WEBSITE_LANGUAGE'                  => ['en' => 'English', 'ar' => 'Arabic'],   // Website language
    'BOOKING_SHOW_PER_PAGE'             => 10,                                      // Number of booking show in booking history page
];
