<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\Helpers;
use App\Models\Brand;
use App\Models\Crop;
use App\Models\Treatment;
use App\Models\Variety;
use League\Csv\Reader;

class VarietyController extends BaseController
{
    private $varietyModel, $brandModel;
    public function __construct()
    {
        $this->varietyModel = new Variety();
        $this->brandModel = new Brand;
    }
    public function index()
    {
        $admin = \auth_admin();
        $varieties = $this->varietyModel->select('varieties.*,crops.name as crop_name,users.name as user_name')
            ->join('crops', 'crops.id=varieties.crop_id');
        ($admin['type'] == 1 && !empty($admin['crop'])) ? $varieties->whereIn('crops.id', \explode(',', $admin['crop'])) : "";
        ($admin['type'] == 1) ? $varieties->join('users', 'users.id=varieties.user_id') : $varieties->join('users', 'users.id=varieties.user_id', 'left');
        $varieties = $varieties->findAll();
        return view('backend/variety/index', \compact('varieties'));
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate(
                [
                    'crop_id' => 'required|is_natural_no_zero',
                    //'code' => 'required|alpha_dash|is_unique[varieties.code]',
                    'brand' => 'required',
                    'short_name' => 'required',
                ],
                [
                    'code' => [
                        //'required' => 'The Variety/Hybrid ID field is required.',
                        'alpha_dash' => "The Variety Id field may only contain alphanumeric, underscore, and dash characters, no space allowed.",
                        'is_unique' => "The Variety Id was already in use. Please use another"
                    ]
                ]
            );
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $data = [
                'crop_id' => $this->request->getPost('crop_id'),
                'code' => $this->request->getPost('code'),
                'brand' => $this->request->getPost('brand'),
                'variety' => $this->request->getPost('variety'),
                'short_name' => $this->request->getPost('short_name'),
                'additional_name' => $this->request->getPost('additional_name'),
                'name' => $this->request->getPost('name'),
                'long' => $this->request->getPost('long'),
                'herbicide' => $this->request->getPost('herbicide'),
                'user_id' => \auth_admin()['id'],
                'status' => auth_admin()['type'] == 0 ? 1 : 0
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
        $variety = $this->varietyModel->find($id);
        if ($variety['id'] != \auth_admin()['id'] && \auth_admin()['type'] > 0) {
            return \redirect('admin/variety')->with('error', 'You are not qualified for this action');
        }
        if ($this->request->is('post')) {
            $validate = $this->validate(
                [
                    'crop_id' => 'required|is_natural_no_zero',
                    'id' => 'required|is_natural_no_zero',
                    //'code' => 'required|alpha_dash|is_unique[varieties.code,id,{id}]',
                    'brand' => 'required',
                    'short_name' => 'required',
                ],
                [
                    'code' => [
                        //'required' => 'The password field is required.',
                        'alpha_dash' => "The Variety Id field may only contain alphanumeric, underscore, and dash characters, no space allowed.",
                        'is_unique' => "The Variety Id was already in use. Please use another"
                    ]
                ]
            );
            if (!$validate) return \redirect()->back()->with('error', 'Fill all required fields')->withInput();

            $data = [
                'crop_id' => $this->request->getPost('crop_id'),
                'code' => $this->request->getPost('code'),
                'brand' => $this->request->getPost('brand'),
                'variety' => $this->request->getPost('variety'),
                'short_name' => $this->request->getPost('short_name'),
                'additional_name' => $this->request->getPost('additional_name'),
                'name' => $this->request->getPost('name'),
                'long' => $this->request->getPost('long'),
                'herbicide' => $this->request->getPost('herbicide'),
                'status' => $this->request->getPost('status')
            ];
            $varietyId = $this->request->getPost('id');
            $this->varietyModel->update($varietyId, $data);

            return \redirect('admin/variety')->with('success', 'Variety updated successfully');
        } else {
            $brands = Helpers::getBrands();
            return view('backend/variety/create', \compact('brands', 'variety'));
        }
    }

    public function delete($id)
    {
        $variety = $this->varietyModel->find($id);
        if ($variety['id'] != \auth_admin()['id'] && \auth_admin()['type'] > 0) {
            return \redirect('admin/variety')->with('error', 'You are not qualified for this action');
        }
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

        if (\auth_admin()['type'] > 0) {
            $expectedHeaders = ['Crop', 'Brand', 'Variety', 'Variety Additional', 'Herbicide Package'];
            $headerEnd = 5;
        } else {
            $expectedHeaders = ['VarietyID', 'Crop', 'Brand', 'Variety', 'Variety Additional', 'Herbicide Package'];
            $headerEnd = 6;
        }
        $headers = $csv->getHeader();
        $records = $csv->getRecords();
        foreach ($records as $k => $record) {
            if ($k == 0) {
                if (empty($headers)) {
                    $headers = $record;
                    $splice = \array_splice($headers, 0, $headerEnd);
                    if ($expectedHeaders != $splice) {
                        return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                    }
                    continue;
                } else {
                    $splice = \array_splice($headers, 0, $headerEnd);
                    if ($expectedHeaders != $splice) {
                        return \redirect()->back()->with('error', 'Uploaded CSV format is not valid!');
                    }
                }
            }


            $brandModel = new Brand();


            if (!empty(trim($record[2])) && !$brandModel->where('name', trim($record[2]))->first()) {
                $brandModel->insert(['name' => trim($record[2])]);
            }
            $arrSplice = array_splice($record, 0, $headerEnd);

            $cropModel = new Crop();


            if (\auth_admin()['type'] > 0) {
                $crop = $cropModel->where('name', trim($arrSplice[0]))->first();
                if (!empty($crop)) {
                    $data = [
                        'crop_id' => $crop['id'] ?? 0,
                        'brand' => trim($arrSplice[1]),
                        'short_name' => trim($arrSplice[2]),
                        'additional_name' => trim($arrSplice[3]),
                        'herbicide' => \trim($arrSplice[4]),
                        'user_id'   => \auth_admin()['id'],
                        'status'    => 0
                    ];
                    if ($variety = $this->varietyModel->where('short_name', trim($arrSplice[2]))->where('user_id', \auth_admin()['id'])->first()) {
                        $this->varietyModel->update($variety['id'], $data);
                    } else {
                        $this->varietyModel->insert($data);
                    }
                }
            } else {
                $crop = $cropModel->where('name', trim($arrSplice[1]))->first();
                if (!empty($crop)) {
                    $data = [
                        'code' => trim($arrSplice[0]),
                        'crop_id' => $crop['id'] ?? 0,
                        'brand' => trim($arrSplice[2]),
                        'short_name' => trim($arrSplice[3]),
                        'additional_name' => trim($arrSplice[4]),
                        'herbicide' => \trim($arrSplice[5]),
                    ];
                    if ($variety = $this->varietyModel->where('code', $data['code'])->first()) {
                        $this->varietyModel->update($variety['id'], $data);
                    } else {
                        $this->varietyModel->insert($data);
                    }
                }
            }
        }
        return \redirect()->back()->with('success', 'Data imported successfully');
    }

    public function getSingle()
    {
        if ($this->request->isAJAX()) {
            $code = $this->request->getPost('id');
            $variety = $this->varietyModel->where('code', $code)->first();
            $treatmentModel = new Treatment;
            $treatments = !empty($variety) ? $treatmentModel->where('variety_id', $variety['id'])->where('is_approved', 1)->where('name IS NOT NUll')->findAll() : array();
            return \response()->setJSON(['status' => true, 'data' => $variety, 'treatments' => $treatments, 'hash' => \csrf_hash()]);
        }
        return \response()->setJSON(['status' => false, 'hash' => \csrf_hash()]);
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids)) return redirect()->back()->with('warning', 'Please select atleast one variety to delete!');
        foreach ($ids as $id) {
            $this->delete($id);
        }
        return \redirect()->back()->with('success', "Varities deleted");
    }

    public function getVarietiesByCrop()
    {
        $admin = \auth_admin();
        if ($this->request->isAJAX()) {
            $cropId = $this->request->getPost('crop_id');
            $varieties = $this->varietyModel->where('crop_id', $cropId)->where('status', 1);
            if ($admin['type'] > 0) {
                $varieties->where('user_id', $admin['id']);
            }
            $varieties = $varieties->orderBy('name', 'asc')->findAll();
            return response()->setJSON(['status' => true, 'varieties' => $varieties]);
        }
    }



    public function manageBrand()
    {

        $brands = $this->brandModel->where('status', 1)->orderBy('name', 'asc')->findAll();
        return view('backend/variety/brand', compact('brands'));
    }

    public function createBrand()
    {
        if ($this->request->is('post')) {
            $validate = $this->validate([
                'id' => 'required',
                'name' => "required|is_unique[brands.name,id,{id}]",
            ]);

            if (!$validate) return redirect()->back()->with('error', 'Please fill all required fields')->withInput();

            $id = $this->request->getPost('id');
            $data = [
                'name' => $this->request->getPost('name')
            ];
            if ($id > 0) {
                $this->brandModel->update($id, $data);
                $message = "Brand updated";
            } else {
                $this->brandModel->insert($data);
                $message = "Brand created";
            }
            return redirect()->back()->with('success', $message);
        }
        return redirect()->back()->with('error', 'Unauthorised access detected!');
    }

    public function deleteBrand($id)
    {
        $this->brandModel->delete($id);
        return \redirect()->back()->with('success', 'Brand deleted successfully');
    }

    public function approve()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $variety = $this->varietyModel->find($id);
            $data['status'] = !$variety['status'];
            $this->varietyModel->update($id, $data);
            return  response()->setJSON(['status' => true, 'is_approved' => $data['status']]);
        }
    }

    public function addEntry()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $data = [
                'code' => $this->request->getPost('value'),
                'status' => 1
            ];
            $this->varietyModel->update($id, $data);
            return  response()->setJSON(['status' => true]);
        }
    }
}
