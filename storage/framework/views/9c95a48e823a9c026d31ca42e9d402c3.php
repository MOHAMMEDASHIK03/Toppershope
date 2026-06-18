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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
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
<body class="min-h-screen bg-white dark:bg-[#0F0F12] flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <?php if (isset($component)) { $__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand.logo','data' => ['variant' => 'full','context' => 'auth','class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand.logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'full','context' => 'auth','class' => 'mb-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b)): ?>
<?php $attributes = $__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b; ?>
<?php unset($__attributesOriginal01ddce07fdfaa1771f1dfaf8e4a4294b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b)): ?>
<?php $component = $__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b; ?>
<?php unset($__componentOriginal01ddce07fdfaa1771f1dfaf8e4a4294b); ?>
<?php endif; ?>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight"><?php echo e($heading); ?></h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1"><?php echo e($subtitle); ?></p>
        </div>

        <div class="bg-white dark:bg-[#1E1E24] border border-gray-200 dark:border-[#2D2D35] rounded-2xl shadow-sm p-8">
            <?php echo e($beforeForm ?? ''); ?>


            <form action="<?php echo e($formAction); ?>" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo e($fields ?? ''); ?>


                <?php if (! (isset($fields))): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="<?php echo e($emailField); ?>"><?php echo e($emailLabel); ?></label>
                    <input type="email" name="<?php echo e($emailField); ?>" id="<?php echo e($emailField); ?>" value="<?php echo e(old($emailField)); ?>" required autofocus
                        <?php if($emailPlaceholder): ?> placeholder="<?php echo e($emailPlaceholder); ?>" <?php endif; ?>
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-[#2D2D35] rounded-lg focus:ring-4 focus:ring-primary-600/15 focus:border-primary-600 outline-none text-gray-900 dark:text-white dark:bg-[#17171C]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5" for="password">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 dark:border-[#2D2D35] rounded-lg focus:ring-4 focus:ring-primary-600/15 focus:border-primary-600 outline-none text-gray-900 dark:text-white dark:bg-[#17171C]">
                </div>
                <?php endif; ?>

                <?php if($showRemember): ?>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600/20">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Keep me signed in</span>
                </label>
                <?php endif; ?>

                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                    <?php echo e($buttonLabel); ?>

                    <i class="ph ph-arrow-right"></i>
                </button>
            </form>

            <?php echo e($afterForm ?? ''); ?>

        </div>

        <div class="flex items-center justify-center gap-3 mt-6">
            <?php if (isset($component)) { $__componentOriginaldc05996cb88133a36764c91b2cfe9d3e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.panel.theme-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('panel.theme-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e)): ?>
<?php $attributes = $__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e; ?>
<?php unset($__attributesOriginaldc05996cb88133a36764c91b2cfe9d3e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldc05996cb88133a36764c91b2cfe9d3e)): ?>
<?php $component = $__componentOriginaldc05996cb88133a36764c91b2cfe9d3e; ?>
<?php unset($__componentOriginaldc05996cb88133a36764c91b2cfe9d3e); ?>
<?php endif; ?>
            <p class="text-xs text-gray-400">&copy; <?php echo e(date('Y')); ?> Topper's Hope</p>
        </div>
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
<?php /**PATH D:\Lama Projects\Toppershope\resources\views/components/panel/auth-page.blade.php ENDPATH**/ ?>