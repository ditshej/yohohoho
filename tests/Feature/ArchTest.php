<?php

it('all artisan commands have a corresponding test file', function () {
    $commandFiles = glob(app_path('Console/Commands/*.php')) ?: [];

    if (empty($commandFiles)) {
        expect(true)->toBeTrue(); // no commands to check

        return;
    }

    foreach ($commandFiles as $commandFile) {
        $commandName = basename($commandFile, '.php');
        $testFile = base_path("tests/Feature/{$commandName}Test.php");

        expect(file_exists($testFile))
            ->toBeTrue("Missing test file for command: {$commandName}");
    }
});
