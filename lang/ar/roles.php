<?php

return [

  // صفحة قائمة الأدوار
  'index' => [
    'title'      => 'الأدوار',
    'heading'    => 'إدارة الأدوار',
    'subheading' => 'عرض وإدارة جميع الأدوار في لوحة التحكم.',

    'create_button' => 'إضافة دور جديد',

    'filters' => [
      'search_label'       => 'بحث',
      'search_placeholder' => 'ابحث باسم الدور...',
      'submit'             => 'تطبيق الفلتر',
      'reset'              => 'إعادة تعيين',
    ],

    'table' => [
      'id'                => 'ID',
      'name'              => 'اسم الدور',
      'permissions_count' => 'عدد الصلاحيات',
      'created_at'        => 'تاريخ الإنشاء',
      'actions'           => 'الإجراءات',
      'empty'             => 'لا توجد أدوار حالياً.',
    ],

    'actions' => [
      'edit'           => 'تعديل',
      'delete'         => 'حذف',
      'confirm_delete' => 'هل أنت متأكد أنك تريد حذف هذا الدور؟',
    ],
  ],

  // صفحة إنشاء دور جديد
  'create' => [
    'title'        => 'إنشاء دور جديد',
    'heading'      => 'إضافة دور جديد',
    'subheading'   => 'قم بإنشاء دور جديد وتحديد الصلاحيات الخاصة به.',
    'back_to_list' => 'رجوع إلى قائمة الأدوار',

    'form' => [
      'name_label'       => 'اسم الدور (بالنظام) *',
      'name_placeholder' => 'مثال: manage_users, manage_orders',
      'name_help'        => 'هذا الاسم سيُستخدم لتحديد هذا الدور في لوحة التحكم.',
      'permissions'      => 'الصلاحيات',
      'permissions_help' => 'اختر الصلاحيات التي تريد ربطها بهذا الدور.',
      'submit'           => 'حفظ الدور',
      'no_permissions'   => 'لا توجد صلاحيات.',
      'permissions_label' => 'الصلاحيات',

    ],
  ],

  // صفحة تعديل دور
  'edit' => [
    'title'        => 'تعديل الدور',
    'heading'      => 'تعديل الدور',
    'subheading'   => 'قم بتعديل اسم الدور والصلاحيات المرتبطة به.',
    'back_to_list' => 'رجوع إلى قائمة الأدوار',

    'form' => [
      'name_label'       => 'اسم الدور (بالنظام) *',
      'name_placeholder' => 'مثال: manage_users, manage_orders',
      'name_help'        => 'هذا الاسم سيُستخدم لتحديد هذا الدور في لوحة التحكم.',
      'permissions'      => 'الصلاحيات',
      'permissions_help' => 'حدّث الصلاحيات المرتبطة بهذا الدور.',
      'submit'           => 'تحديث الدور',
    ],
  ],

  // رسائل فلاش / حالة العمليات
  'messages' => [
    'created'       => 'تم إنشاء الدور بنجاح.',
    'updated'       => 'تم تحديث الدور بنجاح.',
    'deleted'       => 'تم حذف الدور بنجاح.',
    'not_deletable' => 'لا يمكن حذف هذا الدور.',
  ],
];
