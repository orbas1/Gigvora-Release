<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', get_settings('system_name'))); ?></title>

        <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">

        <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
        <?php echo $__env->yieldPushContent('head'); ?>
    </head>
    <body class="font-sans antialiased gv-body bg-[var(--gv-color-neutral-50)] text-[var(--gv-color-neutral-800)]">
        <a href="#guest-content" class="gv-skip-link"><?php echo e(__('Skip to main content')); ?></a>
        <div id="guest-content" class="min-h-screen">
            <?php echo e($slot); ?>

        </div>
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>
<?php /**PATH /Users/user/Downloads/Gigvora-Release/resources/views/layouts/guest.blade.php ENDPATH**/ ?>