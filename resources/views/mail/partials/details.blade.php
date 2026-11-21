{{--
  White card of label : value rows with a small icon (the "Inquiry ID / Name / Email ..." block of the design).
  @include('mail.partials.details', ['rows' => [['&#128100;', 'Name', $name], ...]])
  Empty values are skipped. Values are escaped; use 'html' => true in a row's 4th slot for trusted HTML.
--}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 8px;background:#ffffff;border:1px solid #f1e4d8;border-radius:12px;box-shadow:0 2px 10px rgba(242,107,29,.06);">
  <tr>
    <td style="padding:8px 18px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:'Nunito Sans',Arial,Helvetica,sans-serif;font-size:14px;">
        @foreach(array_values(array_filter($rows, function ($r) { return isset($r[2]) && $r[2] !== '' && $r[2] !== null; })) as $i => $row)
          @php $line = $i > 0 ? 'border-top:1px solid #f3ebe3;' : ''; @endphp
          <tr>
            <td width="30" valign="top" style="{{ $line }}padding:10px 0;font-size:16px;line-height:20px;color:#f26b1d;">{!! $row[0] !!}</td>
            <td width="110" valign="top" style="{{ $line }}padding:10px 8px 10px 0;line-height:20px;color:#4b4f56;">{{ $row[1] }}</td>
            <td width="12" valign="top" style="{{ $line }}padding:10px 0;line-height:20px;color:#4b4f56;">:</td>
            <td valign="top" style="{{ $line }}padding:10px 0;line-height:20px;color:#15171a;font-weight:700;white-space:pre-line;word-break:break-word;">@if(!empty($row[3])){!! $row[2] !!}@else{{ $row[2] }}@endif</td>
          </tr>
        @endforeach
      </table>
    </td>
  </tr>
</table>
