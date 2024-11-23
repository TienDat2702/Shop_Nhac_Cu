@extends('user.layouts.app')
@section('title', 'Đơn hàng của bạn')
@section('content')
    <style>
        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem .625rem !important;
            background-color: #6a6e51 !important;
        }

        .table>tr>td {
            padding: 0.625rem 1.5rem .625rem !important;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }

        .table> :not(caption)>tr>td {
            padding: .8rem 1rem !important;
        }

        .bg-success {
            background-color: #40c710 !important;
        }

        .bg-danger {
            background-color: #f44032 !important;
        }

        .bg-warning {
            background-color: #f5d700 !important;
            color: #000;
        }
    </style>
    <main class="pt-90">
        <main class="pt-90" style="padding-top: 0px;">
            <div class="mb-4 pb-4"></div>
            <section class="my-account container">
                <h2 class="page-title">Orders</h2>
                <div class="row">
                    <div class="col-lg-2">
                        <ul class="account-nav">
                            @include('user.layouts.component.sidebarUser')
                        </ul>
                    </div>

                    <div class="col-lg-10">
                        <div class="page-content my-account__edit">
                            <div class="my-account__edit-form">
                              <form name="account_edit_form" action="#" method="POST" class="needs-validation" novalidate="">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-floating my-3">
                                      <input type="text" class="form-control" placeholder="Full Name" name="name" value="" required="">
                                      <label for="name">Name</label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-floating my-3">
                                      <input type="text" class="form-control" placeholder="Mobile Number" name="mobile" value=""
                                        required="">
                                      <label for="mobile">Mobile Number</label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-floating my-3">
                                      <input type="email" class="form-control" placeholder="Email Address" name="email" value=""
                                        required="">
                                      <label for="account_email">Email Address</label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="my-3">
                                      <h5 class="text-uppercase mb-0">Password Change</h5>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-floating my-3">
                                      <input type="password" class="form-control" id="old_password" name="old_password"
                                        placeholder="Old password" required="">
                                      <label for="old_password">Old password</label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-floating my-3">
                                      <input type="password" class="form-control" id="new_password" name="new_password"
                                        placeholder="New password" required="">
                                      <label for="account_new_password">New password</label>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="form-floating my-3">
                                      <input type="password" class="form-control" cfpwd="" data-cf-pwd="#new_password"
                                        id="new_password_confirmation" name="new_password_confirmation"
                                        placeholder="Confirm new password" required="">
                                      <label for="new_password_confirmation">Confirm new password</label>
                                      <div class="invalid-feedback">Passwords did not match!</div>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="my-3">
                                      <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                    </div>

                </div>
            </section>
        </main>
    @endsection
