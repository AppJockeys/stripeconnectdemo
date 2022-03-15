@extends('layouts.front.app')
@if(isset($page_title) && $page_title!='')
    @section('title', $page_title.' - '.config('app.name'))
@else
    @section('title', config('app.name'))
@endif
    @push('before-styles')
    @endpush
    @push('after-styles')
    @endpush
@section('page-style')
    <link href="{{ asset('front/css/bootstrap-material-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .bchng {
            float: unset;
            margin-left: 5px;
            vertical-align: middle;
            height: 30px;
            display: inline-block;
        }
        .bchng i{
            font-size: 16px;
        }
        .modal.mdl-bgnone{
            background-image:none !important;
        }
        .modal-dialog.jb_wd1{
            max-width: 550px !important;
        }
        #changePasswordModel .model_forms.jb_md, #profileEditModel .model_forms.jb_md {
            min-height: auto;
        }
        .dtp{
            z-index: 999999 !important;
        }
        .pac-container {
            z-index: 9999999;
        }
        .my-rating .fa-star-o, .my-view-rating .fa-star-o{
            font-size:30px;
            cursor: pointer;
        }
        .my-rating .checked, .my-view-rating .checked {
          color: #fde16d;
          font-size: 30px;
        }

        .jb_md .row .col-md-3 {
            margin-bottom: 30px;
        }
        .job_content .profile_content p {
            color: #2b62ad;
            font-weight: bold;
        }
        .job_content h6 {
            color: #717d8e;
        }
        .modal-header.rate_modl .close {
            position: absolute;
            right: 20px;
            top: 15px;
        }
        .modal-header.rate_modl {
            position: relative;
            display: block;
        }
        .btn.btn_rating.btn-blue {
            padding: 5px;
            font-size: 10px;
        }
        .btn.view_rating.btn-blue {
            padding: 5px;
            font-size: 10px;
        }
        .modal-dialog.jb_wd1 {
            overflow: hidden;
        }

        .mdl-fx-hgt.modal-body.m-0.p-0 {
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
@endsection
@section('content')
    <section id="banner">
        <div class="banner-block profile">
            <div class="container">
                <div class="banner-box">
                    <h1 class="wow animated fadeInUp">Dashboard</h1>
                </div>
            </div>
        </div>
    </section>
    <section id="profile">
        <div class="profile-block">
            <div class="container">
                <div role="alert" id="pro-img-alert-div" style="display: none;margin-top: 10px;">
                    <span></span>
                </div>
                <div class="profile-box box-shdbg">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <?php /*
                            <div class="pro_img wow animated fadeInUp">
                                <img src="{{ (isset($user_data->profile_photo_path) && $user_data->profile_photo_path!='' && \File::exists(public_path('uploads/users/photo/'.$user_data->profile_photo_path)))?asset('uploads/users/photo/'.$user_data->profile_photo_path):asset('images/default-user.png') }}">
                                <!-- <div class="pro_img_box">
                                    <input type="file" name="profile_photo_path" id="pro_img">
                                    <i class="fa fa-camera"></i>
                                </div> -->
                            </div>
                            */ ?>
                            <form action="{{ route('users.change.profile.photo') }}" id="changeProfilePhotoForm" name="changeProfilePhotoForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="pro_img wow animated fadeInUp">
                                    <img src="{{ (isset($user_data->profile_photo_path) && $user_data->profile_photo_path!='' && \File::exists(public_path('uploads/users/photo/'.$user_data->profile_photo_path)))?asset('uploads/users/photo/'.$user_data->profile_photo_path):asset('images/default-user.png') }}" id="pro_img_src">
                                    <div class="pro_img_box">
                                        <input type="file" name="profile_photo_path" id="pro_img">
                                        <i class="fa fa-camera"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-10">
                            <div class="profile_content_box">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="profile_content wow animated fadeInUp">
                                            <p>First Name</p>
                                            <h4>{{(isset($user_data->first_name) ? $user_data->first_name :'')}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="profile_content wow animated fadeInUp">
                                            <p>Last Name</p>
                                            <h4>{{(isset($user_data->last_name) ? $user_data->last_name :'')}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="profile_content wow animated fadeInUp">
                                            <p>Email</p>
                                            <h4 style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="{{(isset($user_data->email) ? $user_data->email :'')}}">{{(isset($user_data->email) ? $user_data->email :'')}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="profile_content wow animated fadeInUp">
                                            <p>Phone</p>
                                            <h4>{{(isset($user_data->phone) ? $user_data->phone :'')}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="profile_content wow animated fadeInUp">
                                            <p>Total Bids</p>
                                            <h4>{{ ($tot_bid)?$tot_bid:'0' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="editprofile wow animated fadeInUps">
                                    <a href="javascript:void(0);" class="btn-warning btn-sm bchng" data-toggle="modal" data-target="#changePasswordModel" title="Change Password" style="background-color: #fdad00;color: #fff;"><i class="fa fa-key"></i></a>
                                </div>
                                <div class="editprofile wow animated fadeInUps">
                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#profileEditModel" title="Edit Profile"><img src="{{ asset('front/images/edit.png') }}"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile_tabbings box-shdbg">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="tabbing_nav">
                                <ul class="nav nav-tabs" role="tablist" id="tabul">
                                    <li class="nav-item">
                                        <a class="nav-link wow animated fadeInUp" data-toggle="tab" href="#MyBids" data-type="bids"><span><i class="fa fa-gavel"></i></span>My Bids</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link wow animated fadeInUp" data-toggle="tab" href="#MyJobs" data-type="jobs"><span><i class="fa fa-list-alt"></i></span>My Jobs</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link wow animated fadeInUp" data-toggle="tab" href="#MyPosts" data-type="posts"><span><i class="fa fa-outdent"></i></span>My Posts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link wow animated fadeInUp" data-toggle="tab" href="#Payments" data-type="payments"><span><i class="fa fa-credit-card"></i></span>Payments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link wow animated fadeInUp" data-toggle="tab" href="#MyPlans" data-type="MyPlans"><span><i class="fa fa-tasks"></i></span>My Plans</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link wow animated fadeInUp" data-toggle="tab" href="#BankDetails" data-type="BankDetails"><span><i class="fa fa-bank"></i></span>Bank Details</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tabbing_content">
                                <div class="tab-content">
                                    <div id="MyBids" class="tab-pane"></div>
                                    <div id="MyJobs" class="tab-pane"></div>
                                    <div id="MyPosts" class="tab-pane"></div>
                                    <div id="Payments" class="tab-pane"></div>
                                    <div id="MyPlans" class="tab-pane">
                                        <div class="model_forms jb_md job_content p-3">
                                            @if(isset($current_plan) && !empty($current_plan))
                                            <h4>My Plan Details</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Current Planss</p>
                                                        <h6>{{(isset($current_plan->plan->name) ? $current_plan->plan->name :'')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Plan Amount</p>
                                                        <h6>{{(isset($current_plan->plan->offer_price) ? '$'.$current_plan->plan->offer_price :'')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Purchase Date</p>
                                                        <h6>{{(isset($current_plan->created_at) ? date("Y-m-d H:i:s", strtotime($current_plan->created_at)) :'-')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Renual Date</p>
                                                        <?php 
                                                            $pur_date   = $current_plan->ends_at;
                                                            $afterdate  = strtotime("+1 day", strtotime($pur_date));
                                                         ?>
                                                        <h6>{{($afterdate ? date("Y-m-d H:i:s", strtotime($pur_date)) :'-')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Total Bid</p>
                                                        <h6>{{(isset($current_plan->total_bid) ? $current_plan->total_bid :'0')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Used Bid</p>
                                                        <h6>{{(isset($own_bid['tot_used_bid']) ? $own_bid['tot_used_bid'] :'0')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <p>Remaining Bid</p>
                                                        <h6>{{(isset($own_bid['tot_rem_bid']) ? $own_bid['tot_rem_bid'] :'0')}}</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="profile_content wow animated fadeInUp">
                                                        <a href="{{ route('plans') }}" class="btn btn-blue" title="Upgrade Plan">Upgrade Plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                                <div class="alert alert-danger">
                                                    You don't have any <a href="{{ route('plans') }}">plan </a> yet.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="BankDetails" class="tab-pane">
                                        <div class="model_forms jb_md p-3">
                                            @if(!(
                                                (isset($user_data->dob) && $user_data->dob!='') &&
                                                (isset($user_data->country_id) && $user_data->country_id!='') &&
                                                (isset($user_data->state_id) && $user_data->state_id!='') &&
                                                (isset($user_data->city_id) && $user_data->city_id!='') &&
                                                (isset($user_data->address) && $user_data->address!='') &&
                                                (isset($user_data->address_line_2) && $user_data->address_line_2!='') &&
                                                (isset($user_data->pin_code) && $user_data->pin_code!='')
                                            ))
                                                <div class="alert alert-danger">
                                                    You don't have any bank account. Please complete <a href="javascript:void(0);" data-toggle="modal" data-target="#profileEditModel">profile</a> to add bank account.
                                                </div>
                                            @else
                                                <h4 class="bank_detail_title">Add Bank Details</h4>
                                                <div role="alert" id="bank-details-alert-div" style="display: none;">
                                                    <span></span>
                                                </div>
                                                <form action="{{ route('profile.add_details') }}" name="addDetailsForm" id="addDetailsForm" method="POST" autocomplete="off">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <select class="custom-select" name="account_holder_type" id="account_holder_type">
                                                                    <option value="">Account holder type</option>
                                                                    <option value="individual">Individual</option>
                                                                    <option value="company">Company</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <input class="form-control" type="text" name="routing_number" id="routing_number" placeholder="Routing number">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <input class="form-control" type="text" name="account_number" id="account_number" placeholder="Account number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="button_box jb_snbt mb_top text-center" style="margin: 0;">
                                                        <input type="submit" class="btn btn-yellow" value="Submit" style="margin: 20px 0;">
                                                    </div>
                                                </form>
                                                <hr>
                                                @if(!empty($bank_accounts))
                                                <table class="table table-striped">
                                                    @foreach ($bank_accounts as $bank_account)
                                                        <tr>
                                                            <td>
                                                                <p class="myjb-content">Routing number</p>
                                                                <p class="myjb-title routing_number">{{ $bank_account->routing_number }}</p>
                                                                <p class="run-status account_holder_type"><span>{{ $bank_account->account_holder_type }}</span></p>
                                                            </td>
                                                            <td>
                                                                <p class="myjb-content">Account number</p>
                                                                <p class="myjb-title account_number">{{ $bank_account->account_number }}</p>
                                                            </td>
                                                            <td>
                                                                <div class="buttons_boxss" style="width: 60px;">
                                                                    <?php /*
                                                                    <button class="btn btn-round rounded-circle btn-primary" data-id="{{ $bank_account->bank_account_id }}" id="edit_bank_account"><i class="fa fa-pencil ml-0"></i></button>
                                                                    */ ?>
                                                                    <button class="btn btn-round rounded-circle btn-danger" data-id="{{ $bank_account->bank_account_id }}" id="delete_bank_account"><i class="fa fa-trash ml-0"></i></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Change password Modal -->
    <div class="modal fade mdl-bgnone m-0 p-0" id="changePasswordModel" tabindex="-1" role="dialog" aria-labelledby="changePasswordModelLabel"
        aria-hidden="true">
        <div class="modal-dialog jb_wd1" role="document">
            <div class="modal-content m-0 p-0">
                <div class="modal-header">
                    <h4>Change Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-0 p-0">
                    <div class="model_box">
                        <div class="row m-0 p-0">
                            <div class="col-md-12 m-0 p-0 custom-cols jb_cus3">
                                <div class="right_box">
                                    <div class="model_forms jb_md">
                                        <!-- <h4>Change Password</h4> -->
                                        <div role="alert" id="cp-alert-div" style="display: none;">
                                            <span></span>
                                        </div>
                                        <form action="{{ route('profile.password.update') }}" name="chngpwdFrm" id="chngpwdFrm" method="POST" autocomplete="off">
                                            @csrf
                                            @php
                                                $is_social                          = 0;
                                                $is_current_password_style          = '';
                                                $is_current_password_required       = 'required';
                                            @endphp
                                            @if($user_data->is_social_login == 1 && \Hash::check('admin1234', $user_data->password))
                                                @php
                                                    $is_social                      = 1;
                                                    $is_current_password_style      = 'd-none';
                                                    $is_current_password_required   = '';
                                                @endphp
                                            @endif
                                            <input type="hidden" name="is_social" value="{{ $is_social }}">
                                            <div class="row">
                                                <div class="col-md-12 {{ $is_current_password_style }}">
                                                    <div class="form-group">
                                                        <input type="password" class="form-control" name="current_password" placeholder="Current Password" {{ $is_current_password_required }}>
                                                        <!-- <i class="fa fa-eye" aria-hidden="true"></i> -->
                                                        <span toggle="#input-pwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="password" class="form-control" name="password" placeholder="New Password" required>
                                                        <!-- <i class="fa fa-eye" aria-hidden="true"></i> -->
                                                        <span toggle="#input-pwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                                                        <!-- <i class="fa fa-eye" aria-hidden="true"></i> -->
                                                        <span toggle="#input-pwd" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="button_box jb_snbt mb_top" style="margin: 0;">
                                                <input type="submit" class="btn btn-yellow" value="Submit" style="margin: 20px 0;">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Change password Modal -->
    <!-- Profile Edit Modal -->
    <div class="modal fade mdl-bgnone m-0 p-0 ss" id="profileEditModel" tabindex="-1" role="dialog" aria-labelledby="profileEditModelLabel"
        aria-hidden="true">
        <div class="modal-dialog jb_wd1" role="document">
            <div class="modal-content m-0 p-0">
                <div class="modal-header">
                    <h4>Edit Profile</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-0 p-0 mdl-fx-hgt">
                    <div class="model_box">
                        <div class="row m-0 p-0">
                            <div class="col-md-12 m-0 p-0 custom-cols jb_cus3">
                                <div class="right_box">
                                    <div class="model_forms jb_md">
                                        <div role="alert" id="profile-edit-alert-div" style="display: none;">
                                            <span></span>
                                        </div>
                                        <div id="profile-edit-form" style="display: none;">
                                            <form action="{{ route('profile.update') }}" id="profileEditForm" name="profileEditForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="user_id" id="user_id" value="{{ isset($user_data->id) ? $user_data->id : '' }}">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" name="email" placeholder="Email" maxlength="255" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" name="first_name" placeholder="First Name" maxlength="255">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" maxlength="255">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <select class="custom-select" name="country_code">
                                                            <option value="">Code</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" name="phone" placeholder="Phone Number" maxlength="10" onkeypress="return isNumber(event);" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" name="dob" placeholder="DOB">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <select class="custom-select" name="country_id">
                                                            <option value="">Country</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <select class="custom-select" name="state_id">
                                                            <option value="">State</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <select class="custom-select" name="city_id">
                                                            <option value="">City</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" name="address" placeholder="Address Line 1">
                                                        <input type="hidden" name="latitude">
                                                        <input type="hidden" name="longitude">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <textarea class="form-control" name="address_line_2" placeholder="Address Line 2" rows="3"></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" name="pin_code" placeholder="Pin Code" maxlength="255">
                                                    </div>
                                                </div>
                                                <div class="button_box jb_snbt mb_top">
                                                    <input type="submit" class="btn btn-yellow" value="Update" style="margin: 20px 0;">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Edit Modal -->
    <!-- Give Rating Modal -->
    <div class="modal fade mdl-bgnone m-0 p-0" id="giveRatingModel" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog jb_wd1" role="document">
            <div class="modal-content m-0 p-0">
                <div class="modal-header rate_modl">
                    <h4 class="text-center">Rate Now</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-0 p-0">
                    <div class="model_box">
                        <div class="row m-0 p-0">
                            <div class="col-md-12 m-0 p-0 custom-cols jb_cus3">
                                <div class="right_box">
                                    <div class="model_forms jb_md" style="min-height: unset;">
                                        <!-- <h4>Change Password</h4> -->
                                        <div role="alert" id="cp-alert-div" style="display: none;">
                                            <span></span>
                                        </div>
                                        <form action="{{ route('job.review.save') }}" id="form_rating" method="post">
                                            @csrf
                                            
                                            <div class="form-group my-rating text-center">
                                                <!-- <label>Rate Now Out Of 5</label>
                                                <br> -->
                                                <span class="fa fa-star-o rating-span"></span>
                                                <span class="fa fa-star-o rating-span"></span>
                                                <span class="fa fa-star-o rating-span"></span>
                                                <span class="fa fa-star-o rating-span"></span>
                                                <span class="fa fa-star-o rating-span"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                                            </div>
                                            <input type="hidden" name="from" value="dashboard">
                                            <input type="hidden" name="rating" value="0">
                                            <input type="hidden" name="to_user_id" value="">
                                            <input type="hidden" name="job_id" value="">
                                            <div class="button_box jb_snbt mb_top" style="margin: 0;">
                                                <button type="button" class="btn btn-blue give_rating" style="margin: 20px 0;">Rate Now</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Give Rating Modal -->
    <!-- View Rating Modal -->
    <div class="modal fade mdl-bgnone m-0 p-0" id="viewRatingModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog jb_wd1" role="document">
            <div class="modal-content m-0 p-0">
                <div class="modal-header">
                    <h4>View Rating</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body m-0 p-0">
                    <div class="model_box">
                        <div class="row m-0 p-0">
                            <div class="col-md-12 m-0 p-0 custom-cols jb_cus3">
                                <div class="right_box">
                                    <div class="model_forms jb_md" style="min-height: unset;">
                                        <div class="form-group my-view-rating">
                                            <label>Given Rating Out Of 5</label>
                                            <br>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Description</label>
                                            <p class="description"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View Rating Modal -->
@endsection
    @push('before-scripts')
    @endpush
    @push('after-scripts')
    @endpush
@section('page-script')
<script src="{{ asset('front/js/moment.js') }}"></script>
<script src="{{ asset('front/js/bootstrap-material-datetimepicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tabul a[href="#{{ $activeTab }}"]').trigger("click"); //$('#tabul a:first').trigger("click");
        $('#changePasswordModel').on('hidden.bs.modal', function() {
            var $alertas = $('#chngpwdFrm');
            $alertas[0].reset();
            $alertas.validate().resetForm();
            $alertas.find('.error').removeClass('error');

            $("#cp-alert-div").hide();
            $("#cp-alert-div").addClass('');
            $("#cp-alert-div span").html('');
        });
        $("#chngpwdFrm").validate({
            rules: {
                current_password: {
                    required: {
                        depends: function () {
                            return $("#chngpwdFrm input[name=is_social]").val() == 0;
                        }
                    }
                },
                password: {
                    required:true,
                    pwcheck:true,
                    minlength: 6
                },
                password_confirmation: {
                    required:true,
                    equalTo: "input[name='password']"
                }
            },
            messages: {
                current_password: {
                    required: "The current password field is required."
                },
                password: {
                    required: "The new password field is required.",
                    minlength: "Your password must be at least 6 characters long."
                },
                password_confirmation: {
                    required: "The confirm password field is required.",
                    equalTo: "Enter Confirm Password Same as Password"
                }
            },
            submitHandler: function(form) {
                var formData =  $(form).serialize();
                var action   =  $("#chngpwdFrm").attr('action');

                $(".app-loader").show();
                $(form).find('input[type="submit"]').attr('disabled', 'disabled').val("Processing...");

                $("#cp-alert-div").hide();
                $("#cp-alert-div").addClass('');
                $("#cp-alert-div span").html('');

                $.when(ajax_request(action,formData)).done(function(data){
                    $(form).find('input[type="submit"]').removeAttr('disabled').val("Submit");
                    $(".app-loader").hide();
                    if($.isEmptyObject(data.errors)){
                        if(data.success){
                            toast_success(data.message);
                            setTimeout(function(){
                                window.location.href = "{{ url('/')}}";
                            },2000);
                        }else{
                            toast_error(data.message);
                        }
                    }else{
                        var error  = '';
                        $(data.errors).each(function (row, val) {
                            error += '<li>' + val + '</li>';
                        });
                        if(error!=''){
                            error  = '<ul>'+error+'</ul>';
                            setTimeout(function(){
                                printMsg('cp-alert-div','alert alert-danger',error);
                            },2000);
                        }else{
                            toast_error('Oops! Something went wrong..');
                        }
                    }
                    return false;
                }).fail(function(jqXHR, status, exception) {
                    $(".app-loader").hide();
                    $(form).find('input[type="submit"]').removeAttr('disabled').val("Submit");
                    ajax_fail(jqXHR, status, exception);
                });
            },
        });

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, function(size){
            return 'File too Big, please select a file less than 5mb';
        });
        $('#changeProfilePhotoForm').on('submit', function(event) {
            $('#pro_img').rules("add", {
                extension: "jpeg|jpg|png|gif",
                filesize: 5000 * 1024
            });
            event.preventDefault();
        });
        $('#changeProfilePhotoForm').validate({
            errorElement: 'span',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass('is-invalid');
            },
            errorPlacement: function (error, element) {
                if (element.attr("type") == "file") {
                    error.insertAfter($(element).closest('.pro_img'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var formData =  new FormData(form);
                var action   =  $("#changeProfilePhotoForm").attr('action');
                $.ajax({
                    url         : action, 
                    type        : "POST",             
                    data        : formData,
                    cache       : false,             
                    processData : false, 
                    dataType    :"json",
                    mimeType    : "multipart/form-data",
                    contentType : false,
                    beforeSend: function () {
                        $(".app-loader").show();
                        $("#pro-img-alert-div").hide();
                        $("#pro-img-alert-div").addClass('');
                        $("#pro-img-alert-div span").html('');
                    }
                }).done(function(data) {
                    $(".app-loader").hide();
                    if($.isEmptyObject(data.errors)){
                        if(data.success){
                            //$('#pro_img_src').attr('src',data.filepath)
                            Toast.fire({
                                type: 'success',
                                title: 'Profile changed successfully'
                            });
                            setTimeout(function(){ 
                                location.reload();
                            },2000);
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: data.message
                            });
                        }
                    }else{
                        var error  = '';
                        $(data.errors).each(function (row, val) {
                            error += '<li>' + val + '</li>';
                        });
                        if(error!=''){
                            error  = '<ul>'+error+'</ul>';
                            setTimeout(function(){ 
                                printMsg('pro-img-alert-div','alert alert-danger',error);
                            },2000);
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Oops! Something went wrong..'
                            });
                        }
                    }
                }).fail(function(jqXHR, status, exception) {
                    $(".app-loader").hide();
                    if (jqXHR.status === 0) {
                        error = 'Not connected.\nPlease verify your network connection.';
                    } else if (jqXHR.status == 404) {
                        error = 'The requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        error = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        error = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        error = 'Time out error.';
                    } else if (exception === 'abort') {
                        error = 'Ajax request aborted.';
                    } else {
                        error = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    Toast.fire({
                        type: 'error',
                        title: error
                    });
                });
                return false;
            }
        });
        $('input[name="profile_photo_path"]').change(function() {
            $('#changeProfilePhotoForm').submit();
        });
    });
    $('#tabul a').on('show.bs.tab', function(){
        var pane = $(this);
        var type = pane.data('type');
        var href = this.hash;
        if(type!='BankDetails' && type!='MyPlans')
            nextTabPaneView(pane,href,type);
    });
    $('#tabul a').on('hide.bs.tab', function(e){
        var href = this.hash;
        let type = $(this).data('type');
        if(type!='BankDetails' && type!='MyPlans')
            $(href).html('');
    });
    $(document).on("click", ".button-search", function() {
        var that        = $(this);
        var searchtxt   = that.closest('.search_box').find('input[name ="searchtxt"]').val();
        var tabThat = $("#tabul a.active");
        nextTabPaneView(tabThat,tabThat.attr('href'),tabThat.data('type'),searchtxt);
    });
    $(document).on("keypress", 'input[name ="searchtxt"]', function(e) {
        if(e.which == 13){//Enter key pressed
            $('.button-search').click();//Trigger search button click event
        }
    });
    function nextTabPaneView(pane,href,type,searchtxt=''){
        $(href).html('');
        let url  = '{{ route("profile.tab.data",[":type"]) }}';
        url     = url.replace(':type', type);
        if(url!=''){
            $.ajax({
                type: "POST",
                url: url,
                data: {searchtxt:searchtxt},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                beforeSend: function () {
                    $(".app-loader").show();
                }
            }).done(function( response ) {
                $(href).html(response.html);
            }).fail(function(jqXHR, status, exception)  {
                if (jqXHR.status === 0) {
                    error = 'Not connected.\nPlease verify your network connection.';
                } else if (jqXHR.status == 404) {
                    error = 'The requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    error = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    error = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    error = 'Time out error.';
                } else if (exception === 'abort') {
                    error = 'Ajax request aborted.';
                } else {
                    error = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                Toast.fire({
                    type: 'error',
                    title: error
                });
            }).always(function() {
                setTimeout(function(){
                    $(".app-loader").hide();
                },500);
            });
        }
    }
</script>
<script type="text/javascript">
    profileEditFrm = $('#profileEditModel #profileEditForm');
    $(document).ready(function() {
        var proCountryCodeSelect    = $('#profileEditForm select[name="country_code"]');
        proCountryCodeSelect.select2({
            width:'100%',
            allowClear: true,
            templateResult: proShowFlag,
            templateSelection: proShowFlag,
            placeholder: "Code",
            dropdownParent: "#profileEditModel",
            ajax:{
                url: "{{ route('ajax.country.code.list') }}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                type:"POST",
                //delay: 250,
                data: function(term) {
                    return {
                        search:term.term
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: '+'+item.text,
                                id: item.id,
                                srt_code: item.srt_code,
                                name: item.name,
                            }
                        })
                    };
                }
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            //console.log($("#profileEditForm select[name='country_code'] option:selected"));
            $(this).valid();
        });

        var proCountrySelect        = $('#profileEditForm select[name="country_id"]');
        proCountrySelect.select2({
            width:'100%',
            allowClear: true,
            placeholder: "Country",
            dropdownParent: "#profileEditModel",
            ajax:{
                url: "{{ route('ajax.country.list') }}",
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                type:"POST",
                data: function(term) {
                    return {
                        search:term.term
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.text,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        var proStateSelect          = $('#profileEditForm select[name="state_id"]');
        proStateSelect.select2({
            width:'100%',
            allowClear: true,
            placeholder: "State",
            dropdownParent: "#profileEditModel"
        });
        var proCitySelect           = $('#profileEditForm select[name="city_id"]');
        proCitySelect.select2({
            width:'100%',
            allowClear: true,
            placeholder: "City",
            dropdownParent: "#profileEditModel"
        });

        proCountrySelect.change(function(){
            var countryID           = $(this).val();
            if(countryID){ 
                //console.log('country id ==> '+countryID);
                $(this).valid();
                proStateSelect.val(null).trigger("change");
                proStateSelect.select2({
                    width:'100%',
                    allowClear: true,
                    placeholder: "State",
                    dropdownParent: "#profileEditModel",
                    ajax:{
                        url: "{{ route('ajax.state.list') }}",
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                        type:"POST",
                        //delay: 250,
                        async:false,
                        data: function(term) {
                            return {
                                search:term.term,
                                country_id:countryID
                            };
                        },
                        processResults: function(data, page) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.text,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }else{
                //console.log('country id ==> blank');
                proStateSelect.val(null).trigger("change");
            }
        });
        proStateSelect.change(function(){
            var stateID             = $(this).val();
            if(stateID){ 
                //console.log('state id ==> '+stateID);
                $(this).valid();
                proCitySelect.val(null).trigger("change");
                proCitySelect.select2({
                    width:'100%',
                    allowClear: true,
                    placeholder: "City",
                    dropdownParent: "#profileEditModel",
                    ajax:{
                        url: "{{ route('ajax.city.list') }}",
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                        type:"POST",
                        //delay: 250,
                        async:false,
                        data: function(term) {
                            return {
                                search:term.term,
                                state_id:stateID
                            };
                        },
                        processResults: function(data, page) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.text,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }else{
                //console.log('state id ==> blank');
                proCitySelect.val(null).trigger("change");
            }
        });
        proCitySelect.change(function(){
            var cityID             = $(this).val();
            if(cityID){ 
                //console.log('city id ==> '+cityID);
                $(this).valid();
            }else{
                //console.log('city id ==> blank');
            }
        });

        $('#profileEditModel').on('shown.bs.modal', function (e) {
            var user_id = profileEditFrm.find("input[name='user_id']").val();
            profileEditFrm.find("input[name='dob']").bootstrapMaterialDatePicker({
                format: 'MM/DD/YYYY',
                time: false,
                maxDate: new Date(),
                container: '#profileEditModel .modal-body'
            }).on('change', function (e, date) {
                $(this).valid();
            });
            if(user_id){
                $.get("{{ route('users.detail') }}", {
                    id: user_id,
                    beforeSend: function () {
                        $("#profile-edit-alert-div").show();
                        $("#profile-edit-alert-div"+" span").html('<div class="row"><div class="col-sm-12 text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div></div>');
                    }
                }).done(function(data) {
                    if (data.success) {
                        user = data.user;
                        if (user.email) {
                            profileEditFrm.find("input[name='email']").val(user.email);
                        }
                        if (user.first_name) {
                            profileEditFrm.find("input[name='first_name']").val(user.first_name);
                        }
                        if (user.last_name) {
                            profileEditFrm.find("input[name='last_name']").val(user.last_name);
                        }
                        if (user.phone) {
                            profileEditFrm.find("input[name='phone']").val(user.phone);
                        }
                        if(user.country_code){
                            $.ajax({
                                url: '{{ route("ajax.country.code.select") }}',
                                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                                type: 'POST',
                                dataType: 'json',
                                data:{id:user.country_code},
                                async: false,
                            }).then(function (data) {
                                if(data.success){
                                    var key = "success";
                                    delete data[key];
                                    proCountryCodeSelect.select2("trigger", "select", {
                                        data: { id: data.id, text:'+'+data.text, srt_code: data.srt_code, name: data.name }
                                    });
                                }
                            });
                        }
                        if(user.country_id){
                            $.ajax({
                                url: '{{ route("ajax.country.select") }}',
                                headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                                type: 'POST',
                                dataType: 'json',
                                data:{id:user.country_id},
                                async: false,
                            }).then(function (data) {
                                if(data.success){
                                    var key = "success";
                                    delete data[key];
                                    proCountrySelect.select2("trigger", "select", {
                                        data: { id: data.id, text: data.text }
                                    });
                                }
                            });
                            if(user.state_id){
                                $.ajax({
                                    url: '{{ route("ajax.state.select") }}',
                                    headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                                    type: 'POST',
                                    dataType: 'json',
                                    data:{id:user.state_id},
                                    async: false,
                                }).then(function (data) {
                                    if(data.success){
                                        var key = "success";
                                        delete data[key];
                                        proStateSelect.select2("trigger", "select", {
                                            data: { id: data.id, text: data.text }
                                        });
                                    }
                                });
                                if(user.city_id){
                                    $.ajax({
                                        url: '{{ route("ajax.city.select") }}',
                                        headers: {'X-CSRF-TOKEN': Laravel.csrfToken},
                                        type: 'POST',
                                        dataType: 'json',
                                        data:{id:user.city_id},
                                        async: false,
                                    }).then(function (data) {
                                        if(data.success){
                                            var key = "success";
                                            delete data[key];
                                            proCitySelect.select2("trigger", "select", {
                                                data: { id: data.id, text: data.text }
                                            });
                                        }
                                    });
                                }
                            }
                        }
                        if (user.dob) {
                            profileEditFrm.find("input[name='dob']").val(myDateFormatter(user.dob));
                        }
                        if (user.address) {
                            profileEditFrm.find("input[name='address']").val(user.address);
                        }
                        if (user.latitude) {
                            profileEditFrm.find("input[name='latitude']").val(user.latitude);
                        }
                        if (user.longitude) {
                            profileEditFrm.find("input[name='longitude']").val(user.longitude);
                        }
                        if (user.address_line_2) {
                            profileEditFrm.find("textarea[name='address_line_2']").val(user.address_line_2);
                        }
                        if (user.pin_code) {
                            profileEditFrm.find("input[name='pin_code']").val(user.pin_code);
                        }
                        setTimeout(function(){
                            $("#profile-edit-alert-div").hide();
                            $("#profile-edit-alert-div"+" span").html('');
                            $("#profile-edit-form").show();
                        },200);
                    }else{
                        $('#profileEditModel').modal('hide');
                        Toast.fire({
                            type: 'error',
                            title: 'User record not found.'
                        });
                    }
                }).fail(function(jqXHR, exception) {
                    $('#profileEditModel').modal('hide');
                    var message = 'Oops! Something went wrong..';
                    if (jqXHR.status === 0) {
                        message = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        message = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        message = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        message = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        message = 'Time out error.';
                    } else if (exception === 'abort') {
                        message = 'Ajax request aborted.';
                    } else {
                        message = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    Toast.fire({
                        type: 'error',
                        title: message
                    });
                });
            }
        });
        $('#profileEditModel').on('hidden.bs.modal', function() {
            $('#profileEditForm select[name="country_code"]').val(null).trigger('change');
            $('#profileEditForm select[name="country_id"]').val(null).trigger('change');
            $('#profileEditForm select[name="state_id"]').val(null).trigger('change');
            $('#profileEditForm select[name="city_id"]').val(null).trigger('change');

            var $alertas = $('#profileEditForm');
            $alertas[0].reset();
            $alertas.validate().resetForm();
            $alertas.find('.error').removeClass('error');

            $("#profile-edit-form").hide();
            $("#profile-edit-alert-div").hide();
            $("#profile-edit-alert-div").addClass('');
            $("#profile-edit-alert-div span").html('');
        });
        $("#profileEditForm").validate({
            errorElement: 'span',
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass('is-invalid');
            },
            errorPlacement: function (error, element) {
                if(element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            rules: {
                first_name: {
                    required:true
                },
                last_name: {
                    required:true
                },
                country_code: {
                    required:true
                },
                phone:{
                    required:true,
                    remote: {
                        url: '{!! route("users.exists") !!}',
                        type: "POST",
                        data:{id:$("#user_id").val(),type:3},
                        headers: {'X-CSRF-TOKEN': Laravel.csrfToken}
                    }
                },
                dob: {
                    required:true
                },
                country_id: {
                    required:true
                },
                state_id: {
                    required:true
                },
                city_id: {
                    required:true
                },
                pin_code: {
                    required:true
                },
                address: {
                    required:true
                },
                address_line_2: {
                    required:true
                }
            },
            messages: {
                first_name: {
                    required: "The firstname field is required."
                },
                last_name: {
                    required: "The lastname field is required."
                },
                country_code: {
                    required: "The country code field is required."
                },
                phone: {
                    required: "The phone number field is required.",
                    remote: "The phone number has already been taken."
                },
                dob: {
                    required: "The dob field is required."
                },
                country_id: {
                    required: "The country field is required."
                },
                state_id: {
                    required: "The state field is required."
                },
                city_id: {
                    required: "The city field is required."
                },
                pin_code: {
                    required: "The pin code field is required."
                },
                address: {
                    required: "The address line 1 field is required."
                },
                address_line_2: {
                    required: "The address line 2 field is required."
                }
            },
            submitHandler: function(form) {
                var formData =  new FormData(form);
                var action   =  $("#profileEditForm").attr('action');
                $.ajax({
                    url         : action,
                    type        : "POST",
                    data        : formData,
                    cache       : false,
                    processData : false,
                    dataType    :"json",
                    mimeType    : "multipart/form-data",
                    contentType : false,
                    beforeSend: function () {
                        $(".app-loader").show();

                        $(form).find('input[type="submit"]').attr('disabled', 'disabled').val("Processing...");

                        $("#profile-edit-alert-div").hide();
                        $("#profile-edit-alert-div").addClass('');
                        $("#profile-edit-alert-div span").html('');
                    }
                }).done(function(data) {
                    $(".app-loader").hide();
                    $(form).find('input[type="submit"]').removeAttr('disabled').val("Update");
                    if($.isEmptyObject(data.errors)){
                        if(data.success){
                            Toast.fire({
                                type: 'success',
                                title: data.message
                            });
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: data.message
                            });
                        }
                    }else{
                        var error  = '';
                        $(data.errors).each(function (row, val) {
                            error += '<li>' + val + '</li>';
                        });
                        if(error!=''){
                            error  = '<ul>'+error+'</ul>';
                            setTimeout(function(){
                                printMsg('profile-edit-alert-div','alert alert-danger',error);
                            },2000);
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Oops! Something went wrong..'
                            });
                        }
                    }
                }).fail(function(jqXHR, status, exception) {
                    $(".app-loader").hide();
                    $(form).find('input[type="submit"]').removeAttr('disabled').val("Update");
                    if (jqXHR.status === 0) {
                        error = 'Not connected.\nPlease verify your network connection.';
                    } else if (jqXHR.status == 404) {
                        error = 'The requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        error = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        error = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        error = 'Time out error.';
                    } else if (exception === 'abort') {
                        error = 'Ajax request aborted.';
                    } else {
                        error = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    Toast.fire({
                        type: 'error',
                        title: error
                    });
                });
                return false;
            },
        });
    });  
    function proShowFlag (country) {
        $(country.element).attr("data-srt_code", country.text);
        if(typeof country.srt_code !== 'undefined'){
            return $("<span><i class=\"flag flag-" + country.srt_code.toLowerCase() + "\"></i> " + country.text +' '+ country.srt_code + "</span>");
        }else{
            return 'Code';
        }
    }
    function myDateFormatter (dateObject) {
            var d       = new Date(dateObject);
            var day     = d.getDate();
            var month   = d.getMonth() + 1;
            var year    = d.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date = month + "/" + day + "/" + year;
            return date;
        }; 
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#addDetailsForm").validate({
            rules: {
                account_holder_type: {
                    required:true,
                },
                routing_number: {
                    required:true,
                },
                account_number: {
                    required:true,
                }
            },
            messages: {
                account_holder_type: {
                    required:"The account holder type field is required",
                },
                routing_number: {
                    required:"The routing number field is required",
                },
                account_number: {
                    required:"The account number field is required",
                }
            },
            submitHandler: function(form) {
                var formData =  $(form).serialize();
                var action   =  $(form).attr('action');

                $(".app-loader").show();
                $(form).find('input[type="submit"]').attr('disabled', 'disabled').val("Processing...");

                $("#bank-details-alert-div").hide();
                $("#bank-details-alert-div").addClass('');
                $("#bank-details-alert-div span").html('');

                $.when(ajax_request(action,formData)).done(function(data){
                    $(form).find('input[type="submit"]').removeAttr('disabled').val("Submit");
                    $(".app-loader").hide();
                    if($.isEmptyObject(data.errors)){
                        if(data.success){
                            toast_success(data.message);
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }else{
                            toast_error(data.message);
                        }
                    }else{
                        var error  = '';
                        $(data.errors).each(function (row, val) {
                            error += '<li>' + val + '</li>';
                        });
                        if(error!=''){
                            error  = '<ul>'+error+'</ul>';
                            setTimeout(function(){
                                printMsg('bank-details-alert-div','alert alert-danger',error);
                            },2000);
                        }else{
                            toast_error('Oops! Something went wrong..');
                        }
                    }
                    return false;
                }).fail(function(jqXHR, status, exception) {
                    $(".app-loader").hide();
                    $(form).find('input[type="submit"]').removeAttr('disabled').val("Submit");
                    ajax_fail(jqXHR, status, exception);
                });
            },
        });
    });
    $(document).on('click', '#delete_bank_account', function(e){
        e.preventDefault();
        let bank_account_id = $(this).data('id');
        if(typeof bank_account_id !== "undefined"){
            swal({
                title: 'Are you sure you want to remove this account?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: "#d33",
                confirmButtonClass: 'btn btn-success',
                cancelButtonText: 'No, cancel!',
                cancelButtonColor: '#3085d6',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: true,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-Token': Laravel.csrfToken},
                        url: '{!! route("profile.deleteBank_details") !!}',
                        dataType : 'json',
                        data: {bank_account_id:bank_account_id},
                        success: function(response) {
                            if(response.success == true){
                                Toast.fire({
                                    type: 'success',
                                    title: response.message
                                });
                                setTimeout(function(){
                                    location.reload();
                                },2000);
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: response.message
                                });
                            }
                        },
                        error: function (jqXHR, status, exception) {
                            if (jqXHR.status === 0) {
                                error = 'Not connected.\nPlease verify your network connection.';
                            } else if (jqXHR.status == 404) {
                                error = 'The requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                error = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                error = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                error = 'Time out error.';
                            } else if (exception === 'abort') {
                                error = 'Ajax request aborted.';
                            } else {
                                error = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            Toast.fire({
                                type: 'error',
                                title: error
                            });
                        }
                    });
                }
            });
        }
    });
    $(document).on('click', '#edit_bank_account', function(e){
        e.preventDefault();
        let bank_account_id = $(this).data('id');
        if ($("#addDetailsForm input[name='bank_account_id']").length === 0)
            $("#addDetailsForm").append('<input type="hidden" name="bank_account_id" value="'+bank_account_id+'" />');
        else
            $("#addDetailsForm input[name='bank_account_id']").val(bank_account_id);

        $("#addDetailsForm").attr('action','{{ route('profile.updateBank_details') }}');
        $('.bank_detail_title').html('Update Bank Details');
        $('#account_holder_type').val($('.account_holder_type').text());
        $('#routing_number').val($('.routing_number').text());
        $('#account_number').val($('.account_number').text());
    });
</script>
<?php /*
<script src="https://maps.google.com/maps/api/js?libraries=places&key={{ env('GEO_LOC_KEY','AIzaSyCrqQAPZKEfYMpShwT3aRi-5To1rvMF8zY')}}"></script>
<script type="text/javascript">
    var input           = $('#profileEditModel input[name="address"]')[0];
    var autocomplete    = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        // input.className = '';
        var place       = autocomplete.getPlace();
        var lat_long    = place.geometry.location.toJSON();
        $('#profileEditModel input[name="latitude"]').val(lat_long.lat);
        $('#profileEditModel input[name="longitude"]').val(lat_long.lng);

        var address     = '';
        if(place.address_components){
            address     = [
            (place.address_components[0] && place.address_components[0].short_name || ''), (place.address_components[1] && place.address_components[1].short_name || ''), (place.address_components[2] && place.address_components[2].short_name || '')].join(' ');
        }
    });
</script>
*/ ?>
<script type="text/javascript">
    $('body').on('click','.btn_rating', function(e){
        _th = $(this);
        form = $("#form_rating");
        form.find('span.error').remove();
        form.find('input[name="rating"],textarea').val("");
        to_user_id = _th.closest('div').find('.to_user_id').val();
        job_id = _th.closest('div').find('.job_id').val();
        form.find('input[name="job_id"]').val(job_id);
        form.find('input[name="to_user_id"]').val(to_user_id);
        $("#giveRatingModel").modal('show');
    });
    $('body').on('click','.view_rating', function(e){
        _th = $(this);
        
        given_star = parseInt(_th.closest('div').find('.given_star').val());
        given_desc = _th.closest('div').find('.given_desc').val();
        modal = $("#viewRatingModel");
        modal.find('.my-view-rating .fa-star').remove();
        rdv = modal.find('.my-view-rating');
        pen_star = 5 - given_star;
        if(given_star > 0){
            for (var i = 0; i < given_star; i++) {
                rdv.append('<span class="fa fa-star checked"></span>');
            }
        }
        if(pen_star > 0){
            for (var i = 0; i < pen_star; i++) {
                rdv.append('<span class="fa fa-star-o"></span>');
            }
        }
        modal.find('p.description').html(given_desc);
        modal.modal('show');
    });
</script>
@include('front.inc_rating')
@endsection
