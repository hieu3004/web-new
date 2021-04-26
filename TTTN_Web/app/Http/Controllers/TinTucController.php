<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TinTuc;
use App\Models\LoaiTin;
use App\Models\TheLoai;
use Illuminate\Support\Str;
use App\Models\Comment;
class TinTucController extends Controller
{
    //
    public function getDanhSach(){
        $tintuc = TinTuc::orderBy('id','DESC')->get();
        return view('admin.tintuc.danhsach',['tintuc'=>$tintuc]);

    }
    public function getSua($id){
        $tintuc=TinTuc::find($id);   
        $theloai=TheLoai::all();
        $loatin=LoaiTin::all(); 
        return view('admin.tintuc.sua',['tintuc'=>$tintuc,'theloai'=>$theloai,'loaitin'=>$loatin]);
    }
    public function postSua(Request $request,$id){
        
        
        $tintuc=TinTuc::find($id);
      
        
        $tintuc->TieuDe=$request->TieuDe;
        $tintuc->idLoaiTin=$request->LoaiTin;
        $tintuc->MoTa=$request->MoTa;
        $tintuc->NoiDung=$request->NoiDung;
        $tintuc->SoLuotXem=0;
        $tintuc->NoiBat=$request->NoiBat;


            if($request->hasFile('Hinh')){
                $file = $request->file('Hinh');
                $name = $file->getClientOriginalName();
                $Hinh = Str::random(4)."_".$name;
                while(file_exists("tintuc/".$Hinh)){
                   
                    $Hinh = Str::random(4)."_".$name;
                }
                $file ->move("tintuc",$Hinh);
             
                unlink("tintuc/".$tintuc->Hinh);   $tintuc->Hinh = $Hinh;
            }
          

        $tintuc->save();
        return redirect ('admin/tintuc/sua/'.$id)->with('thongbao','Sửa Thành Công');
    }
    public function getThem(){
        $theloai=TheLoai::all();
        $loatin=LoaiTin::all();
        return view('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loatin]);

    }
    
    public function postThem(Request $request){
        $this->validate($request,[
            'LoaiTin'=>'required',
            'TieuDe'=>'required|min:3|unique:TinTuc,TieuDe',
            'MoTa'=>'required',
            'NoiDung'=>'required'
        ],
        [
            'LoaiTin.required'=>'Bạn chưa nhập loại tin',
            'TieuDe.required'=>'Bạn chưa nhập tiêu đề',
           
            'TieuDe.min'=>'Tên tiêu đề phải có độ dài 3->100 ký tự',
            'TieuDe.max'=>'Tên tiêu đề phải có độ dài 3->100 ký tự',
            'TieuDe.unique'=>'Tên đã tồn tại',
            'MoTa.required'=>'Bạn chưa nhập mô tả',
            'NoiDung.required'=>'Bạn chưa nhập nội dung'

        ]);
        $tintuc = new TinTuc;
        $tintuc->TieuDe=$request->TieuDe;
        $tintuc->idLoaiTin=$request->LoaiTin;
        $tintuc->MoTa=$request->MoTa;
        $tintuc->NoiDung=$request->NoiDung;
        $tintuc->SoLuotXem=0;
        $tintuc->NoiBat=$request->NoiBat;
        


            if($request->hasFile('Hinh')){
                $file = $request->file('Hinh');
                $name = $file->getClientOriginalName();
                $Hinh = Str::random(4)."_".$name;
                while(file_exists("tintuc/".$Hinh)){
                    $Hinh = Str::random(4)."_".$name;
                }
                $file->move("tintuc",$Hinh);
                $tintuc->Hinh = $Hinh;
            }
            else{
                $tintuc->Hinh="";
            }

        $tintuc->save();
        return redirect ('admin/tintuc/them')->with('thongbao','Thêm Thành Công');
    }
    public function getXoa($id){
        $tintuc= TinTuc::find($id);
        $tintuc->delete();
        return redirect('admin/tintuc/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
