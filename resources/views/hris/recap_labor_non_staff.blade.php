
<table border="1">
    <tr>
        <td></td>
    </tr>
      <tr>
        <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc" rowspan="2">No Dept</td>
        <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc" rowspan="2">Dept Name</td>
        <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc" rowspan="2">No Sub Dept</td>
        <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc" rowspan="2">Sub Dept Name</td>
        <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc" rowspan="2">Group</td>
        @foreach ($dateRange as $date_r)
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc" colspan="7">{{Carbon\Carbon::parse($date_r)->translatedFormat('l, j F Y')}}</td>
        @endforeach
      </tr>
      <tr>
        @foreach ($dateRange as $date_r)
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">No of MP</td>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">Working min</td>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">Wage</td>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">BPJS TK</td>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">BPJS KS</td>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">Accrual THR</td>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">Total</td>
        @endforeach
      </tr>
        <?php 
        $man=['man_power','working_min','bruto','bpjs_tk','bpjs_ks','thr','total'];
        ?>
      @foreach ($x as $v)
      <tr>
        <td style="border:1px solid black">{{$v['department_id']}}</td>
        <td style="border:1px solid black">{{$v['department_name']}}</td>
        <td style="border:1px solid black">{{$v['sub_dept_id']}}</td>
        <td style="border:1px solid black">{{$v['sub_dept_name']}}</td>
        <td style="border:1px solid black">{{$v['group_department']}}</td>
        @foreach ($dateRange as $date_r)
        @for ($i=0;$i<=6;$i++)
          <?php
            $value=$man[$i];
          ?>
          <td style="border:1px solid black">
            @if(isset($v['gaji']->where('tanggal_berjalan',$date_r)->first()[$value]))
              {{$v['gaji']->where('tanggal_berjalan',$date_r)->first()[$value]}}
            @else
              -
            @endif
          </td>
        @endfor
        @endforeach
      </tr>
      @endforeach
      <tr>
        <td colspan="5" style="font-weight:bold;border:1px solid black;background-color:#ffecdc">Total</td>
        @foreach ($dateRange as $date_r)
        @for ($i=0;$i<=6;$i++)
          <?php
            $value=$man[$i];
          ?>
          <td style="font-weight:bold;border:1px solid black;background-color:#ffecdc">
            {{$ab->where('tanggal_berjalan',$date_r)->first()[$value]}}
          </td>
        @endfor
        @endforeach
      </tr>
</table>