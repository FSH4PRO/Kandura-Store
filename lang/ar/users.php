<?php

return [
    'title'      => 'المستخدمون',
    'heading'    => 'إدارة المستخدمين',
    'subheading' => 'يمكنك البحث وتصفيه المستخدمين من هنا.',

    'filters' => [
        'search_label'       => 'بحث',
        'search_placeholder' => 'ابحث بالاسم أو البريد أو الهاتف...',
        'status_label'       => 'الحالة',
        'status_all'         => 'كل الحالات',
        'status_active'      => 'نشط',
        'status_inactive'    => 'غير نشط',
        'role_label'         => 'الدور',
        'role_all'           => 'كل الأدوار',
        'submit'             => 'تصفية',
        'reset'              => 'إعادة تعيين',
    ],

    'table' => [
        'id'         => 'ID',
        'name'       => 'الاسم',
        'email'      => 'البريد',
        'phone'      => 'الهاتف',
        'role'       => 'الدور',
        'status'     => 'الحالة',
        'created_at' => 'تاريخ الإنشاء',
        'actions'    => 'إجراءات',
        'empty'      => 'لا يوجد مستخدمون حتى الآن.',
    ],

    'status_badge' => [
        'active'   => 'نشط',
        'inactive' => 'غير نشط',
    ],

    'roles' => [
        'super_admin'     => 'Super Admin',
        'manage_users'    => 'إدارة المستخدمين',
        'manage_admins'   => 'إدارة الأدمنز',
        'manage_orders'   => 'إدارة الطلبات',
        'manage_addresses' => 'إدارة العناوين',
        'user'            => 'مستخدم',
    ],

    'actions' => [
        'delete'         => 'حذف',
        'confirm_delete' => 'هل أنت متأكد من حذف هذا المستخدم؟',
        'no_actions'     => 'لا توجد إجراءات متاحة',
    ],
];
