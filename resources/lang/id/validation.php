<?php

return [
    'accepted'             => 'Isian :attribute harus diterima.',
    'active_url'           => 'Isian :attribute bukan URL yang valid.',
    'after'                => 'Isian :attribute harus tanggal setelah :date.',
    'after_or_equal'       => 'Isian :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha'                => 'Isian :attribute hanya boleh berisi huruf.',
    'alpha_dash'           => 'Isian :attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => 'Isian :attribute hanya boleh berisi huruf dan angka.',
    'array'                => 'Isian :attribute harus berupa array.',
    'before'               => 'Isian :attribute harus tanggal sebelum :date.',
    'before_or_equal'      => 'Isian :attribute harus tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'Isian :attribute harus antara :min dan :max.',
        'file'    => 'Ukuran file :attribute harus antara :min dan :max kilobytes.',
        'string'  => 'Jumlah karakter :attribute harus antara :min dan :max.',
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'boolean'              => 'Isian :attribute harus true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'email'                => 'Isian :attribute harus berupa alamat email yang valid.',
    'required'             => 'Isian :attribute wajib diisi.',
    'same'                 => 'Isian :attribute dan :other harus cocok.',
    'min'                  => [
        'numeric' => ':attribute minimal :min.',
        'file'    => 'Ukuran file :attribute minimal :min kilobytes.',
        'string'  => 'Jumlah karakter :attribute minimal :min.',
        'array'   => ':attribute harus memiliki minimal :min item.',
    ],
    'max'                  => [
        'numeric' => ':attribute maksimal :max.',
        'file'    => 'Ukuran file :attribute maksimal :max kilobytes.',
        'string'  => 'Jumlah karakter :attribute maksimal :max.',
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
    ],
    'unique'               => ':attribute sudah digunakan.',
    'exists'               => ':attribute tidak ditemukan.',
    'string'               => ':attribute harus berupa teks.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'numeric'              => ':attribute harus berupa angka.',

    // custom
    'attributes' => [
        'email'    => 'alamat email',
        'password' => 'kata sandi',
        'name'     => 'nama',
    ],
];