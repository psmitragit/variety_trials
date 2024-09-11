<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\Brand;
use App\Models\Variety;
use League\Csv\Reader;
use PhpParser\Node\Expr\FuncCall;

class VarietyController extends BaseController
{
    private $varietyModel;
    public function __construct()
    {
        $this->varietyModel = new Variety();
    }
    public function index()
    {
        $varieties = $this->varietyModel->findAll();
        return view('backend/variety/index', \compact('varieties'));
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'code' => 'required|is_unique[varieties.code]',
                'brand' => 'required',
                'short_name' => 'required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $data = [
                'code' => $this->request->getPost('code'),
                'brand' => $this->request->getPost('brand'),
                'variety' => $this->request->getPost('variety'),
                'short_name' => $this->request->getPost('short_name'),
                'additional_name' => $this->request->getPost('additional_name'),
                'name' => $this->request->getPost('name'),
                'long' => $this->request->getPost('long'),
                'herbicide' => $this->request->getPost('herbicide'),
            ];
            $varietyId = $this->varietyModel->insert($data);

            return \redirect('admin/variety')->with('success', 'Variety added successfully');
        } else {
            $variables = array();
            $brands = Helpers::getBrands();
            return view('backend/variety/create', \compact('brands', 'variables'));
        }
    }
    public function edit($id)
    {

        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id' => 'required|is_natural_no_zero',
                'code' => 'required|is_unique[varieties.code,id,{id}]',
                'brand' => 'required',
                'short_name' => 'required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $data = [
                'code' => $this->request->getPost('code'),
                'brand' => $this->request->getPost('brand'),
                'variety' => $this->request->getPost('variety'),
                'short_name' => $this->request->getPost('short_name'),
                'additional_name' => $this->request->getPost('additional_name'),
                'name' => $this->request->getPost('name'),
                'long' => $this->request->getPost('long'),
                'herbicide' => $this->request->getPost('herbicide'),
            ];
            $varietyId = $this->request->getPost('id');
            $this->varietyModel->update($varietyId, $data);

            return \redirect('admin/variety')->with('success', 'Variety updated successfully');
        } else {
            $variety = $this->varietyModel->find($id);
            $brands = Helpers::getBrands();
            return view('backend/variety/create', \compact('brands', 'variety'));
        }
    }

    public function delete($id)
    {
        $this->varietyModel->delete($id);
        return \redirect('admin/variety')->with('success', 'Variety deleted successfully');
    }

    public function bulkInsert()
    {
        $validate = $this->validate(['bulk_file' => 'uploaded[bulk_file]|ext_in[bulk_file,csv]']);
        if (!$validate) {
            return redirect()->back()->with('error', $this->validator->getError('bulk_file'));
        }

        $filePath = $_FILES['bulk_file']['tmp_name'];
        $csv = Reader::createFromPath($filePath);
        $expectedHeaders = ['VarietyID', 'Brand', 'Variety', 'Variety Additional', 'Herbicide Package'];
        $headers = $csv->getHeader();
        $records = $csv->getRecords();
        $indexVarietyOriginal = \false;
        foreach ($records as $k => $record) {
            if ($k == 0) {
                if (empty($headers)) {
                    $indexVarietyOriginal = \array_search('Variety Original', $record);
                    $headers = $record;
                    $splice = \array_splice($headers, 0, 5);
                    if ($expectedHeaders != $splice) {
                        return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                    }
                    continue;
                } else {
                    $indexVarietyOriginal = \array_search('Variety Original', $headers);
                    $splice = \array_splice($headers, 0, 5);
                    if ($expectedHeaders != $splice) {
                        return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                    }
                }
            }

            $varietyOriginal = "";
            if ($indexVarietyOriginal) {
                $varietyOriginal = $record[$indexVarietyOriginal];
                unset($record[$indexVarietyOriginal]);
            }
            $brandModel = new Brand();


            if (!empty(trim($record[1])) && !$brandModel->where('name', trim($record[1]))->first()) {
                $brandModel->insert(['name' => trim($record[1])]);
            }
            $arrSplice = array_splice($record, 0, 5);
            $data = [
                'code' => trim($arrSplice[0]),
                'brand' => trim($arrSplice[1]),
                'short_name' => trim($arrSplice[2]),
                'additional_name' => trim($arrSplice[3]),
                'herbicide' => \trim($arrSplice[4]),
                'name' => $varietyOriginal,
            ];
            if ($variety = $this->varietyModel > where('code', trim($arrSplice[0]))->first()) {
                $this->varietyModel->update($variety['id'], $data);
            } else {
                $this->varietyModel->insert($data);
            }
        }
        return \redirect()->back()->with('success', 'Data imported successfully');
    }

    public function getSingle()
    {
        if ($this->request->isAJAX()) {
            $code = $this->request->getPost('id');
            $variety = $this->varietyModel->where('code', $code)->first();
            return \response()->setJSON(['status' => true, 'data' => $variety, 'hash' => \csrf_hash()]);
        }
        return \response()->setJSON(['status' => false, 'hash' => \csrf_hash()]);
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        foreach ($ids as $id) {
            $this->delete($id);
        }
        return \redirect()->back()->with('success', "Varities deleted");
    }
}
