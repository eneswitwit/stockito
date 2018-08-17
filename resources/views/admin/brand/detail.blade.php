<div class="col-md-12">
    <div class="box box-info">
        <form class="form-horizontal" method="post" action="{{ route('admin.brand.edit', ['id'=>$Brand->id]) }}" >
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="box-body">
                <div class="form-group">
                    <label for="inputBrandname" class="col-sm-2 control-label">@lang('admin.brandname')</label>

                    <div class="col-sm-10">
                        <input type="text" name="brand_name" class="form-control" value="{{$Brand->brand_name}}"
                               id="inputBrandname" placeholder="@lang('admin.brandname')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">@lang('admin.email')</label>

                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" value="{{$BrandUser->email}}"
                               id="inputEmail" placeholder="@lang('admin.email')">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSubscription" class="col-sm-2 control-label">@lang('admin.subscription')</label>

                    <div class="col-sm-10">
                        <input type="text" name="subscription" class="form-control"
                               id="inputSubscription" placeholder="Subscription" disabled>
                    </div>
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">@lang('admin.billing_address')</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="inputCompanyname"  class="col-sm-2 control-label">@lang('admin.companyname')</label>
                    <div class="col-sm-10">
                        <input type="text"
                               name="company_name"
                               class="form-control"
                               value="{{$Brand->company_name}}"
                               id="inputCompanyname" placeholder="@lang('admin.companyname')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputAdressline" class="col-sm-2 control-label">@lang('admin.adressline_1')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{$Brand->address_1}}"
                               name="address_1"
                               id="inputAdressline" placeholder="@lang('admin.adressline_1')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputAdressline2" class="col-sm-2 control-label">@lang('admin.adressline_2')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"  value="{{$Brand->address_2}}"
                               name="address_2"
                               id="inputAdressline2" placeholder="@lang('admin.adressline_2')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputCity" class="col-sm-2 control-label">@lang('admin.city')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               name="city"
                               value="{{$Brand->city}}"
                               id="inputCity" placeholder="@lang('admin.city')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputZIPCode" class="col-sm-2 control-label">@lang('admin.zip_code')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"  value="{{$Brand->zip}}"
                               name="zip"
                               id="inputZIPCode" placeholder="@lang('admin.zip_code')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEuropean" class="col-sm-2 control-label">@lang('admin.country')</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="country_id">
                            @foreach($Countries as $contry)
                                <option value="{{$contry->id}}"
                                        @if($Brand->country_id ==$contry->id) selected="selected" @endif >
                                    {{$contry->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputHomepage" class="col-sm-2 control-label">@lang('admin.homepage')</label>
                    <div class="col-sm-10">
                        <input type="url" class="form-control"  value="{{$Brand->homepage}}"
                               name="homepage"
                               id="inputHomepage" placeholder="@lang('admin.homepage')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPhone" class="col-sm-2 control-label">@lang('admin.phone')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"   value="{{$Brand->phone}}"
                               name="phone"
                               id="inputPhone" placeholder="@lang('admin.phone')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputFirstname" class="col-sm-2 control-label">@lang('admin.firstname')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               value="{{$Brand->contact_first_name}}"
                               name="contact_first_name"
                               id="inputFirstname" placeholder="@lang('admin.firstname')">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputLastname" class="col-sm-2 control-label">@lang('admin.lastname')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               name="contact_last_name"
                               value="{{$Brand->contact_last_name}}"
                               id="inputLastname" placeholder="@lang('admin.lastname')">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputTitle" class="col-sm-2 control-label">@lang('admin.title')</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"
                               name="contact_title"
                               value="{{$Brand->contact_title}}"  id="inputTitle" placeholder="@lang('admin.title')">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputTitle" class="col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-info">@lang('admin.edit_btn')</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-sm-6 col-sm-offset-2">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.usage')</h3>
                </div>
                <div class="box-body">
                    <ul class="nav nav-stacked">
                        <li>Total Storage <span class="pull-right badge bg-blue">@TODO</span></li>
                        <li>Used Storage <span class="pull-right badge bg-aqua">{{$Brand->format_used_storage}}</span></li>
                        <li>Last Login <span class="pull-right badge bg-green">{{$BrandUser->last_login}}</span></li>
                    </ul>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th>Connected Creatives to this Brand</th>
                            <th style="width: 40px"></th>
                        </tr>
                        @foreach($Brand->creatives as $creative)
                            <tr>
                                <td>{{$creative->first_name}} {{$creative->first_name}}</td>
                                <td><a href="{{ route('admin.creative.detail', ['id'=>$creative->id]) }}">Show</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div class="box-header with-border">
            <h3 class="box-title">Invoices</h3>
        </div>

    </div>
</div>