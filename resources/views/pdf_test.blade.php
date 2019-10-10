<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style type="text/css">
  .title25 { width: 25%; }
  .title50 { width: 50%; }
  .left {  text-align: left; }
  .center {  text-align: center; }
  .right {  text-align: right; }
  .color_white { background-color: #fff; }
  .color_blue  { background-color: #99f; }
  .font_s { font-size: 8; }
  .font_m { font-size: 12; }
  table, th, td {
    border: none;
}
</style>
</head>
<body class="color_white">
  <table width="50%" cellpadding="10" cellspacing="10">
    <tr class="color_blue">
      <td class="title25 font_s left  ">@isset($test01) {{ $test01 }} @endisset</td>
      <td class="title50 font_m center">@isset($test02) {{ $test02 }} @endisset</td>
      <td class="title25 font_s right ">@isset($test03) {{ $test03 }} @endisset</td>
    </tr>
  </table>
  <table width="50%" cellpadding="10" cellspacing="10">
    <tr class="color_blue">
      <td class="title25 font_s left  ">@isset($test01) {{ $test01 }} @endisset</td>
      <td class="title50 font_m center">@isset($test02) {{ $test02 }} @endisset</td>
      <td class="title25 font_s right ">@isset($test03) {{ $test03 }} @endisset</td>
    </tr>
  </table>
  <div>
     <h2 class="title50 font_s center ">@isset($test04) {{ $test04 }} @endisset</h2>
      <div class="title25 font_s center ">@isset($test05) {{ $test05 }} @endisset</div>
  </div>
</body>
</html>