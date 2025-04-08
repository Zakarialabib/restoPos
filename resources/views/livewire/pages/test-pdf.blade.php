<?php

use function Livewire\Volt\{state};

use function Livewire\Volt\{layout, title};

state([
    'documentId' => 'test-document-123',
    'pdfUrl' => asset('sample.pdf')
]);

layout('layouts.guest');
title('PDF Viewer Test');

?>
<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">PDF Viewer Test</h1>

                    <livewire:pdf-viewer :document="$documentId" :pdf-url="$pdfUrl"
                        :debug="true" />
                </div>
            </div>
        </div>
    </div>
</div>