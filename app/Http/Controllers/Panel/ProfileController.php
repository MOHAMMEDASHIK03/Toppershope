<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Support\PanelProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
  public function show(string $panelKey)
  {
    $config = PanelProfile::config($panelKey);
    $user = $this->resolveUser($panelKey, $config);

    if ($panelKey === 'trial') {
      $user->loadMissing('batch');
    }

    return view('panel.profile.show', [
      'panelKey' => $panelKey,
      'config' => $config,
      'user' => $user,
      'roleLabel' => PanelProfile::roleLabel($panelKey, $user),
      'displayEmail' => PanelProfile::displayEmail($panelKey, $user, $config),
      'editableFields' => PanelProfile::editableFields($panelKey),
      'readOnlyFields' => PanelProfile::readOnlyFields($panelKey, $user),
    ]);
  }

  public function update(Request $request, string $panelKey)
  {
    $config = PanelProfile::config($panelKey);

    if (!($config['can_update'] ?? false)) {
      abort(403);
    }

    $user = $this->resolveUser($panelKey, $config);
    $changingPassword = $request->filled('password');

    if ($changingPassword && !($config['can_change_password'] ?? false)) {
      abort(403);
    }

    $rules = PanelProfile::validationRules($panelKey, $changingPassword);
    $validated = $request->validate($rules);

    if ($changingPassword) {
      if (!Hash::check($validated['current_password'], $user->getAuthPassword())) {
        throw ValidationException::withMessages([
          'current_password' => 'The current password is incorrect.',
        ]);
      }

      $casts = method_exists($user, 'getCasts') ? $user->getCasts() : [];
      $user->password = ($casts['password'] ?? null) === 'hashed'
        ? $validated['password']
        : Hash::make($validated['password']);
      $user->save();
    }

    PanelProfile::persist($panelKey, $user, $validated);

    return back()->with('success', 'Profile updated successfully.');
  }

  private function resolveUser(string $panelKey, array $config)
  {
    $guard = $config['guard'] ?? 'web';
    $user = Auth::guard($guard)->user();

    if (!$user) {
      abort(403);
    }

    if ($panelKey === 'faculty' && method_exists($user, 'isFaculty') && !$user->isFaculty()) {
      abort(403);
    }

    if ($panelKey === 'student' && ($user->role ?? 'student') !== 'student') {
      abort(403);
    }

    return $user;
  }
}
