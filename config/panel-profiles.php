<?php

return [
  'panels' => [
    'admin' => [
      'guard' => 'admin',
      'layout' => 'admin.layouts.app',
      'profile_route' => 'admin.profile',
      'email_attribute' => 'email',
      'can_update' => true,
      'can_change_password' => true,
    ],
    'hr' => [
      'guard' => 'hr',
      'layout' => 'hr.layouts.app',
      'profile_route' => 'hr.profile',
      'email_attribute' => 'email',
      'can_update' => true,
      'can_change_password' => true,
    ],
    'student' => [
      'guard' => 'web',
      'layout' => 'student.layouts.app',
      'profile_route' => 'student.profile',
      'email_attribute' => 'email',
      'can_update' => true,
      'can_change_password' => true,
    ],
    'faculty' => [
      'guard' => 'web',
      'layout' => 'layouts.faculty',
      'profile_route' => 'faculty.profile',
      'email_attribute' => 'email',
      'can_update' => true,
      'can_change_password' => true,
    ],
    'ads' => [
      'guard' => 'ads',
      'layout' => 'layouts.ads',
      'profile_route' => 'ads.profile',
      'email_attribute' => 'email',
      'can_update' => true,
      'can_change_password' => true,
    ],
    'admission' => [
      'guard' => 'admission',
      'layout' => 'layouts.admission',
      'profile_route' => 'admission.profile',
      'email_attribute' => 'email',
      'can_update' => true,
      'can_change_password' => true,
    ],
    'trial' => [
      'guard' => 'trial',
      'layout' => 'trial.layouts.app',
      'profile_route' => 'trial.profile',
      'email_attribute' => 'trial_email',
      'can_update' => false,
      'can_change_password' => false,
    ],
  ],
];
