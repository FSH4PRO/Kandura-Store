<?php

return [

    'title'       => 'المستخدمون - Kandura Store',
    'heading'     => 'المستخدمون',
    'subheading'  => 'إدارة مستخدمي النظام (Users / Admins / Super Admins)',

    'filters' => [
        'search_label'  => 'بحث',
        'search_placeholder' => 'الاسم، البريد، رقم الهاتف...',
        'status_label'  => 'الحالة',
        'status_all'    => 'الكل',
        'status_active' => 'فعّال',
        'status_inactive' => 'موقوف',
        'role_label'    => 'الدور',
        'submit'        => 'تصفية',
        'reset'         => 'تصفير',
    ],

    'table' => [
        'id'        => '#',
        'name'      => 'الاسم',
        'email'     => 'البريد الإلكتروني',
        'phone'     => 'رقم الهاتف',
        'role'      => 'الدور',
        'status'    => 'الحالة',
        'created_at'=> 'تاريخ الإنشاء',
        'actions'   => 'إجراءات',
        'empty'     => 'لا يوجد مستخدمون مطابِقون لخيارات البحث الحالية.',
    ],

    'status_badge' => [
        'active'   => 'فعّال',
        'inactive' => 'موقوف',
    ],

    'actions' => [
        'no_actions'  => 'لا توجد إجراءات متاحة',
        'delete'      => 'حذف المستخدم',
        'confirm_del' => 'هل أنت متأكد من حذف هذا المستخدم؟',
    ],

    'create_admin' => [
        'button'   => 'إضافة أدمن',
        'title'    => 'إضافة أدمن جديد',
        'subtitle' => 'إنشاء حساب مسؤول جديد للنظام.',
        'back'     => 'رجوع لقائمة المستخدمين',
        'success'  => 'تم إنشاء حساب الأدمن بنجاح.',
    ],

];
