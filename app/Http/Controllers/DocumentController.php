<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use TCPDF_FONTS;
use App\User;
use Illuminate\Support\Facades\Auth; 

class DocumentController extends Controller
{
    public function downloadPdf(User $user)
    {

        $user = Auth::user();
        // ダミーデータ設定
        $data['test01'] = "01 - あいうえお - left";
        $data['test02'] = "02 - あいうえお - center";
        $data['test03'] = "03 - あいうえお - right";
        $data['test04'] = $user->name;
        $data['test05'] = $user->loginid;



        // PDF 生成メイン　－　A3 縦に設定 用紙の向き[P or Portrait(縦:既定) | L or Landscape(横)
        $pdf = new TCPDF("L", "mm", "A3", true, "UTF-8" );
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // PDF プロパティ設定
        $pdf->SetTitle('ユーザー情報');  // PDFドキュメントのタイトルを設定  http://tcpdf.penlabo.net/method/s/SetTitle.html
        $pdf->SetAuthor('Author aiueo あいうえお');  // PDFドキュメントの著者名を設定  http://tcpdf.penlabo.net/method/s/SetAuthor.html
        $pdf->SetSubject('Subject aiueo あいうえお');  // PDFドキュメントのサブジェクト(表題)を設定  http://tcpdf.penlabo.net/method/s/SetSubject.html
        $pdf->SetKeywords('KEY1 KEY2 KEY3 あいうえお'); // PDFドキュメントのキーワードを設定 http://tcpdf.penlabo.net/method/s/SetKeywords.html
        $pdf->SetCreator('Creator aiueo あいうえお');  // PDFドキュメントの製作者名を設定  http://tcpdf.penlabo.net/method/s/SetCreator.html

        // ヘッダー
        // $PDF_HEADER_LOGO = "storage/logs/3209654.png";//any image file. check correct path.
        // $PDF_HEADER_LOGO_WIDTH = "20";
        // $PDF_HEADER_TITLE = "This is my Title";
        // $PDF_HEADER_STRING = "Tel 1234567896 Fax 987654321\n"
        // . "E abc@gmail.com\n"
        // . "www.abc.com";
        // $pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

        // 余白セット
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        // 日本語フォント設定
        $pdf->setFont('kozminproregular','',10);

         // ページ追加
        $pdf->addPage();

        $pdf->resetColumns();
        $pdf->setEqualColumns(2, 180);
        $pdf->selectColumn();


        // HTMLを描画、viewの指定と変数代入 - pdf_test.blade.php
        $pdf->writeHTML(view("pdf_test", $data)->render());

        $pdf->resetColumns();




        // 出力指定 ファイル名、拡張子、D(ダウンロード)
        $pdf->output('test' . '.pdf', 'D');
        return;
   }
}