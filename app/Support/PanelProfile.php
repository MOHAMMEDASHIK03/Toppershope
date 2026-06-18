<?php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;

class PanelProfile
{
  public static function config(string $panelKey): array
  {
    $config = config("panel-profiles.panels.{$panelKey}");

    if (!$config) {
      abort(404);
    }

    return $config;
  }

  public static function roleLabel(string $panelKey, Authenticatable $user): string
  {
    return match ($panelKey) {
      'admin' => ($user->is_super_admin ?? false) ? 'Super Admin' : 'Admin',
      'hr' => ($user->role ?? '') === 'hr_manager' ? 'HR Manager' : 'HR Executive',
      'student' => ucfirst((string) ($user->target_exam ?? 'Student')),
      'faculty' => method_exists($user, 'isFacultyHead') && $user->isFacultyHead()
        ? 'Faculty Head'
        : 'Faculty',
      'ads' => ($user->role ?? '') === 'ads_head' ? 'Ads Head' : 'Ads Manager',
      'admission' => ($user->role ?? '') === 'head' ? 'Admissions Head' : 'Admissions Staff',
      'trial' => 'Trial Student',
      default => 'User',
    };
  }

  public static function displayEmail(string $panelKey, Authenticatable $user, array $config): string
  {
    $field = $config['email_attribute'] ?? 'email';

    return (string) ($user->{$field} ?? $user->email ?? '');
  }

  public static function displayName(Authenticatable $user): string
  {
    return (string) ($user->name ?? 'User');
  }

  public static function initial(Authenticatable $user): string
  {
    return strtoupper(substr(static::displayName($user), 0, 1));
  }

  /** @return array<int, array<string, mixed>> */
  public static function editableFields(string $panelKey): array
  {
    return match ($panelKey) {
      'student' => [
        ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
        ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'required' => false],
        [
          'name' => 'target_exam',
          'label' => 'Target Exam',
          'type' => 'select',
          'required' => false,
          'options' => [
            '' => 'Select preference',
            'jee' => 'JEE',
            'neet' => 'NEET',
            'foundation' => 'Foundation',
            'boards' => 'Boards',
            'coding' => 'Coding / Python',
          ],
        ],
        ['name' => 'dob', 'label' => 'Date of Birth', 'type' => 'date', 'required' => false],
        ['name' => 'district', 'label' => 'District', 'type' => 'text', 'required' => false],
        ['name' => 'state', 'label' => 'State', 'type' => 'text', 'required' => false],
      ],
      'faculty' => [
        ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
        ['name' => 'phone', 'label' => 'Phone', 'type' => 'text', 'required' => false],
      ],
      'admin', 'hr', 'ads', 'admission' => [
        ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
      ],
      default => [],
    };
  }

  /** @return array<int, array<string, mixed>> */
  public static function readOnlyFields(string $panelKey, Authenticatable $user): array
  {
    $email = static::displayEmail($panelKey, $user, static::config($panelKey));
    $role = static::roleLabel($panelKey, $user);

    $fields = [
      ['name' => 'email', 'label' => 'Email', 'value' => $email],
      ['name' => 'role', 'label' => 'Role', 'value' => $role],
    ];

    if ($panelKey === 'trial') {
      $fields = [
        ['name' => 'trial_email', 'label' => 'Trial Login Email', 'value' => (string) ($user->trial_email ?? '')],
        ['name' => 'email', 'label' => 'Contact Email', 'value' => (string) ($user->email ?? '')],
        ['name' => 'phone', 'label' => 'Phone', 'value' => (string) ($user->phone ?? '')],
        ['name' => 'role', 'label' => 'Access Type', 'value' => $role],
      ];
    }

    if (in_array($panelKey, ['hr', 'ads', 'admission'], true)) {
      $fields[] = [
        'name' => 'is_active',
        'label' => 'Account Status',
        'value' => ($user->is_active ?? true) ? 'Active' : 'Inactive',
      ];
    }

    if ($panelKey === 'admin') {
      $fields[] = [
        'name' => 'is_super_admin',
        'label' => 'Access Level',
        'value' => ($user->is_super_admin ?? false) ? 'Super Admin' : 'Admin',
      ];
    }

    if ($panelKey === 'trial') {
      $fields[] = [
        'name' => 'batch',
        'label' => 'Trial Batch',
        'value' => $user->batch?->name ?? '—',
      ];
      $fields[] = [
        'name' => 'expires_at',
        'label' => 'Access Expires',
        'value' => $user->expires_at instanceof Carbon
          ? $user->expires_at->format('d M Y')
          : '—',
      ];
      $fields[] = [
        'name' => 'days_left',
        'label' => 'Days Remaining',
        'value' => method_exists($user, 'daysLeft') ? (string) $user->daysLeft() : '—',
      ];
    }

    if ($user->created_at ?? null) {
      $fields[] = [
        'name' => 'member_since',
        'label' => 'Member Since',
        'value' => $user->created_at->format('d M Y'),
      ];
    }

    return $fields;
  }

  public static function validationRules(string $panelKey, bool $changingPassword): array
  {
    $rules = [];

    foreach (static::editableFields($panelKey) as $field) {
      $rule = match ($field['name']) {
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'target_exam' => 'nullable|string|max:50',
        'dob' => 'nullable|date',
        'district', 'state' => 'nullable|string|max:100',
        default => 'nullable|string|max:255',
      };
      $rules[$field['name']] = $rule;
    }

    if ($changingPassword) {
      $rules['current_password'] = 'required|string';
      $rules['password'] = 'required|string|min:8|confirmed';
    }

    return $rules;
  }

  public static function persist(string $panelKey, Authenticatable $user, array $data): void
  {
    $payload = [];

    foreach (static::editableFields($panelKey) as $field) {
      $name = $field['name'];
      if (!array_key_exists($name, $data)) {
        continue;
      }

      $value = $data[$name];

      if ($name === 'target_exam') {
        $value = filled($value) ? strtolower((string) $value) : null;
      }

      $payload[$name] = $value;
    }

    if ($payload !== []) {
      $user->update($payload);
    }
  }
}
