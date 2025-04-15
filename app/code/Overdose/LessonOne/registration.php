<?php
/**
 * Register the module with Magento
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,  // Type: Module
    'Overdose_LessonOne',  // Module name
    __DIR__  // Directory where the module is located
);