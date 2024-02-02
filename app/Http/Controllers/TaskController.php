<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Picqer\Barcode\BarcodeGeneratorHTML;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $tasks = Task::with(['category'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query());

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreTaskRequest $request)
{
    $data = $request->validated();
    
    // Formater la date avec Carbon en français
    $data['date'] = Carbon::createFromFormat('Y-m-d', $data['date'])->locale('fr_FR')->toDateString();

    $task = Task::create($data);

    return redirect()
        ->route('tasks.index')
        ->with('success', 'La tâche a été créée');
}

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', [
            'categories' => Category::all(),
            'task' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->except('product_image'));

        /**
         * Handle upload an image
         */
        if($request->hasFile('product_image')){

            // Delete Old Photo
            if($task->task){
                unlink(public_path('storage/tasks/') . $task->product_image);
            }

            // Prepare New Photo
            $file = $request->file('product_image');
            $fileName = hexdec(uniqid()).'.'.$file->getClientOriginalExtension();

            // Store an image to Storage
            $file->storeAs('tasks/', $fileName, 'public');

            // Save DB
            $task->update([
                'product_image' => $fileName
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Product has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'La tâche a bien été supprimé');
    }

    /**
     * Handle export data products.
     */
    public function import()
    {
        return view('tasks.import');
    }

    public function handleImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $the_file = $request->file('file');

        try{
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'J', $column_limit );
            $startcount = 2;
            $data = array();
            foreach ( $row_range as $row ) {
                $data[] = [
                    'product_name' => $sheet->getCell( 'A' . $row )->getValue(),
                    'category_id' => $sheet->getCell( 'B' . $row )->getValue(),
                    'unit_id' => $sheet->getCell( 'C' . $row )->getValue(),
                    'product_code' => $sheet->getCell( 'D' . $row )->getValue(),
                    'stock' => $sheet->getCell( 'E' . $row )->getValue(),
                    'buying_price' => $sheet->getCell( 'F' . $row )->getValue(),
                    'selling_price' =>$sheet->getCell( 'G' . $row )->getValue(),
                    'product_image' =>$sheet->getCell( 'H' . $row )->getValue(),
                ];
                $startcount++;
            }

            Task::insert($data);

        } catch (Exception $e) {
            // $error_code = $e->errorInfo[1];
            return redirect()
                ->route('tasks.index')
                ->with('error', 'There was a problem uploading the data!');
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Data product has been imported!');
    }

    /**
     * Handle export data products.
     */
    function export(){
        $tasks = Task::all()->sortBy('name');

        $task_array [] = array(
            'Nom',
            'Category Id',
            'Date',
        );

        foreach($tasks as $task)
        {
            $task_array[] = array(
                'Name' => $task->name,
                'Category Id' => $task->category_id,
                'Date' => $task->date,
            );
        }

        $this->exportExcel($task_array);
    }

    /**
     *This function loads the customer data from the database then converts it
     * into an Array that will be exported to Excel
     */
    public function exportExcel($tasks){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        try {
            $spreadSheet = new Spreadsheet();
            $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
            $spreadSheet->getActiveSheet()->fromArray($tasks);
            $Excel_writer = new Xls($spreadSheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="tasks.xls"');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            $Excel_writer->save('php://output');
            exit();
        } catch (Exception $e) {
            return;
        }
    }

}
