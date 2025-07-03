<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="excel_file" :value="__('Excel File')" />
                            <x-text-input id="excel_file" name="excel_file" type="file" accept=".xls,.xlsx" required />
                            <x-input-error :messages="$errors->get('excel_file')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-primary-button>{{ __('Upload') }}</x-primary-button>
                        </div>
                    </form>

					<div class="mt-6">
						<h4 class="text-lg font-semibold">Upload Stats</h4>
						<div id="upload-stats" class="mt-2 text-sm text-gray-700"></div>
						<div id="upload-errors" class="mt-2 text-sm text-red-700"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
	@push('scripts')
	<script>
		$(document).ready(function() {
			@if (session('message'))
				toastr.success("{{ session('message') }}");
			@endif
		});

		Echo.channel('product-excel-file-imported')
			.listen('.uploaded-file.updated', (e) => {
				if (e.status === 'complete') {
					toastr.success("File processing complete.");

					// Render stats if present
					const stats = JSON.parse(e.statistics || '{}');
					const errors = JSON.parse(e.error_log || '[]');

					let statsHtml = `<strong>Total:</strong> ${stats.total}, <strong>New:</strong> ${stats.new}, <strong>Failed:</strong> ${stats.failed}`;
					$('#upload-stats').html(statsHtml);

					if (errors.length > 0) {
						let errorList = errors.map(err => `<li>Row ${err.row}: ${err.errors.join(', ')}</li>`).join('');
						$('#upload-errors').html(`<ul>${errorList}</ul>`);
					}
				}
			});
	</script>
	@endpush
</x-app-layout>
