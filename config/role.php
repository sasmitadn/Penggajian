<?php

return [
    'Dashboard' => [
        'create' => [],
        'read' => ['admin.dashboard'],
        'update' => [],
        'delete' => []
    ],
    'Dashboard Saya' => [
        'create' => [],
        'read' => ['user.dashboard'],
        'update' => [],
        'delete' => []
    ],
    'Absensi Saya' => [
        'create' => [],
        'read' => ['user.attendances.index'],
        'update' => [],
        'delete' => []
    ],
    'Absensi' => [
        'create' => ['admin.attendances.store'],
        'read' => ['admin.attendances.index'],
        'update' => ['admin.attendances.update'],
        'delete' => []
    ],
    'Insight & Analitik' => [
        'create' => [],
        'read' => ['admin.analytics.attendance', 'admin.analytics.salary'],
        'update' => [],
        'delete' => []
    ],
    'Laporan' => [
        'create' => [],
        'read' => [
            'admin.reports.attendance',
            'admin.reports.attendance.detail',
            'admin.reports.attendance.export',
            'admin.reports.attendance.export.detail',
            'admin.reports.salary',
            'admin.reports.salary.export'
        ],
        'update' => [],
        'delete' => []
    ],
    'Gaji Saya' => [
        'create' => [],
        'read' => ['user.salary', 'user.salary.receipt'],
        'update' => [],
        'delete' => []
    ],
    'Ajukan Pinjaman' => [
        'create' => ['user.cash_advances.create', 'user.cash_advances.store'],
        'read' => ['user.cash_advances.index', 'user.cash_advances.show', 'user.cash_advances.export.receipt'],
        'update' => [],
        'delete' => ['user.cash_advances.delete']
    ],
    'Pinjaman Karyawan' => [
        'create' => ['admin.cash_advances.create', 'admin.cash_advances.store'],
        'read' => ['admin.cash_advances.index', 'admin.cash_advances.show', 'admin.cash_advance.export.receipt', 'admin.cash_advance.export'],
        'update' => ['admin.cash_advances.edit', 'admin.cash_advances.update', 'admin.cash_advances.update.detail'],
        'delete' => ['admin.cash_advances.delete']
    ],
    'Daftar Gaji' => [
        'create' => ['admin.payrolls.create', 'admin.payrolls.store'],
        'read' => ['admin.payrolls.index', 'admin.payrolls.detail', 'admin.payrolls.detail.receipt'],
        'update' => [],
        'delete' => ['admin.payrolls.delete']
    ],
    'Daftar User' => [
        'create' => ['admin.users.create', 'admin.users.store', 'admin.users.import', 'admin.users.import.example'],
        'read' => ['admin.users.index'],
        'update' => ['admin.users.edit', 'admin.users.update'],
        'delete' => ['admin.users.delete']
    ],
    'Jabatan' => [
        'create' => ['admin.job_category.create', 'admin.job_category.store'],
        'read' => ['admin.job_category.index'],
        'update' => ['admin.job_category.edit', 'admin.job_category.update'],
        'delete' => ['admin.job_category.delete']
    ]
];
