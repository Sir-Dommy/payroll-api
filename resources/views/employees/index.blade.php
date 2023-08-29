@extends('layouts.main_hr')
@section('xara_cbs')
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="card">
                        @if (Session::has('flash_message'))
                            <div class="alert alert-success">
                                {{ Session::get('flash_message') }}
                            </div>
                        @endif

                        @if (Session::has('delete_message'))

                            <div class="alert alert-danger">
                                {{ Session::get('delete_message') }}
                            </div>
                        @endif
                        <div class="card-header">
                            <h4>Active Employees</h4>
                            <div class="card-header-right">
                                <a class="dt-button btn-sm" href="{{ url('employees/create')}}">New Employee</a>
                                <button id="refresh" class="btn btn-sm btn-success">
                                    Refresh
                                </button>
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#importEmployees">
                                    Upload Employees
                                </button>
                                <a href="{{url('employee/template')}}" class="btn btn-sm btn-warning">
                                    Download Template
                                </a>
                                <div class="modal fade" id="importEmployees">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{url('employee/import')}}" method="post"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Import Employees</label>
                                                        <input type="file" class="form-control" name="file">
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button class="btn btn-sm btn-warning" data-dismiss="modal">
                                                        Not Now
                                                    </button>
                                                    <button type="submit" class="btn btn-sm btn-info">
                                                        Upload
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="card">
                                <div class="card-header">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <a class="active nav-link" href="#active" data-toggle="tab">Active
                                                Employees</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#probation" data-toggle="tab">On Probation</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div id="active" class="tab-pane active">
                                            <div class="dt-responsive table-responsive">
                                                <table id="order-table"
                                                       class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>PFN</th>
                                                        <th style="width:150px;">Employee Name</th>
                                                        <th>ID</th>
                                                        <th>Kra Pin</th>
                                                        <th>Nssf NO.</th>
                                                        <th>Nhif NO.</th>
                                                        <th>Gender</th>
                                                        <th>Employee Type</th>
                                                        <th>Branch</th>
                                                        <th>Department</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i = 1; ?>
                                                    @forelse($employees as $employee)
                                                        <tr>

                                                            <td> {{ $i }}</td>
                                                            <td>{{ $employee->personal_file_number }}</td>
                                                            @if($employee->middle_name == null || $employee->middle_name == '')
                                                                <td style="width: 150px;">{{ $employee->first_name.' '.$employee->last_name}}</td>
                                                            @else
                                                                <td style="width: 150px;">{{ $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}</td>
                                                            @endif
                                                            <td>{{ $employee->identity_number }}</td>
                                                            <td>{{ $employee->pin }}</td>
                                                            <td>{{ $employee->social_security_number }}</td>
                                                            <td>{{ $employee->hospital_insurance_number }}</td>
                                                            <td>{{ $employee->gender }}</td>
                                                            <td>
                                                                @php
                                                                    try{
                                                                        if($employee->employeeY->employee_type_name == 'Contract')
                                                                            {
                                                                                $start_date  = $employee->end_date;
                                                                                $today = (new \DateTime(today()));
                                                                                $end = (new \DateTime($start_date));
                                                                                $interval = $today->diff($end);
                                                                                echo $employee->employeeY->employee_type_name.' -- '.$interval->m. ' Months and '.$interval->d .' Days Remaining';
                                                                            }
                                                                        else{
                                                                            echo $employee->employeeY->employee_type_name;
                                                                        }
                                                                    }
                                                                    catch (\Exception $e)
                                                                    {}
                                                                @endphp
                                                            </td>
                                                            @if( $employee->branch_id!=0)
                                                                <td>{{ App\Models\Branch::getName($employee->branch_id) }}</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                            @if( $employee->department_id != 0)
                                                                <td>{{ App\Models\Department::getName($employee->department_id).' ('.App\Models\Department::getCode($employee->department_id).')'}}</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button"
                                                                            class="btn btn-info btn-sm dropdown-toggle"
                                                                            data-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                        Action <span class="caret"></span>
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">
                                                                        <li>
                                                                            <a href="{{url('employees/view/'.$employee->id)}}">View</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{url('employees/edit/'.$employee->id)}}">Update</a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="{{url('employees/deactivate/'.$employee->id)}}"
                                                                               onclick="return (confirm('Are you sure you want to deactivate this employee?'))">Deactivate</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                            <?php $i++; ?>
                                                    @empty
                                                        <tr>
                                                            <td colspan="11">
                                                                <center>
                                                                    <div
                                                                        class="flex flex-col items-center justify-center mt-16"
                                                                        style="">
                                                                        <div
                                                                            class="flex flex-col items-center justify-center">
                                                                            <svg width="125" height="110"
                                                                                 viewBox="0 0 125 110"
                                                                                 fill="#5851d8"
                                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                                 class="mt-5 mb-4">
                                                                                <g clip-path="url(#clip0)">
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M46.8031 84.4643C46.8031 88.8034 43.3104 92.3215 39.0026 92.3215C34.6948 92.3215 31.2021 88.8034 31.2021 84.4643C31.2021 80.1252 34.6948 76.6072 39.0026 76.6072C43.3104 76.6072 46.8031 80.1252 46.8031 84.4643Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M60.4536 110H64.3539V72.6785H60.4536V110Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M85.8055 76.6072H70.2045C69.1319 76.6072 68.2544 77.4911 68.2544 78.5715V82.5C68.2544 83.5804 69.1319 84.4643 70.2045 84.4643H85.8055C86.878 84.4643 87.7556 83.5804 87.7556 82.5V78.5715C87.7556 77.4911 86.878 76.6072 85.8055 76.6072ZM70.2045 82.5H85.8055V78.5715H70.2045V82.5Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M91.6556 1.96429C94.8811 1.96429 97.506 4.60821 97.506 7.85714V19.6429H83.8181L85.308 21.6071H99.4561V7.85714C99.4561 3.53571 95.9459 0 91.6556 0H33.152C28.8618 0 25.3516 3.53571 25.3516 7.85714V21.6071H39.3203L40.8745 19.6429H27.3017V7.85714C27.3017 4.60821 29.9265 1.96429 33.152 1.96429H91.6556Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M122.858 92.3213H117.007C115.935 92.3213 115.057 93.2052 115.057 94.2856V102.143C115.057 103.223 115.935 104.107 117.007 104.107H122.858C123.93 104.107 124.808 103.223 124.808 102.143V94.2856C124.808 93.2052 123.93 92.3213 122.858 92.3213ZM117.007 102.143H122.858V94.2856H117.007V102.143Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M103.356 43.2142V70.7142H21.4511V43.2142H26.1821V41.2498H19.501V72.6783H105.306V41.2498H98.3541L98.2839 43.2142H103.356Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M101.406 21.6071C104.632 21.6071 107.257 24.251 107.257 27.5V41.25H98.2257L98.0853 43.2142H109.207V27.5C109.207 23.1609 105.714 19.6428 101.406 19.6428H83.8182L85.0878 21.6071H101.406ZM40.8746 19.6428H23.4016C19.0937 19.6428 15.6011 23.1609 15.6011 27.5V43.2142H26.1961L26.3365 41.25H17.5512V27.5C17.5512 24.251 20.1761 21.6071 23.4016 21.6071H39.3204L40.8746 19.6428Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M62.4041 9.82153C45.1709 9.82153 31.2021 23.8917 31.2021 41.2501C31.2021 58.6085 45.1709 72.6787 62.4041 72.6787C79.6373 72.6787 93.606 58.6085 93.606 41.2501C93.606 23.8917 79.6373 9.82153 62.4041 9.82153ZM62.4041 11.7858C78.5335 11.7858 91.6559 25.0035 91.6559 41.2501C91.6559 57.4967 78.5335 70.7144 62.4041 70.7144C46.2746 70.7144 33.1523 57.4967 33.1523 41.2501C33.1523 25.0035 46.2746 11.7858 62.4041 11.7858Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M62.4041 19.6428C45.1709 19.6428 31.2021 23.8916 31.2021 41.25C31.2021 58.6084 45.1709 66.7857 62.4041 66.7857C79.6373 66.7857 93.606 58.6084 93.606 41.25C93.606 23.8916 79.6373 19.6428 62.4041 19.6428ZM62.4041 21.6071C82.6346 21.6071 91.6559 27.665 91.6559 41.25C91.6559 56.0096 80.7216 64.8214 62.4041 64.8214C44.0866 64.8214 33.1523 56.0096 33.1523 41.25C33.1523 27.665 42.1735 21.6071 62.4041 21.6071Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M101.406 70.7144H23.4014C10.478 70.7144 0 81.2685 0 94.2858V110H124.808V94.2858C124.808 81.2685 114.33 70.7144 101.406 70.7144ZM101.406 72.6786C113.234 72.6786 122.858 82.3724 122.858 94.2858V108.036H1.95012V94.2858C1.95012 82.3724 11.574 72.6786 23.4014 72.6786H101.406Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M33.152 33.3928H29.2518C27.0969 33.3928 25.3516 35.1509 25.3516 37.3214V45.1785C25.3516 47.3491 27.0969 49.1071 29.2518 49.1071H33.152V33.3928ZM31.2019 35.3571V47.1428H29.2518C28.1773 47.1428 27.3017 46.2609 27.3017 45.1785V37.3214C27.3017 36.2391 28.1773 35.3571 29.2518 35.3571H31.2019Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M95.556 33.3928H91.6558V49.1071H95.556C97.7109 49.1071 99.4562 47.3491 99.4562 45.1785V37.3214C99.4562 35.1509 97.7109 33.3928 95.556 33.3928ZM95.556 35.3571C96.6305 35.3571 97.5061 36.2391 97.5061 37.3214V45.1785C97.5061 46.2609 96.6305 47.1428 95.556 47.1428H93.6059V35.3571H95.556Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M94.581 15.7144C94.0447 15.7144 93.606 16.1563 93.606 16.6965V34.3751C93.606 34.9152 94.0447 35.3572 94.581 35.3572C95.1173 35.3572 95.5561 34.9152 95.5561 34.3751V16.6965C95.5561 16.1563 95.1173 15.7144 94.581 15.7144Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M38.0273 41.2499C37.4891 41.2499 37.0522 40.8099 37.0522 40.2678C37.0522 33.3142 44.1409 25.5356 53.6283 25.5356C54.1665 25.5356 54.6033 25.9756 54.6033 26.5178C54.6033 27.0599 54.1665 27.4999 53.6283 27.4999C45.2564 27.4999 39.0024 34.2414 39.0024 40.2678C39.0024 40.8099 38.5655 41.2499 38.0273 41.2499Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M97.5059 110H99.456V72.6785H97.5059V110Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M25.3516 110H27.3017V72.6785H25.3516V110Z"
                                                                                          class="fill-gray-600"></path>
                                                                                </g>
                                                                                <defs>
                                                                                    <clipPath id="clip0">
                                                                                        <rect width="124.808"
                                                                                              height="110"
                                                                                              fill="white"></rect>
                                                                                    </clipPath>
                                                                                </defs>
                                                                            </svg>
                                                                        </div>
                                                                        <div class="mt-2"><label class="font-medium">No
                                                                                Employees
                                                                                yet!</label></div>
                                                                        <div class="mt-2"><label class="text-gray-500">This
                                                                                section will
                                                                                contain the list of Employees.</label>
                                                                        </div>
                                                                        <div class="mt-6">
                                                                            <a href="{{url('employees/create')}}"
                                                                               class="btn btn-sm btn-success">
                                                                                Onboard Employees.
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>PFN</th>
                                                        <th style="width:150px;">Employee Name</th>
                                                        <th>ID</th>
                                                        <th>Kra Pin</th>
                                                        <th>Nssf NO.</th>
                                                        <th>Nhif NO.</th>
                                                        <th>Gender</th>
                                                        <th>Branch</th>
                                                        <th>Department</th>
                                                        <th></th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="probation" class="tab-pane">
                                            <div class="dt-responsive table-responsive">
                                                <table id="order-table"
                                                       class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>PFN</th>
                                                        <th style="width:150px;">Employee Name</th>
                                                        <th>ID</th>
                                                        <th>Kra Pin</th>
                                                        <th>Nssf NO.</th>
                                                        <th>Nhif NO.</th>
                                                        <th>Gender</th>
                                                        <th>Employee Type</th>
                                                        <th>Branch</th>
                                                        <th>Department</th>
                                                        <th></th>
                                                    </tr>

                                                    </thead>

                                                    <tbody>

                                                    <?php $i = 1; ?>
                                                    @forelse($probation as $employee)

                                                        <tr>

                                                            <td> {{ $i }}</td>
                                                            <td>{{ $employee->personal_file_number }}</td>
                                                            @if($employee->middle_name == null || $employee->middle_name == '')
                                                                <td style="width: 150px;">{{ $employee->first_name.' '.$employee->last_name}}</td>
                                                            @else
                                                                <td style="width: 150px;">{{ $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}</td>
                                                            @endif
                                                            <td>{{ $employee->identity_number }}</td>
                                                            <td>{{ $employee->pin }}</td>
                                                            <td>{{ $employee->social_security_number }}</td>
                                                            <td>{{ $employee->hospital_insurance_number }}</td>
                                                            <td>{{ $employee->gender }}</td>
                                                            <td>
                                                                    <?php
                                                                    $etype = DB::table('x_employee_type')->where('id', '=', $employee->type_id)->pluck('employee_type_name')->first();
                                                                    ?>
                                                                {{ $etype}}
                                                            </td>
                                                            @if( $employee->branch_id!=0)
                                                                <td>{{ App\Models\Branch::getName($employee->branch_id) }}</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                            @if( $employee->department_id != 0)
                                                                <td>{{ App\Models\Department::getName($employee->department_id).' ('.App\Models\Department::getCode($employee->department_id).')'}}</td>
                                                            @else
                                                                <td></td>
                                                            @endif
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button type="button"
                                                                            class="btn btn-info btn-sm dropdown-toggle"
                                                                            data-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                        Action <span class="caret"></span>
                                                                    </button>

                                                                    <ul class="dropdown-menu" role="menu">

                                                                        <li>
                                                                            <a href="{{url('employees/view/'.$employee->id)}}">View</a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="{{url('employees/edit/'.$employee->id)}}">Update</a>
                                                                        </li>
{{--                                                                        <li>--}}
{{--                                                                            <a href="{{url('employees/deactivate/'.$employee->id)}}"--}}
{{--                                                                               onclick="return (confirm('Are you sure you want to deactivate this employee?'))">Deactivate</a>--}}
{{--                                                                        </li>--}}
                                                                        <li>
                                                                            <a data-toggle="modal"
                                                                               data-target="#confirmEmployee{{$employee->id}}">
                                                                                Confirm Employee
                                                                            </a>
                                                                        </li>

                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade" id="confirmEmployee{{$employee->id}}">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form
                                                                        action="{{url('employee/confirm/'.$employee->id)}}"
                                                                        method="post">
                                                                        @csrf
                                                                        <div class="modal-body text-center">
                                                                            <img src="{{asset('images/print.gif')}}"
                                                                                 alt="gif">
                                                                        </div>
                                                                        <div
                                                                            class="modal-footer justify-content-center">
                                                                            <button type="submit" name="dismiss" value="N"
                                                                                    class="btn btn-sm btn-outline-warning btn-round">
                                                                                Exit
                                                                            </button>
                                                                            <button type="submit" name="confirm" value="Y"
                                                                                    class="btn btn-sm btn-outline-success btn-round">
                                                                                Confirm
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <?php $i++; ?>
                                                    @empty
                                                        <tr>
                                                            <td colspan="11">
                                                                <center>
                                                                    <div
                                                                        class="flex flex-col items-center justify-center mt-16"
                                                                        style="">
                                                                        <div
                                                                            class="flex flex-col items-center justify-center">
                                                                            <svg width="125" height="110"
                                                                                 viewBox="0 0 125 110"
                                                                                 fill="#5851d8"
                                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                                 class="mt-5 mb-4">
                                                                                <g clip-path="url(#clip0)">
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M46.8031 84.4643C46.8031 88.8034 43.3104 92.3215 39.0026 92.3215C34.6948 92.3215 31.2021 88.8034 31.2021 84.4643C31.2021 80.1252 34.6948 76.6072 39.0026 76.6072C43.3104 76.6072 46.8031 80.1252 46.8031 84.4643Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M60.4536 110H64.3539V72.6785H60.4536V110Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M85.8055 76.6072H70.2045C69.1319 76.6072 68.2544 77.4911 68.2544 78.5715V82.5C68.2544 83.5804 69.1319 84.4643 70.2045 84.4643H85.8055C86.878 84.4643 87.7556 83.5804 87.7556 82.5V78.5715C87.7556 77.4911 86.878 76.6072 85.8055 76.6072ZM70.2045 82.5H85.8055V78.5715H70.2045V82.5Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M91.6556 1.96429C94.8811 1.96429 97.506 4.60821 97.506 7.85714V19.6429H83.8181L85.308 21.6071H99.4561V7.85714C99.4561 3.53571 95.9459 0 91.6556 0H33.152C28.8618 0 25.3516 3.53571 25.3516 7.85714V21.6071H39.3203L40.8745 19.6429H27.3017V7.85714C27.3017 4.60821 29.9265 1.96429 33.152 1.96429H91.6556Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M122.858 92.3213H117.007C115.935 92.3213 115.057 93.2052 115.057 94.2856V102.143C115.057 103.223 115.935 104.107 117.007 104.107H122.858C123.93 104.107 124.808 103.223 124.808 102.143V94.2856C124.808 93.2052 123.93 92.3213 122.858 92.3213ZM117.007 102.143H122.858V94.2856H117.007V102.143Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M103.356 43.2142V70.7142H21.4511V43.2142H26.1821V41.2498H19.501V72.6783H105.306V41.2498H98.3541L98.2839 43.2142H103.356Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M101.406 21.6071C104.632 21.6071 107.257 24.251 107.257 27.5V41.25H98.2257L98.0853 43.2142H109.207V27.5C109.207 23.1609 105.714 19.6428 101.406 19.6428H83.8182L85.0878 21.6071H101.406ZM40.8746 19.6428H23.4016C19.0937 19.6428 15.6011 23.1609 15.6011 27.5V43.2142H26.1961L26.3365 41.25H17.5512V27.5C17.5512 24.251 20.1761 21.6071 23.4016 21.6071H39.3204L40.8746 19.6428Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M62.4041 9.82153C45.1709 9.82153 31.2021 23.8917 31.2021 41.2501C31.2021 58.6085 45.1709 72.6787 62.4041 72.6787C79.6373 72.6787 93.606 58.6085 93.606 41.2501C93.606 23.8917 79.6373 9.82153 62.4041 9.82153ZM62.4041 11.7858C78.5335 11.7858 91.6559 25.0035 91.6559 41.2501C91.6559 57.4967 78.5335 70.7144 62.4041 70.7144C46.2746 70.7144 33.1523 57.4967 33.1523 41.2501C33.1523 25.0035 46.2746 11.7858 62.4041 11.7858Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M62.4041 19.6428C45.1709 19.6428 31.2021 23.8916 31.2021 41.25C31.2021 58.6084 45.1709 66.7857 62.4041 66.7857C79.6373 66.7857 93.606 58.6084 93.606 41.25C93.606 23.8916 79.6373 19.6428 62.4041 19.6428ZM62.4041 21.6071C82.6346 21.6071 91.6559 27.665 91.6559 41.25C91.6559 56.0096 80.7216 64.8214 62.4041 64.8214C44.0866 64.8214 33.1523 56.0096 33.1523 41.25C33.1523 27.665 42.1735 21.6071 62.4041 21.6071Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M101.406 70.7144H23.4014C10.478 70.7144 0 81.2685 0 94.2858V110H124.808V94.2858C124.808 81.2685 114.33 70.7144 101.406 70.7144ZM101.406 72.6786C113.234 72.6786 122.858 82.3724 122.858 94.2858V108.036H1.95012V94.2858C1.95012 82.3724 11.574 72.6786 23.4014 72.6786H101.406Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M33.152 33.3928H29.2518C27.0969 33.3928 25.3516 35.1509 25.3516 37.3214V45.1785C25.3516 47.3491 27.0969 49.1071 29.2518 49.1071H33.152V33.3928ZM31.2019 35.3571V47.1428H29.2518C28.1773 47.1428 27.3017 46.2609 27.3017 45.1785V37.3214C27.3017 36.2391 28.1773 35.3571 29.2518 35.3571H31.2019Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M95.556 33.3928H91.6558V49.1071H95.556C97.7109 49.1071 99.4562 47.3491 99.4562 45.1785V37.3214C99.4562 35.1509 97.7109 33.3928 95.556 33.3928ZM95.556 35.3571C96.6305 35.3571 97.5061 36.2391 97.5061 37.3214V45.1785C97.5061 46.2609 96.6305 47.1428 95.556 47.1428H93.6059V35.3571H95.556Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M94.581 15.7144C94.0447 15.7144 93.606 16.1563 93.606 16.6965V34.3751C93.606 34.9152 94.0447 35.3572 94.581 35.3572C95.1173 35.3572 95.5561 34.9152 95.5561 34.3751V16.6965C95.5561 16.1563 95.1173 15.7144 94.581 15.7144Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M38.0273 41.2499C37.4891 41.2499 37.0522 40.8099 37.0522 40.2678C37.0522 33.3142 44.1409 25.5356 53.6283 25.5356C54.1665 25.5356 54.6033 25.9756 54.6033 26.5178C54.6033 27.0599 54.1665 27.4999 53.6283 27.4999C45.2564 27.4999 39.0024 34.2414 39.0024 40.2678C39.0024 40.8099 38.5655 41.2499 38.0273 41.2499Z"
                                                                                          class="fill-primary-500"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M97.5059 110H99.456V72.6785H97.5059V110Z"
                                                                                          class="fill-gray-600"></path>
                                                                                    <path fill-rule="evenodd"
                                                                                          clip-rule="evenodd"
                                                                                          d="M25.3516 110H27.3017V72.6785H25.3516V110Z"
                                                                                          class="fill-gray-600"></path>
                                                                                </g>
                                                                                <defs>
                                                                                    <clipPath id="clip0">
                                                                                        <rect width="124.808"
                                                                                              height="110"
                                                                                              fill="white"></rect>
                                                                                    </clipPath>
                                                                                </defs>
                                                                            </svg>
                                                                        </div>
                                                                        <div class="mt-2"><label class="font-medium">No
                                                                                Employees
                                                                                yet!</label></div>
                                                                        <div class="mt-2"><label class="text-gray-500">This
                                                                                section will
                                                                                contain the list of Employees.</label>
                                                                        </div>
                                                                        <div class="mt-6">
                                                                            <a href="{{url('employees/create')}}"
                                                                               class="btn btn-sm btn-success">
                                                                                Onboard Employees.
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </center>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>PFN</th>
                                                        <th style="width:150px;">Employee Name</th>
                                                        <th>ID</th>
                                                        <th>Kra Pin</th>
                                                        <th>Nssf NO.</th>
                                                        <th>Nhif NO.</th>
                                                        <th>Gender</th>
                                                        <th>Branch</th>
                                                        <th>Department</th>
                                                        <th></th>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="processing" style="display: none">
                            <img src="{{asset('images/loader.gif')}}" style="height: 50px; width: 50px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('media/jquery-1.8.0.min.js')}}"></script>
    <script>
        let apiRequest = new XMLHttpRequest();
        document.getElementById('refresh').addEventListener('click', (event) => {
            event.preventDefault();
            apiRequest.open('GET', 'http://127.0.0.1/oarizon/public/v1/employees')
            apiRequest.send();
        });
        apiRequest.onreadystatechange = () => {
            if (apiRequest.readyState === 4) {
                if (apiRequest.status === 404) {
                    console.log('No Data')
                } else {
                    console.log(JSON.parse(apiRequest.response))
                    $('#processing').hide()
                }
            } else {
                $('#processing').show()
            }
        }
    </script>
@stop
