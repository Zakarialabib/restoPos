<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Customers;

use App\Exports\CustomerExport;
use App\Imports\CustomerImport;
use App\Livewire\Utils\Datatable;
use App\Models\Customer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class Index extends Component
{
    use Datatable;
    use WithFileUploads;

    public $customer;

    public $file;

    public $importModal = false;

    public $model = Customer::class;


    public function render()
    {
        abort_if(Gate::denies('customer_access'), 403);

        $query = Customer::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $customers = $query->paginate($this->perPage);

        return view('livewire.admin.customers.index', ['customers' => $customers]);
    }

    public function deleteSelected(): void
    {
        abort_if(Gate::denies('customer_delete'), 403);

        Customer::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Customer $customer): void
    {
        abort_if(Gate::denies('customer_delete'), 403);

        $customer->delete();

        $this->alert('warning', __('Customer deleted successfully'));
    }

    public function downloadSelected(): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        $customers = Customer::whereIn('id', $this->selected)->get();

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function downloadAll(Customer $customers): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        return (new CustomerExport($customers))->download('customers.xls', \Maatwebsite\Excel\Excel::XLS);
    }

    public function exportSelected(): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        return $this->callExport()->forModels($this->selected)->download('customers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function exportAll(): StreamedResponse|Response
    {
        abort_if(Gate::denies('customer_export'), 403);

        return $this->callExport()->download('customers.pdf', \Maatwebsite\Excel\Excel::MPDF);
    }

    public function importExcel(): void
    {
        abort_if(Gate::denies('customer_import'), 403);

        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);

        Excel::import(new CustomerImport(), $this->file);

        $this->importModal = false;

        $this->alert('success', __('Customer imported successfully.'));
    }

    public function downloadSample()
    {
        return Storage::disk('exports')->download('customers_import_sample.xls');
    }

    private function callExport(): CustomerExport
    {
        return new CustomerExport();
    }
}
