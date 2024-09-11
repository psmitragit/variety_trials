<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Crop;
use App\Models\CropVariable;
use App\Helpers\Helpers;
use App\Models\Trials;

class CropController extends BaseController
{
    public function index()
    {
        $cropModel = new Crop();
        $crops = $cropModel->select('crops.*,GROUP_CONCAT(cv.name) as variables')->join('crop_variables cv', 'crops.id=cv.crop_id', 'left')->groupBy('crops.name')->find();
        return view('backend/crop/index', \compact('crops'));
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'name' => 'required',
                'v_title.*' => 'if_exist|required',
                'v_value.*' => 'if_exist|required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $cropModel = new Crop();
            $name = $this->request->getPost('name');
            $data = [
                'name' => $name,
                'slug' => $this->createSlug($name)
            ];
            $cropId = $cropModel->insert($data);

            $variablesTitle = $this->request->getPost('v_title') ?? [];

            foreach ($variablesTitle as $k => $l) {
                $this->addVariables($l, $cropId);
            }

            return \redirect('admin/crop')->with('success', 'Crop added successfully');
        } else {
            $variables = array();

            return view('backend/crop/create', \compact('variables'));
        }
    }
    public function edit($id)
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id' => 'required|is_natural_no_zero',
                'name' => 'required|trim|is_unique[crops.name,id,{id}]',
                'v_title.*' => 'if_exist|required',
                'v_value.*' => 'if_exist|required',
            ]);
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $cropModel = new Crop();
            $data = [
                'name' => $this->request->getPost('name'),
            ];
            $cropId = $this->request->getPost('id');
            $cropModel->update($cropId, $data);
            $variables = Helpers::getVariables($id);
            $oldVariableTitles = array();
            $variablesTitle = $this->request->getPost('v_title') ?? [];
            \array_walk($variablesTitle, function ($value) {
                return \trim($value);
            });
            $varibaleModel = new CropVariable();
            foreach ($variables as $v) {
                !in_array($v['name'], $variablesTitle) ? $varibaleModel->where(['name' => $v['name'], 'crop_id' => $cropId])->delete() : "";
            }
            foreach ($variablesTitle as $k => $l) {
                $this->addVariables($l, $cropId);
            }

            return \redirect('admin/crop')->with('success', 'Crop updated successfully');
        } else {
            $cropModel = new Crop();
            $crop = $cropModel->find($id);
            $variables = Helpers::getVariables($id) ?? [];
            return view('backend/crop/create', \compact('crop', 'variables'));
        }
    }

    public function delete($id)
    {
        $cropModel = new Crop();
        $varibaleModel = new CropVariable();
        $varibaleModel->where('crop_id', $id)->delete();
        $cropModel->delete($id);
        return \redirect('admin/crop')->with('success', 'Crop deleted successfully');
    }

    // public function bulkInsert()
    // {
    //     $validate = $this->validate(['bulk_file' => 'uploaded[bulk_file]|ext_in[bulk_file,csv,xlsx]']);
    //     if (!$validate) {
    //         return redirect()->back()->with('error', $this->validator->getError('bulk_file'));
    //     }

    //     $filePath = $_FILES['bulk_file']['tmp_name'];
    //     $csv = Reader::createFromPath($filePath);
    //     $expectedHeaders = ['cropId', 'Brand', 'Variety', 'Variety Additional', 'Herbicide Package'];
    //     $headers = $csv->getHeader();
    //     $records = $csv->getRecords();
    //     $indexVarietyOriginal = \false;
    //     foreach ($records as $k => $record) {
    //         if ($k == 0) {
    //             if (empty($headers)) {
    //                 $indexVarietyOriginal = \array_search('Variety Original', $record);
    //                 $headers = $record;
    //                 $splice = \array_splice($headers, 0, 5);
    //                 if ($expectedHeaders != $splice) {
    //                     return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
    //                 }
    //                 continue;
    //             } else {
    //                 $indexVarietyOriginal = \array_search('Variety Original', $headers);
    //                 $splice = \array_splice($headers, 0, 5);
    //                 if ($expectedHeaders != $splice) {
    //                     return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
    //                 }
    //             }
    //         }

    //         $varietyOriginal = "";
    //         if ($indexVarietyOriginal) {
    //             $varietyOriginal = $record[$indexVarietyOriginal];
    //             unset($record[$indexVarietyOriginal]);
    //         }
    //         $cropModel = new Crop();

    //         $arrSplice = array_splice($record, 0, 5);
    //         $data = [
    //             'code' => trim($arrSplice[0]),
    //             'brand' => trim($arrSplice[1]),
    //             'short_name' => trim($arrSplice[2]),
    //             'additional_name' => trim($arrSplice[3]),
    //             'herbicide' => \trim($arrSplice[4]),
    //             'name' => $varietyOriginal,
    //         ];
    //         if ($variety = $cropModel->where('code', trim($arrSplice[0]))->first()) {
    //             $cropModel->update($variety['id'], $data);
    //             $cropId = $variety['id'];
    //         } else {
    //             $cropId = $cropModel->insert($data);
    //         }

    //         foreach ($headers as $k => $l) {
    //             $this->addVariables($l, $record[$k], $cropId);
    //         }
    //     }
    //     return \redirect()->back()->with('success', 'Data imported successfully');
    // }


    public function addVariables($title, $cropId)
    {
        if (!empty($title)) {
            $varibaleModel = new CropVariable();
            $data = [
                'crop_id' => $cropId,
                'name' => \trim($title)
            ];
            if (!$varibaleModel->where(['crop_id' => $cropId, 'name' => \trim($title)])->first()) {
                return $varibaleModel->insert($data);
            }
        }
    }


    public function variables($id = 0)
    {
        if ($this->request->isAJAX() && $this->request->is('post')) {
            $variables = Helpers::getVariables($this->request->getPost('id'));
            return \response()->setJSON(['status' => true, 'variables' => $variables, 'hash' => \csrf_hash()]);
        } else {
            $variables = Helpers::getVariables($id);
            return view('backend/crop/variables', \compact('variables'));
        }
    }

    public function cropDetails($id)
    {
        $cropModel = new Crop();
        $trialModel = new Trials();
        $crop = $cropModel->find($id);
        $variables = Helpers::getVariables($id);
        $trials = $trialModel->where('crop_id', $id)->findAll();
        return \view('backend/crop/details.php', \compact('crop', 'variables', 'trials'));
    }

    public function getVariables()
    {
    }

    public function createSlug($name)
    {
        $cropModel = new Crop();
        $slug = \url_title($name, '-', true);
        $crop = $cropModel->where('slug', 'like', $slug . '%')->find();
        foreach ($crop as $k => $l) {
            $slug .= '-' . $k + 1;
        }
        return $slug;
    }
}
