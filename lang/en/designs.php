<?php

return [

  // General
  'title'     => 'Designs',
  'heading'   => 'Designs',
  'subheading' => 'Manage and browse kandura designs.',

  'tabs' => [
    'my'      => 'My designs',
    'browse'  => 'Browse designs',
  ],

  // Filters
  'filters' => [
    'search_label'        => 'Search',
    'search_placeholder'  => 'Search by name or description...',
    'size_label'          => 'Size',
    'size_all'            => 'All sizes',
    'price_min'           => 'Min price',
    'price_max'           => 'Max price',
    'option_label'        => 'Design option',
    'option_all'          => 'All options',
    'creator_label'       => 'Designer',
    'creator_all'         => 'All designers',
    'mode_label'          => 'Mode',
    'mode_my'             => 'My designs',
    'mode_browse'         => 'Browse others',
    'per_page'            => 'Per page',
    'submit'              => 'Filter',
    'reset'               => 'Reset',
  ],

  // Table
  'table' => [
    'id'          => '#',
    'name'        => 'Name',
    'description' => 'Description',
    'price'       => 'Price',
    'sizes'       => 'Sizes',
    'options'     => 'Options',
    'creator'     => 'Designer',
    'created_at'  => 'Created at',
    'actions'     => 'Actions',
    'empty'       => 'No designs found.',
    'view'        => 'View details',
  ],

  // Status badges (لو احتجتها لاحقاً)
  'status_badge' => [
    'active'   => 'Active',
    'inactive' => 'Inactive',
  ],

  // Actions
  'actions' => [
    'show'            => 'View details',
    'edit'            => 'Edit',
    'delete'          => 'Delete',
    'confirm_delete'  => 'Are you sure you want to delete this design?',
    'no_actions'      => 'No actions available.',
    'create_button'   => 'Create new design',
  ],

  // Form (Create / Edit)
  'form' => [
    'create_title'     => 'Create new design',
    'edit_title'       => 'Edit design',
    'create_heading'   => 'New design',
    'edit_heading'     => 'Update design',

    'name_en'          => 'Name (English)',
    'name_en_placeholder' => 'Example: Classic Kandura',
    'name_ar'          => 'Name (Arabic)',
    'name_ar_placeholder' => 'مثال: كندورة كلاسيك',

    'description_en'   => 'Description (English)',
    'description_ar'   => 'Description (Arabic)',

    'price'            => 'Price',
    'price_placeholder' => 'Example: 250',

    'sizes'            => 'Available sizes',
    'sizes_help'       => 'Select at least one size.',

    'options'          => 'Design options',
    'options_help'     => 'You can choose multiple options (color, fabric, etc.).',

    'images'           => 'Images',
    'images_help'      => 'Upload at least one image. You can upload multiple images.',
    'images_add_more'  => 'Add more images',

    'submit_create'    => 'Create design',
    'submit_update'    => 'Update design',
    'back_to_list'     => 'Back to designs',
  ],

  // Messages
  'messages' => [
    'created' => 'Design created successfully.',
    'updated' => 'Design updated successfully.',
    'deleted' => 'Design deleted successfully.',
    'forbidden' => 'You are not allowed to manage this design.',
  ],

  'index' => [
    'heading'    => 'Designs',
    'subheading' => 'Manage and browse kandura designs.',
  ],

  'card' => [
    'created_at' => 'Created at',
    'price'      => 'Price',
    'sizes'      => 'Sizes',
    'options'    => 'Design options',
    'no_image'  => 'No images available for this design.',
    'options_count' => '{0} No options|{1} One option|[2,*] :count options',
  ],

  'show' => [
    'heading' => 'Design Details',
    'subheading' => 'View the full details of the design here.',
    'back_to_list' => 'Back to designs',
    'currency' => 'USD',
    'created_at' => 'Created at',
    'sizes' => 'Available Sizes',
    'options' => 'Design Options',
    'gallery' => 'Gallery',
    'description' => 'Description',
    'no_sizes' => 'No sizes specified for this design.',

    'customer_info' => [
      'title' => 'Designer Information',
      'type_customer' => 'Customer',
      'customer_id' => 'Customer ID',
      'joined_at' => 'Joined at',

    ],
    'meta' => [
      'title' => 'Design Metadata',
      'design_id'    => 'Design ID',
      'price'        => 'Price',
      'sizes_count' => 'Sizes Count',
      'options_count' => 'Options Count',

    ],

  ],

];
