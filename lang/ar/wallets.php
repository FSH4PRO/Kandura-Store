<?php

return [
  'index' => [
    'title'       => 'المحافظ',
    'heading'     => 'إدارة المحافظ',
    'subheading'  => 'إدارة محافظ العملاء والأرصدة.',
    'bulk_topup'  => 'شحن جماعي',
  ],

  'show' => [
    'title'                => 'المحفظة :id',
    'heading'              => 'المحفظة',
    'subheading'           => 'تفاصيل المحفظة وسجل المعاملات.',
    'back'                 => 'العودة إلى المحافظ',
    'wallet_info'          => 'معلومات المحفظة',
    'status_active'        => 'نشط',
    'status_inactive'      => 'غير نشط',
    'customer'             => 'العميل',
    'balance'              => 'الرصيد',
    'created_at'           => 'تاريخ الإنشاء',
    'updated_at'           => 'تاريخ التحديث',
    'add_credit'           => 'إضافة رصيد',
    'deactivate'           => 'تعطيل',
    'activate'             => 'تفعيل',
    'transactions'         => 'المعاملات',
    'transactions_count'   => 'معاملة',
  ],

  'filters' => [
    'search_label'       => 'بحث',
    'search_placeholder' => 'البحث باسم العميل أو رقم الهاتف',
    'status_label'       => 'الحالة',
    'status_all'         => 'جميع الحالات',
    'status_active'      => 'نشط',
    'status_inactive'    => 'غير نشط',
    'balance_min'        => 'الحد الأدنى للرصيد',
    'balance_max'        => 'الحد الأقصى للرصيد',
    'submit'             => 'تصفية',
    'reset'              => 'إعادة تعيين',
  ],

  'table' => [
    'customer'        => 'العميل',
    'balance'          => 'الرصيد',
    'status'           => 'الحالة',
    'created_at'       => 'تاريخ الإنشاء',
    'actions'          => 'الإجراءات',
    'view'             => 'عرض',
    'add_credit'       => 'إضافة رصيد',
    'deactivate'       => 'تعطيل',
    'activate'         => 'تفعيل',
    'status_active'    => 'نشط',
    'status_inactive'  => 'غير نشط',
    'empty'            => 'لم يتم العثور على محافظ.',
  ],

  'transactions' => [
    'type'        => 'النوع',
    'amount'      => 'المبلغ',
    'description' => 'الوصف',
    'date'        => 'التاريخ',
    'credit'      => 'إضافة',
    'debit'       => 'خصم',
    'empty'       => 'لم يتم العثور على معاملات.',
  ],

  'modal' => [
    'topup_title'      => 'إضافة رصيد إلى المحفظة',
    'bulk_topup_title' => 'شحن جماعي لجميع المحافظ',
    'amount'           => 'المبلغ',
    'reference'        => 'المرجع',
    'note'             => 'ملاحظة',
    'cancel'           => 'إلغاء',
    'submit'           => 'إرسال',
    'bulk_warning'     => 'سيتم إضافة رصيد لجميع المحافظ النشطة. لا يمكن التراجع عن هذا الإجراء.',
  ],

  'messages' => [
    'topped_up'          => 'تم إضافة رصيد بقيمة :amount إلى المحفظة بنجاح.',
    'bulk_topped_up'     => 'تم إضافة رصيد بقيمة :amount إلى :count محفظة بنجاح.',
    'activated'          => 'تم تفعيل المحفظة بنجاح.',
    'deactivated'        => 'تم تعطيل المحفظة بنجاح.',
    'confirm_deactivate' => 'هل أنت متأكد من أنك تريد تعطيل هذه المحفظة؟ لن يتمكن العميل من استخدامها للدفع.',
  ],
];
