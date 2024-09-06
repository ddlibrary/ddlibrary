@extends('admin.layout')
@section('admin.content')
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-table"></i> Resource Analytics
                </div>
                <div class="card-body">
                    <div class="row">

                        {{-- Total resources by languages --}}
                        <div class="col-sm-12">
                            <div class="card border-secondary mb-3">

                                <style>
                                    th, td {
                                        border:1px solid gray !important;
                                        color: #000 !important;padding:1px !important;
                                    }
                                </style>
                                <div class="card-body text-secondary p-2" style="overflow-x: scroll">
                                    <table class="table" >
                                        <tr>
                                            <th class="text-center" colspan="6" rowspan="2"> Available DD Library Resources</th>
                                            <th class="text-center" colspan="19">Annual Resources Uploads</th>
                                            <th class="text-center" colspan="19">Users Profile																	</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" colspan="5"></th>
                                            <th class="text-center" colspan="12">2024</th>
                                            <th class="text-center" colspan="2"></th>
                                            <th class="text-center" colspan="5"></th>
                                            <th class="text-center" colspan="12">2024</th>
                                            <th class="text-center" colspan="2"></th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Subject</th>
                                            <th class="text-center">Language</th>
                                            <th class="text-center">#Of Subject</th>
                                            <th>Resource Categories</th>
                                            <th class="text-center">#Of Resource Categories</th>
                                            <th class="text-center">2020 < </th>
                                            <th class="text-center">2020</th>
                                            <th class="text-center">2021</th>
                                            <th class="text-center">2022</th>
                                            <th class="text-center">2023</th>
                                            <th class="text-center">Jan</th>
                                            <th class="text-center">Feb</th>
                                            <th class="text-center">Mar</th>
                                            <th class="text-center">Apr</th>
                                            <th class="text-center">May</th>
                                            <th class="text-center">Jun</th>
                                            <th class="text-center">Jul</th>
                                            <th class="text-center">Aug</th>
                                            <th class="text-center">Sep</th>
                                            <th class="text-center">Oct</th>
                                            <th class="text-center">Nov</th>
                                            <th class="text-center">Dec</th>
                                            <th class="text-center">2025</th>
                                            <th class="text-center">Total</th>

                                            <th class="text-center">2020 < </th>
                                            <th class="text-center">2020</th>
                                            <th class="text-center">2021</th>
                                            <th class="text-center">2022</th>
                                            <th class="text-center">2023</th>
                                            <th class="text-center">Jan</th>
                                            <th class="text-center">Feb</th>
                                            <th class="text-center">Mar</th>
                                            <th class="text-center">Apr</th>
                                            <th class="text-center">May</th>
                                            <th class="text-center">Jun</th>
                                            <th class="text-center">Jul</th>
                                            <th class="text-center">Aug</th>
                                            <th class="text-center">Sep</th>
                                            <th class="text-center">Oct</th>
                                            <th class="text-center">Nov</th>
                                            <th class="text-center">Dec</th>
                                            <th class="text-center">2025</th>
                                            <th class="text-center">Total</th>

                                        </tr>
                                        <?php $number = 1;$totalViews = 0; $totalResources = 0;$totalResourceInSubjectArea = 0; ?>
                                        @foreach ($subjectCategories as $categoryId => $category)
                                            @if (isset($category['resource_subject_areas']))
                                                <?php $total = 0; ?>
                                                @foreach ($category['resource_subject_areas'] as $subjectArea)
                                                    <tr>
                                                        <td class="text-center">{{ $number }}</td>
                                                        <?php $number++; ?>
                                                        <td>{{ $subjectArea['name'] }}</td>
                                                        <td class="text-center">{{ fixLanguage($subjectArea['language']) }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['total'] ?? 0 }}</td>
                                                        @if ($loop->first)
                                                        <td rowspan="{{$category['resource_subject_areas']->count()}}" style="vertical-align: middle" class="text-center">{{ $category['name'] }}</td>
                                                        <td rowspan="{{$category['resource_subject_areas']->count()}}" style="vertical-align: middle" class="text-center">{{ $category['total_resources'] }}</td>
                                                        <?php $totalResourceInSubjectArea += $category['total_resources']; ?>
                                                        @endif
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['before2020'] > 0 ? $subjectArea[$subjectArea->id]['before2020'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2020'] > 0 ? $subjectArea[$subjectArea->id]['year2020'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2021'] > 0 ? $subjectArea[$subjectArea->id]['year2021'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2022'] > 0 ? $subjectArea[$subjectArea->id]['year2022'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2023'] > 0 ? $subjectArea[$subjectArea->id]['year2023'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month1'] > 0 ? $subjectArea[$subjectArea->id]['month1'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month2'] > 0 ? $subjectArea[$subjectArea->id]['month2'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month3'] > 0 ? $subjectArea[$subjectArea->id]['month3'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month4'] > 0 ? $subjectArea[$subjectArea->id]['month4'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month5'] > 0 ? $subjectArea[$subjectArea->id]['month5'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month6'] > 0 ? $subjectArea[$subjectArea->id]['month6'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month7'] > 0 ? $subjectArea[$subjectArea->id]['month7'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month8'] > 0 ? $subjectArea[$subjectArea->id]['month8'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month9'] > 0 ? $subjectArea[$subjectArea->id]['month9'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month10'] > 0 ? $subjectArea[$subjectArea->id]['month10'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month11'] > 0 ? $subjectArea[$subjectArea->id]['month11'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month12'] > 0 ? $subjectArea[$subjectArea->id]['month12'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2024'] > 0 ? $subjectArea[$subjectArea->id]['year2024'] : '' }}</td>
                                                        <td class="text-center">
                                                            <?php
                                                            $resources = 
                                                            $subjectArea[$subjectArea->id]['before2020'] +
                                                            $subjectArea[$subjectArea->id]['year2020'] +
                                                            $subjectArea[$subjectArea->id]['year2021'] +
                                                            $subjectArea[$subjectArea->id]['year2022'] +
                                                            $subjectArea[$subjectArea->id]['year2023'] +
                                                            $subjectArea[$subjectArea->id]['month1'] +
                                                            $subjectArea[$subjectArea->id]['month2'] +
                                                            $subjectArea[$subjectArea->id]['month3'] +
                                                            $subjectArea[$subjectArea->id]['month4'] +
                                                            $subjectArea[$subjectArea->id]['month5'] +
                                                            $subjectArea[$subjectArea->id]['month6'] +
                                                            $subjectArea[$subjectArea->id]['month7'] +
                                                            $subjectArea[$subjectArea->id]['month8'] +
                                                            $subjectArea[$subjectArea->id]['month9'] +
                                                            $subjectArea[$subjectArea->id]['month10'] +
                                                            $subjectArea[$subjectArea->id]['month11'] +
                                                            $subjectArea[$subjectArea->id]['month12'] +
                                                            $subjectArea[$subjectArea->id]['year2024'];
                                                            echo $resources;
                                                            $totalResources += $resources;
                                                            ?>
                                                        </td>

                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['before2020Views'] > 0 ? $subjectArea[$subjectArea->id]['before2020Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2020Views'] > 0 ? $subjectArea[$subjectArea->id]['year2020Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2021Views'] > 0 ? $subjectArea[$subjectArea->id]['year2021Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2022Views'] > 0 ? $subjectArea[$subjectArea->id]['year2022Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2023Views'] > 0 ? $subjectArea[$subjectArea->id]['year2023Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month1Views'] > 0 ? $subjectArea[$subjectArea->id]['month1Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month2Views'] > 0 ? $subjectArea[$subjectArea->id]['month2Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month3Views'] > 0 ? $subjectArea[$subjectArea->id]['month3Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month4Views'] > 0 ? $subjectArea[$subjectArea->id]['month4Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month5Views'] > 0 ? $subjectArea[$subjectArea->id]['month5Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month6Views'] > 0 ? $subjectArea[$subjectArea->id]['month6Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month7Views'] > 0 ? $subjectArea[$subjectArea->id]['month7Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month8Views'] > 0 ? $subjectArea[$subjectArea->id]['month8Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month9Views'] > 0 ? $subjectArea[$subjectArea->id]['month9Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month10Views'] > 0 ? $subjectArea[$subjectArea->id]['month10Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month11Views'] > 0 ? $subjectArea[$subjectArea->id]['month11Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['month12Views'] > 0 ? $subjectArea[$subjectArea->id]['month12Views'] : '' }}</td>
                                                        <td class="text-center">{{ $subjectArea[$subjectArea->id]['year2024Views'] > 0 ? $subjectArea[$subjectArea->id]['year2024Views'] : '' }}</td>
                                                        <td class="text-center">
                                                            <?php 
                                                            $views = $subjectArea[$subjectArea->id]['before2020Views'] +
                                                            $subjectArea[$subjectArea->id]['year2020Views'] +
                                                            $subjectArea[$subjectArea->id]['year2021Views'] +
                                                            $subjectArea[$subjectArea->id]['year2022Views'] +
                                                            $subjectArea[$subjectArea->id]['year2023Views'] +
                                                            $subjectArea[$subjectArea->id]['month1Views'] +
                                                            $subjectArea[$subjectArea->id]['month2Views'] +
                                                            $subjectArea[$subjectArea->id]['month3Views'] +
                                                            $subjectArea[$subjectArea->id]['month4Views'] +
                                                            $subjectArea[$subjectArea->id]['month5Views'] +
                                                            $subjectArea[$subjectArea->id]['month6Views'] +
                                                            $subjectArea[$subjectArea->id]['month7Views'] +
                                                            $subjectArea[$subjectArea->id]['month8Views'] +
                                                            $subjectArea[$subjectArea->id]['month9Views'] +
                                                            $subjectArea[$subjectArea->id]['month10Views'] +
                                                            $subjectArea[$subjectArea->id]['month11Views'] +
                                                            $subjectArea[$subjectArea->id]['month12Views'] +
                                                            $subjectArea[$subjectArea->id]['year2024Views'];
                                                            $totalViews += $views;
                                                            echo $views;
                                                            ?>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <tr>
                                            <th class="text-center" colspan="5">Total</th>
                                            
                                            <th class="text-center">{{$totalResourceInSubjectArea }} </th>
                                            <th class="text-center">{{$before2020Total }} </th>
                                            <th class="text-center">{{$year2020Total }} </th>
                                            <th class="text-center">{{$year2021Total }} </th>
                                            <th class="text-center">{{$year2022Total }} </th>
                                            <th class="text-center">{{$year2023Total }} </th>
                                            
                                            <th class="text-center">{{$month1Total }} </th>
                                            <th class="text-center">{{$month2Total }} </th>
                                            <th class="text-center">{{$month3Total }} </th>
                                            <th class="text-center">{{$month4Total }} </th>
                                            <th class="text-center">{{$month5Total }} </th>
                                            <th class="text-center">{{$month6Total }} </th>
                                            <th class="text-center">{{$month7Total }} </th>
                                            <th class="text-center">{{$month8Total }} </th>
                                            <th class="text-center">{{$month9Total }} </th>
                                            <th class="text-center">{{$month10Total }} </th>
                                            <th class="text-center">{{$month11Total }} </th>
                                            <th class="text-center">{{$month12Total }} </th>
                                            <th class="text-center">{{$year2025Total }} </th>
                                            <th class="text-center"> {{$totalResources}}</th>
                                            
                                            <th class="text-center">{{$before2020ViewsTotal }} </th>
                                            <th class="text-center">{{$year2020ViewsTotal }} </th>
                                            <th class="text-center">{{$year2021ViewsTotal }} </th>
                                            <th class="text-center">{{$year2022ViewsTotal }} </th>
                                            <th class="text-center">{{$year2023ViewsTotal }} </th>
                                            <th class="text-center">{{$month1ViewsTotal }} </th>
                                            <th class="text-center">{{$month2ViewsTotal }} </th>
                                            <th class="text-center">{{$month3ViewsTotal }} </th>
                                            <th class="text-center">{{$month4ViewsTotal }} </th>
                                            <th class="text-center">{{$month5ViewsTotal }} </th>
                                            <th class="text-center">{{$month6ViewsTotal }} </th>
                                            <th class="text-center">{{$month7ViewsTotal }} </th>
                                            <th class="text-center">{{$month8ViewsTotal }} </th>
                                            <th class="text-center">{{$month9ViewsTotal }} </th>
                                            <th class="text-center">{{$month10ViewsTotal }} </th>
                                            <th class="text-center">{{$month11ViewsTotal }} </th>
                                            <th class="text-center">{{$month12ViewsTotal }} </th>
                                            <th class="text-center">{{$year2025ViewsTotal }} </th>
                                            <th class="text-center">{{ $totalViews}} </th>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
