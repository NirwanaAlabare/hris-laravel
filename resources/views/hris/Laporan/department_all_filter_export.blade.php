<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head>

</head>
    <body>
        <table>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-weight:bold; font-size:13pt; text-decoration:underline">PT. NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <td colspan="5" style="font-family: Arial Nova; font-size:11pt;">All Department</td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="5" style="border: 1px solid dark;background-color:yellow;font-weight:bold">No</td>
                <td width="14" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Site Nirwana Id</td>
                <td width="31" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Site Nirwana Name</td>
                <td width="14" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Department Id</td>
                <td width="28" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Department Name</td>
                <td width="18" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Sub Department Id</td>
                <td width="28" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Sub Department Name</td>
                <td width="28" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Kategori Department</td>
                <td width="28" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Kategori Penempatan</td>
                <td width="16" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Jumlah Karyawan</td>
                <td width="14" style="border: 1px solid dark;background-color:yellow;font-weight:bold">Status</td>
            </tr>
            @foreach ($query as $k=>$q)
              <tr>
                <td style="border: 1px solid dark">{{$k+1}}</td>
                <td style="border: 1px solid dark">{{$q->site_nirwana_id}}</td>
                <td style="border: 1px solid dark">{{$q->site_nirwana_name}}</td>
                <td style="border: 1px solid dark">{{$q->department_id}}</td>
                <td style="border: 1px solid dark">{{$q->department_name}}</td>
                <td style="border: 1px solid dark">{{$q->sub_dept_id}}</td>
                <td style="border: 1px solid dark">{{$q->sub_dept_name}}</td>
                <td style="border: 1px solid dark">{{$q->group_1}}</td>
                <td style="border: 1px solid dark">{{$q->group_2}}</td>
                <td style="border: 1px solid dark">{{App\Models\EmployeeAtribut::where('site_nirwana_id',$q->site_nirwana_id)->where('department_id',$q->department_id)->where('sub_dept_id',$q->sub_dept_id)->count()}}</td>
                <td style="border: 1px solid dark">{{$q->status}}</td>
              </tr>
            @endforeach
        </table>
    </body>
</html>