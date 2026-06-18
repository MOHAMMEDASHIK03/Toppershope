<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'pageTitle' => 'Sign In',
    'heading',
    'subtitle',
    'formAction',
    'buttonLabel' => 'Sign in',
    'emailField' => 'email',
    'emailLabel' => 'Email',
    'emailPlaceholder' => null,
    'showRemember' => true,
    'accent' => 'indigo',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'pageTitle' => 'Sign In',
    'heading',
    'subtitle',
    'formAction',
    'buttonLabel' => 'Sign in',
    'emailField' => 'email',
    'emailLabel' => 'Email',
    'emailPlaceholder' => null,
    'showRemember' => true,
    'accent' => 'indigo',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $accentMap = [
        'indigo' => ['logo' => 'from-orange-500 to-amber-500', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'ring' => 'focus:ring-orange-500/20 focus:border-orange-500'],
        'orange' => ['logo' => 'from-orange-500 to-amber-500', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'ring' => 'focus:ring-orange-500/20 focus:border-orange-500'],
        'emerald' => ['logo' => 'from-emerald-600 to-teal-600', 'btn' => 'bg-orange-500 hover:bg-orange-600', 'ring' => 'focus:ring-orange-500/20 focus:border-orange-500'],
    ];
    $accentClasses = $accentMap[$accent] ?? $accentMap['indigo'];
?>
<!DOCTYPE html>
<html lang="en" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($pageTitle); ?> | Topper's Hope</title>
    <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1/src/index.js" type="module"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-[#f4f6f8] flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-xl bg-gradient-to-br <?php echo e($accentClasses['logo']); ?> flex items-center justify-center text-white font-bold text-lg shadow-lg mb-4">TH</div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight"><?php echo e($heading); ?></h1>
            <p class="text-slate-500 text-sm mt-1"><?php echo e($subtitle); ?></p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8">
            <?php echo e($beforeForm ?? ''); ?>


            <form action="<?php echo e($formAction); ?>" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo e($fields ?? ''); ?>


                <?php if (! (isset($fields))): ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="<?php echo e($emailField); ?>"><?php echo e($emailLabel); ?></label>
                    <input type="email" name="<?php echo e($emailField); ?>" id="<?php echo e($emailField); ?>" value="<?php echo e(old($emailField)); ?>" required autofocus
                        <?php if($emailPlaceholder): ?> placeholder="<?php echo e($emailPlaceholder); ?>" <?php endif; ?>
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg <?php echo e($accentClasses['ring']); ?> outline-none text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="password">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg <?php echo e($accentClasses['ring']); ?> outline-none text-slate-900">
                </div>
                <?php endif; ?>

                <?php if($showRemember): ?>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-slate-600">Keep me signed in</span>
                </label>
                <?php endif; ?>

                <button type="submit" class="w-full py-2.5 <?php echo e($accentClasses['btn']); ?> text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    <?php echo e($buttonLabel); ?>

                    <i class="ph ph-arrow-right"></i>
                </button>
            </form>

            <?php echo e($afterForm ?? ''); ?>

        </div>

        <p class="text-center text-xs text-slate-400 mt-6">&copy; <?php echo e(date('Y')); ?> Topper's Hope</p>
    </div>

    <?php if (isset($component)) { $__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.toast-stack','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('toast-stack'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007)): ?>
<?php $attributes = $__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007; ?>
<?php unset($__attributesOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007)): ?>
<?php $component = $__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007; ?>
<?php unset($__componentOriginal8cfcb0fc4ab55bb76cadc5ba7a0de007); ?>
<?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Lama Projects\toppershope-website\resources\views/components/panel/auth-page.blade.php ENDPATH**/ ?>