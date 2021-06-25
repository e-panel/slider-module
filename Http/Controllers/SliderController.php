<?php

namespace Modules\Slider\Http\Controllers;

use Modules\Core\Http\Controllers\CoreController as Controller;
use Illuminate\Http\Request;

use Modules\Slider\Entities\Slider;
use Modules\Slider\Http\Requests\SliderRequest;

class SliderController extends Controller
{
    protected $title;

    /**
     * Siapkan konstruktor controller
     * 
     * @param Slider $data
     */
    public function __construct(Slider $data) 
    {
        $this->title = __('slider::general.title');
        $this->data = $data;

        $this->toIndex = route('epanel.slider.index');
        $this->prefix = 'epanel.slider';
        $this->view = 'slider::slider';

        $this->tCreate = __('slider::general.created', ['title' => $this->title]);
        $this->tUpdate = __('slider::general.updated', ['title' => $this->title]);
        $this->tDelete = __('slider::general.deleted', ['title' => $this->title]);

        view()->share([
            'title' => $this->title, 
            'view' => $this->view, 
            'prefix' => $this->prefix
        ]);
    }

    /**
     * Tampilkan halaman utama modul yang dipilih
     * 
     * @param Request $request
     * @return Response|View
     */
    public function index(Request $request) 
    {
        $data = $this->data->latest()->get();

        if($request->has('datatable')):
            return $this->datatable($data);
        endif;

        return view("$this->view.index", compact('data'));
    }

    /**
     * Tampilkan halaman untuk menambah data
     * 
     * @return Response|View
     */
    public function create() 
    {
        return view("$this->view.create");
    }

    /**
     * Lakukan penyimpanan data ke database
     * 
     * @param Request $request
     * @return Response|View
     */
    public function store(SliderRequest $request) 
    {
        $input = $request->all();

        $input['id_operator'] = optional(auth()->user())->id;

        if($request->hasFile('file')):
            $input['file'] = $this->upload($request->file('file'), uuid());
        endif;

        $this->data->create($input);

        notify()->flash($this->tCreate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Menampilkan detail lengkap
     * 
     * @param Int $id
     * @return Response|View
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Tampilkan halaman perubahan data
     * 
     * @param Int $id
     * @return Response|View
     */
    public function edit($id)
    {
        $edit = $this->data->uuid($id)->firstOrFail();
    
        return view("$this->view.edit", compact('edit'));
    }

    /**
     * Lakukan perubahan data sesuai dengan data yang diedit
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|View
     */
    public function update(SliderRequest $request, $id)
    {    
        $edit = $this->data->uuid($id)->firstOrFail();

        $input = $request->all();

        if($request->hasFile('file')):          
            deleteImg($edit->file);
            $input['file'] = $this->upload($request->file('file'), uuid());
        else:
            $input['file'] = $edit->file;
        endif;
        
        $edit->update($input);

        notify()->flash($this->tUpdate, 'success');
        return redirect($this->toIndex);
    }

    /**
     * Lakukan penghapusan data yang tidak diinginkan
     * 
     * @param Request $request
     * @param Int $id
     * @return Response|String
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('pilihan')):
            foreach($request->pilihan as $temp):
                $each = $this->data->uuid($temp)->firstOrFail();
                deleteImg($each->file);
                $each->delete();
            endforeach;
            notify()->flash($this->tDelete, 'success');
            return redirect()->back();
        endif;
    }

    /**
     * Function for Upload File
     * 
     * @param  $file, $filename
     * @return URI
     */
    public function upload($file, $filename) 
    {
        $tmpFilePath = 'app/public/Slider/';
        $tmpFileDate =  date('Y-m') .'/'.date('d').'/';
        $tmpFileName = $filename;
        $tmpFileExt = $file->getClientOriginalExtension();

        makeImgDirectory($tmpFilePath . $tmpFileDate);
        
        $nama_file = $tmpFilePath . $tmpFileDate . $tmpFileName;
        
        \Image::make($file->getRealPath())->resize(1430, null, function($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path() . "/$nama_file.$tmpFileExt");
        
        \Image::make($file->getRealPath())->fit(150, 50)->save(storage_path() . "/{$nama_file}_m.$tmpFileExt");

        return "storage/Slider/{$tmpFileDate}{$tmpFileName}.{$tmpFileExt}";
    }

    /**
     * Datatable API
     * 
     * @param  $data
     * @return Datatable
     */
    public function datatable($data) 
    {
        return datatables()->of($data)
            ->editColumn('pilihan', function($data) {
                $return  = '<span>';
                $return .= '    <div class="checkbox checkbox-only">';
                $return .= '        <input type="checkbox" id="pilihan['.$data->id.']" name="pilihan[]" value="'.$data->uuid.'">';
                $return .= '        <label for="pilihan['.$data->id.']"></label>';
                $return .= '    </div>';
                $return .= '</span>';
                return $return;
            })
            ->editColumn('file', function($data) {
                return '<a href="'. viewImg($data->file) . '" data-lity><img src="'. viewImg($data->file, 'm') . '" class="img-responsive img-thumbnail" width="100px"></a>';
            })
            ->editColumn('judul', function($data) {
                $return  = $data->judul ?? '-';
                $return .= '<div class="font-11 color-blue-grey-lighter">';
                $return .=      $data->keterangan ?? '-';
                $return .= '</div>';
                return $return;
            })
            ->editColumn('tanggal', function($data) {
                \Carbon\Carbon::setLocale('id');
                $return  = '<small>' . date('Y-m-d h:i:s', strtotime($data->created_at)) . '</small><br/>';
                $return .= str_replace('yang ', '', $data->created_at->diffForHumans());
                return $return;
            })
            ->editColumn('aksi', function($data) {
                $return  = '<div class="btn-toolbar">';
                $return .= '    <div class="btn-group btn-group-sm">';
                $return .= '        <a href="'. route("$this->prefix.edit", $data->uuid) .'" role="button" class="btn btn-sm btn-primary-outline">';
                $return .= '            <span class="fa fa-pencil"></span>';
                $return .= '        </a>';
                $return .= '    </div>';
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['pilihan', 'file', 'judul', 'tanggal', 'aksi'])->toJson();
    }
}
