<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoaiTin;
use App\Models\TheLoai;
use App\Models\TinTuc;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
class PageController extends Controller
{
    //
    function __construct(){
        $theloai= TheLoai::all();
        view()->share('theloai',$theloai);
        // if(Auth::check()==false)
        // {
        //     $nguoidung = Auth::User();
        //     view()->share('nguoidung',$nguoidung);
        // }
    }
    function trangchu()
    {
       
        return view ('pages.trangchu');
    }
    function lienhe()
    {
      
        return view ('pages.lienhe');
    }
    function loaitin($id)
    {
        $loaitin= LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
        return view ('pages.loaitin',['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }
    function tintuc($id)
    {
        $tintuc= TinTuc::find($id);
        $tinnoibat = TinTuc::where('NoiBat',1)->take(4)->get();
        $tinlienquan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
        return view ('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }
    function getDangnhap()
    {
        return view ('pages.dangnhap');
    }
    function postDangnhap(Request $request)
    {
        $this->validate($request,[
            
            'email'=>'required',
            'password'=>'required|min:3|max:32',    
           ],
           [          
               'email.required'=>'Bạn chưa nhập emial',      
               'password.required'=>'Bạn chưa nhập mật khẩu',
               'password.min'=>'Mật khẩu quá ngắn',
               'password.max'=>'Mật khẩu quá dài',
           ]);
           if(Auth::attempt(['email'=>$request->email,'password' =>$request->password])){
               return redirect('trangchu');
           }else{
               return redirect('dangnhap')->with('thongbao','Đăng nhập không thành công');
           }
    }
    function getDangxuat()
    {
        Auth::logout();
        return redirect ('trangchu');
    }
    function getDangky()
    {
      
        return view ('pages.dangky');
    }
    function postDangky(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:3|max:32',
            'passwordAgain'=>'required|same:password'
        ],
        [
            'name.required'=>'Bạn chưa nhập tên người dùng',
            'name.min'=>'Tên người dùng phảo ít nhất 3 ký tự',
           
            'email.required'=>'Bạn chưa nhập emial',
            'email.email'=>'Sai định dạng',
            
            'email.required'=>'Email đã tồn tại',
            'password.required'=>'Bạn chưa nhập mật khẩu',
            'password.min'=>'Mật khẩu quá ngắn',
            'password.max'=>'Mật khẩu quá dài',
            'passwordAgain.required'=>'Nhập lại mật khẩu',
            'passwordAgain.same'=>'Chưa khớp mật khẩu'

        ]);
        $user = new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->quyen=0;
    

        $user->save();
        return redirect ('dangnhap')->with('thongbao','Đăng ký Thành Công');
       
    }
}
